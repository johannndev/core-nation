<?php

namespace App\Exports;

use App\Models\TransactionDetail;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use App\Exports\StringValueBinder;

class DestyTransactionExport extends StringValueBinder implements FromCollection, WithHeadings, WithColumnFormatting
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * DATA ROW
     */
    public function collection(): Collection
    {
        $dataList = TransactionDetail::where('transaction_id', $this->id)
            ->with('item', 'destySender.warehouse')
            ->orderBy('date', 'desc')
            ->orderBy('transaction_id', 'desc')
            ->get();

        $rows = $dataList->map(function ($item) {

            if ($item->transaction_type == 2 || $item->transaction_type == 17) {
                $qty = '-' . $item->quantity;
                $idgudang   = (string) optional($item->destySender)->gudang_id;
                $namagudang = optional(optional($item->destySender)->warehouse)->name;
                $idslot     = (string) optional($item->destySender)->slot_id;
            } else {
                $qty = $item->quantity;
                $idgudang = '';
                $namagudang = '';
                $idslot = '';
            }

            return [
                $item->item->name ?? '',
                $item->item->code ?? '',
                '="' . $idgudang . '"',
                $namagudang,
                '="' . $idslot . '"',
                '',
                $qty,
                $item->total,
            ];
        });

        // 🔥 3 ROW KOSONG → DATA MULAI DI ROW 5
        return collect([
            ['', '', '', '', '', '', '', ''], // row 2
            ['', '', '', '', '', '', '', ''], // row 3
            ['', '', '', '', '', '', '', ''], // row 4
        ])->merge($rows);
    }

    /**
     * HEADER
     */
    public function headings(): array
    {
        return [
            'Nama Produk',
            'SKU Master',
            'ID Gudang',
            'Nama Gudang',
            'ID Slot',
            'Nama Slot',
            'Stok Fisik',
            'Unit Price',
        ];
    }

    /**
     * FORMAT KOLOM EXCEL
     */
    public function columnFormats(): array
    {
        return [
            'C' => '@',
            'E' => '@',
            'H' => '#,##0',
        ];
    }
}
