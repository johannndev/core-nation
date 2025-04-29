<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->invoice }}</title>
    <style>

        @page {
            size: A4 portrait;
            margin: 0;
        }

        html, body {
            margin: 0;
            padding: 20mm; /* ✅ Padding di semua sisi */
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        
        
        .header, .footer {
            width: 100%;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
        }
        .section {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: avoid; /* <-- penting supaya tidak potong tabel */
        }
        th, td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: left;
            word-wrap: break-word;
        }

        tr, td, th {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        .text-right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }
        .totals {
            width: 100%;
            margin-top: 20px;
        }
        .totals td {
            padding: 5px;
        }
        .totals .label {
            text-align: right;
            font-weight: bold;
        }
        .footer-note {
            margin-top: 30px;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <div class="">
            <table style="border: none;">
                <tr>
                    <td style="border: none; white-space: nowrap;  width: 30px; ">
                        <img
                            src="{{asset('img/logo.png')}}"
                            style="width: auto;"
                            alt="Flowbite Logo"
                        />
                    </td>
                    <td style="border: none;">
                        <h2 style="margin: 0;">CoreNation Active</h2>
                    </td>
                </tr>
            </table>
        </div>
        <h4>Invoice {{$typeInvoice}}</h4>
        <p><strong>Invoice #: </strong>{{ $invoice->invoice }}</p>
        <p><strong>Date: </strong>{{ \Carbon\Carbon::parse($invoice->date)->format('d M Y') }}</p>
    </div>

    {{-- From & To --}}
    <div class="section">
        <table style="border: none;">
            <tr>
                <td style="border: none;">
                    <strong>From:</strong><br>
                    {{$invoice->sender->name}}<br>
                    {{-- {{ $invoice->from_address }}<br>
                    {{ $invoice->from_phone }} --}}
                </td>
                <td style="border: none;">
                    <strong>To:</strong><br>
                    {{$invoice->receiver->name}}<<br>
                    {{-- {{ $invoice->to_address }}<br>
                    {{ $invoice->to_phone }} --}}
                </td>
            </tr>
        </table>
    </div>

    {{-- Product Table --}}
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Code</th>
                <th>Item Name</th>
                <th>Description</th>
                <th class="text-right">Quantity</th>
                <th class="text-right">Price</th>
                <th class="text-right">Discount</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->transactionDetail as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->item->code }}</td>
                    <td>{{ $item->item->getItemName() }}</td>
                    <td>{{ $item->item->group? $item->item->group->description : $item->item->description }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp{{ number_format($item->discount, 0, ',', '.') }}</td>
                    <td class="text-right">Rp{{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totals --}}
    <table class="totals">
        <tr>
            <td class="label">Total Items:</td>
            <td class="text-right">{{$invoice->total_items}}</td>
        </tr>
        <tr>
            <td class="label">Total Discount:</td>
            <td class="text-right">{{$invoice->discount}}%</td>
        </tr>
        <tr>
            <td class="label">Adjustment:</td>
            <td class="text-right">Rp{{number_format($invoice->adjustment,2)}}</td>
        </tr>
        <tr>
            <td class="label">Total Before Discount:</td>
            <td class="text-right">Rp{{number_format($invoice->real_total,2)}}</td>
        </tr>
        <tr>
            <td class="label"><strong>Grand Total:</strong></td>
            <td class="text-right"><strong>Rp{{number_format($invoice->total,2)}}</strong></td>
        </tr>
    </table>

    {{-- Footer --}}
    <div class="footer-note">
        Terima kasih atas kepercayaan Anda. Silakan hubungi kami jika ada pertanyaan mengenai invoice ini.
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Ambil tinggi konten di pixel
            const contentHeightPx = document.body.scrollHeight;
    
            // Konversi pixel ke points (1pt = 1.333px)
            const contentHeightPt = contentHeightPx * 0.75;
    
            // Kirim tinggi ke PHP lewat hidden element
            const heightInput = document.createElement('input');
            heightInput.type = 'hidden';
            heightInput.name = 'docHeight';
            heightInput.value = Math.ceil(contentHeightPt);
    
            document.body.appendChild(heightInput);
        });
    </script>

</body>
</html>
