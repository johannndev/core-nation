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
        // 1️⃣ GET ITEM ID / CODE
        // ================================
        $keys = $rows->pluck(0)->map(fn($v) => trim($v))->unique();

        $items = Item::whereIn('id', $keys)
            ->orWhereIn('code', $keys)
            ->get(['id', 'code']);

        $idMap = $items->pluck('id', 'code')->toArray();
        $validIds   = $items->pluck('id');
        $validCodes = $items->pluck('code');

        // ================================
        // 2️⃣ VALIDATE ITEM EXIST
        // ================================
        $notFoundItems = $keys->diff($validIds)->diff($validCodes);
        if ($notFoundItems->isNotEmpty()) {
            $this->errors = $notFoundItems->toArray();
            return;
        }

        $itemIds = $keys->map(fn($k) => is_numeric($k) ? $k : $idMap[$k]);

        // ================================
        // 3️⃣ LOAD RESTOCK CACHE
        // ================================
        $restocks = Restock::whereIn('item_id', $itemIds)
            ->whereDate('date', $date)
            ->get()
            ->keyBy('item_id');

        // ================================
        // 4️⃣ VALIDATE RESTOCK FOR TYPE
        // ================================
        if (in_array($type, ['production', 'shipped', 'missing'])) {
            $missingRestock = $itemIds->diff($restocks->keys());
            if ($missingRestock->isNotEmpty()) {
                $this->errors = $missingRestock->toArray();
                return;
            }
        }

        // ================================
        // 5️⃣ FIELD MAP
        // ================================
        $map = [
            'restocked'  => ['inc' => 'restocked_quantity', 'dec' => null],
            'production' => ['inc' => 'in_production_quantity', 'dec' => 'restocked_quantity'],
            'shipped'    => ['inc' => 'shipped_quantity', 'dec' => 'in_production_quantity'],
            'missing'    => ['inc' => 'missing_quantity', 'dec' => null],
        ];

        $incField = $map[$type]['inc'];
        $decField = $map[$type]['dec'];

        $restockUpdates = [];
        $restockCreates = [];
        $historyInsert  = [];

        // ================================
        // 6️⃣ LOOP MEMORY ONLY
        // ================================
        foreach ($rows as $row) {

            $key = trim($row[0]);
            $qty = (int)$row[1];
            if ($qty <= 0) continue;

            $itemId = is_numeric($key) ? $key : $idMap[$key];

            // RESTOCK NOT EXIST & TYPE RESTOCKED → CREATE
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
                $action = 'created';
                $restockId = null;
            } else {

                $r = $restocks[$itemId];
                $before = $r->$incField;
                $after  = $before + $qty;

                $update = [
                    'id' => $r->id,
                    $incField => $after,
                    'updated_at' => $now,
                ];

                // decrement logic
                if ($decField) {
                    $update[$decField] = $r->$decField - $qty;
                    $r->$decField -= $qty;
                }

                $restockUpdates[] = $update;
                $r->$incField = $after; // update cache

                $action = 'updated';
                $restockId = $r->id;
            }

            // HISTORY
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
        // 7️⃣ EXECUTE DATABASE BATCH
        // ================================
        DB::transaction(function () use ($restockCreates, $restockUpdates, &$historyInsert, $date) {

            if ($restockCreates) Restock::insert($restockCreates);
            if ($restockUpdates) Restock::upsert($restockUpdates, ['id'], array_keys($restockUpdates[0]));

            // map restock_id for new
            $newRestocks = Restock::whereDate('date', $date)->get(['id', 'item_id'])->keyBy('item_id');
            foreach ($historyInsert as &$h) {
                if (!$h['restock_id']) {
                    $h['restock_id'] = $newRestocks[$h['item_id']]->id ?? null;
                }
            }

            if ($historyInsert) RestockHistory::insert($historyInsert);
        });
    }
}
