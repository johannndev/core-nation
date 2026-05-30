<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\Restock;
use App\Models\RestockHistory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class RestockImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public $date, $type;
    public $errors = [];

    public function __construct($date, $type)
    {
        $this->date = $date;
        $this->type = $type;
    }

    public function chunkSize(): int
    {
        return 1000; // RAM SAFE
    }

    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) return;

        $date = $this->date;
        $type = $this->type;
        $now  = now();

        // ================================
        // FIELD MAP
        // ================================
        $map = [
            'restocked'  => ['inc' => 'restocked_quantity', 'dec' => null],
            'production' => ['inc' => 'in_production_quantity', 'dec' => 'restocked_quantity'],
            'shipped'    => ['inc' => 'shipped_quantity', 'dec' => 'in_production_quantity'],
            'missing'    => ['inc' => 'missing_quantity', 'dec' => null],

            // RESET MODE
            'restocked_reset'  => ['reset' => 'restocked_quantity'],
            'production_reset' => ['reset' => 'in_production_quantity'],
            'shipped_reset'    => ['reset' => 'shipped_quantity'],
            'missing_reset'    => ['reset' => 'missing_quantity'],
        ];

        if (!isset($map[$type])) {
            throw new \Exception("Invalid import type: {$type}");
        }

        $isReset    = str_ends_with($type, '_reset');
        $incField   = $map[$type]['inc'] ?? null;
        $decField   = $map[$type]['dec'] ?? null;
        $resetField = $map[$type]['reset'] ?? null;

        // ================================
        // 1️⃣ GET ITEM ID / CODE
        // ================================
        $keys = $rows->pluck(0)->map(fn($v) => trim($v))->unique();

        // $items = Item::whereIn('id', $keys)
        //     ->orWhereIn('code', $keys)
        //     ->get(['id', 'code']);

        $items = Item::whereIn('id', $keys)
            ->orWhereIn('code', $keys)
            ->get(['id', 'code', 'name']);

        $itemMap = [];

        foreach ($items as $item) {
            $itemMap[$item->id] = $item;
            $itemMap[$item->code] = $item;
        }

        $idMap = $items->pluck('id', 'code')->toArray();
        $validIds   = $items->pluck('id');
        $validCodes = $items->pluck('code');

        // ================================
        // 2️⃣ VALIDATE ITEM EXIST
        // ================================
        // $notFoundItems = $keys->diff($validIds)->diff($validCodes);
        // if ($notFoundItems->isNotEmpty()) {
        //     $this->errors = $notFoundItems->toArray();
        //     return;
        // }

        $notFoundItems = $keys->diff($validIds)->diff($validCodes);

        if ($notFoundItems->isNotEmpty()) {

            foreach ($notFoundItems as $item) {
                $this->errors[] =
                    "Item '{$item}' tidak ditemukan pada master item";
            }

            return;
        }

        $itemIds = $keys->map(fn($k) => is_numeric($k) ? $k : $idMap[$k]);

        // ================================
        // 3️⃣ LOAD RESTOCK TODAY
        // ================================
        $restocks = Restock::whereIn('item_id', $itemIds)
            ->whereDate('date', $date)
            ->get()
            ->keyBy('item_id');

        // ================================
        // 4️⃣ VALIDATE RESTOCK FOR NORMAL MODE
        // ================================
        // if (!$isReset && in_array($type, ['production', 'shipped', 'missing'])) {
        //     $missingRestock = $itemIds->diff($restocks->keys());
        //     if ($missingRestock->isNotEmpty()) {
        //         $this->errors = $missingRestock->toArray();
        //         return;
        //     }
        // }

        if (!$isReset && in_array($type, ['production', 'shipped', 'missing'])) {

            $missingRestock = $itemIds->diff($restocks->keys());

            if ($missingRestock->isNotEmpty()) {

                foreach ($missingRestock as $itemId) {

                    $item = $items->firstWhere('id', $itemId);

                    $this->errors[] =
                        "Restock tanggal {$date} belum dibuat | "
                        . "ID: {$item->id} | "
                        . "Code: {$item->code} | "
                        . "Nama: {$item->name}";
                }

                return;
            }
        }

        $restockCreates = [];
        $restockUpdates = [];
        $historyInsert  = [];

        // ================================
        // 5️⃣ LOOP DATA
        // ================================
        foreach ($rows as $index => $row) {

            $excelRow = $index + 2; // +2 karena baris 1 biasanya header

            $key = trim((string) ($row[0] ?? ''));

            if ($key === '') {

                $this->errors[] =
                    "Baris {$excelRow}: Item ID / Code tidak boleh kosong";

                continue;
            }

            $qty = (int) ($row[1] ?? 0);
            $itemId = is_numeric($key)
                ? $key
                : $idMap[$key];



            // ================= RESET MODE =================
            if ($isReset) {

                if (!$restocks->has($itemId)) continue; // tidak auto create

                $r = $restocks[$itemId];
                $before = $r->$resetField;
                $after  = 0;

                $restockUpdates[] = [
                    'id' => $r->id,
                    $resetField => 0,
                    'updated_at' => $now,
                ];

                $historyInsert[] = [
                    'restock_id' => $r->id,
                    'item_id' => $itemId,
                    'step' => $type,
                    'action' => 'reset',
                    'qty_before' => $before,
                    'qty_after' => 0,
                    'qty_changed' => -$before,
                    'invoice' => null,
                    'user_id' => auth()->id(),
                    'date' => $date,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                continue;
            }

            // ================= NORMAL MODE (WAJIB QTY) =================
            if ($qty <= 0) {
                $item = $items->firstWhere('id', $itemId);

                $this->errors[] =
                    "Baris {$excelRow}: Qty harus lebih dari 0 | "
                    . "Code: {$item->code} | "
                    . "Nama: {$item->name}";

                continue;
            }

            // CREATE DAILY RESTOCK (ONLY restocked mode)
            if (!$restocks->has($itemId) && $type == 'restocked') {

                $restockCreates[] = [
                    'item_id' => $itemId,
                    'date' => $date,
                    'status' => 1,
                    'restocked_quantity' => $qty,
                    'in_production_quantity' => 0,
                    'shipped_quantity' => 0,
                    'missing_quantity' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                $before = 0;
                $after  = $qty;
                $restockId = null;
                $action = 'created';
            } else {

                $r = $restocks[$itemId];

                if ($decField && $r->$decField < $qty) {
                    $item = $items->firstWhere('id', $itemId);

                    $fieldName = match ($decField) {
                        'restocked_quantity' => 'Restocked',
                        'in_production_quantity' => 'Production',
                        default => $decField,
                    };

                    $this->errors[] =
                        "Baris {$excelRow}: Stock {$fieldName} tidak cukup | "
                        . "Code: {$item->code} | "
                        . "Nama: {$item->name} | "
                        . "Available: {$r->$decField} | "
                        . "Request: {$qty}";

                    continue;
                }

                $before = $r->$incField;
                $after  = $before + $qty;

                $update = [
                    'id' => $r->id,
                    $incField => $after,
                    'updated_at' => $now,
                ];

                if ($decField) {
                    $update[$decField] = $r->$decField - $qty;
                }

                $restockUpdates[] = $update;
                $restockId = $r->id;
                $action = 'updated';
            }

            $historyInsert[] = [
                'restock_id' => $restockId,
                'item_id' => $itemId,
                'step' => $type,
                'action' => $action,
                'qty_before' => $before,
                'qty_after' => $after,
                'qty_changed' => $qty,
                'invoice' => null,
                'user_id' => auth()->id(),
                'date' => $date,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // ================================
        // 6️⃣ EXECUTE DB
        // ================================

        if (!empty($this->errors)) {
            return;
        }

        DB::transaction(function () use ($restockCreates, $restockUpdates, &$historyInsert, $date) {

            if ($restockCreates) {
                Restock::insert($restockCreates);
            }

            if (!empty($restockUpdates)) {
                $cols = array_keys(reset($restockUpdates));
                $cols = array_diff($cols, ['id']);

                Restock::upsert(
                    $restockUpdates,
                    ['id'],
                    $cols
                );
            }

            // map restock_id for new rows
            $newRestocks = Restock::whereDate('date', $date)
                ->get(['id', 'item_id'])
                ->keyBy('item_id');

            foreach ($historyInsert as &$h) {
                if (!$h['restock_id']) {
                    $h['restock_id'] = $newRestocks[$h['item_id']]->id ?? null;
                }
            }

            if ($historyInsert) {
                RestockHistory::insert($historyInsert);
            }
        });
    }
}
