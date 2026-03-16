<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

/**
 * MAIN EXPORT (MULTI SHEET)
 */
class DestyTransactionExport implements WithMultipleSheets
{
    protected $data;


    public function __construct($data)
    {
        $this->data = $data;

    }

    public function sheets(): array
    {
        return [
            new DestyStockSheet($this->data),
          
        ];
    }
}

/**
 * SHEET 1 : UPDATE STOCK
 */
class DestyStockSheet implements FromCollection, WithColumnFormatting, WithColumnWidths, WithStyles, WithEvents
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection(): Collection
    {
        $rows = collect($this->data)->map(function ($item) {
            return [
                $item['nama_produk'] ?? '',
                $item['sku'] ?? '',
                '="' . $item['id_gudang'] . '"',
                $item['nama_gudang'] ?? '',
                '="' . $item['id_slot'] . '"',
                '',
                $item['qty'] ?? '',
                $item['total'] ?? '',
            ];
        });

        return collect([
            [
                'Nama Produk',
                'SKU Master',
                'ID Gudang',
                'Nama Gudang',
                'ID Slot',
                'Nama Slot',
                'Stok Fisik',
                'Unit Price',
            ],
            [
                null,
                null,
                null,
                null,
                null,
                null,
                'Tambah atau Kurangi Stok',
                null,
            ],
            [
                '(Opsional)',
                '(Wajib)',
                '(Wajib)',
                '(Opsional)',
                '(Opsional)',
                '(Opsional)',
                '(Wajib)',
                '(Required)',
            ],
            [
                'Hanya referensi',
                'Kode SKU produk',
                'ID gudang',
                'Hanya referensi',
                'ID slot produk',
                'Hanya referensi',
                'Masukkan + atau - stok',
                'Harga unit jika FIFO/AVG',
            ],
        ])->merge($rows);
    }

    public function columnFormats(): array
    {
        return [
            'C' => '@',
            'E' => '@',
            'H' => '"Rp" #,##0',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 22,
            'C' => 22,
            'D' => 25,
            'E' => 22,
            'F' => 25,
            'G' => 15,
            'H' => 18,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            2 => ['font' => ['italic' => true]],
            3 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                // freeze header
                $sheet->freezePane('A5');

                // wrap text
                $sheet->getStyle('A1:H4')->getAlignment()->setWrapText(true);

                // auto filter
                $sheet->setAutoFilter('A1:H1');
            },
        ];
    }
}


