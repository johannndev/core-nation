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
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * DATA ROW
     */
    public function collection(): Collection
    {

        $dataList = collect($this->data);
        $rows = $dataList->map(function ($item) {
            return [
                $item['nama_produk'] ?? '',
                $item['sku'] ?? '',
                '="' . $item['id_gudang'] . '"',
                $item['nama_gudang'],
                '="' . $item['id_slot'] . '"',
                '',
                $item['qty'],
                $item['total'],
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
