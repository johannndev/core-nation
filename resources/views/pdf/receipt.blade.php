<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $data->invoice }}</title>
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
        <p><strong>Invoice : </strong>{{ $data->invoice }}</p>
        <p><strong>Date: </strong>{{ \Carbon\Carbon::parse($data->date)->format('d M Y') }}</p>
    </div>

    {{-- From & To --}}
    <div class="section">
        <table style="border: none;">
            <tr>
                <td style="border: none;">
                    {{$data->sender->name}}<br>
                    {{-- {{ $data->from_address }}<br>
                    {{ $data->from_phone }} --}}
                </td>
            </tr>
        </table>
    </div>

    {{-- Product Table --}}
    <table>
        <thead>
            <tr>
                <th>Item Name</th>
                <th class="text-right">Quantity</th>
                <th class="text-right">Amt</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data->transactionDetail as $index => $item)
                <tr>
                    <td>{{ $item->item->getItemName() }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totals --}}
    <table class="totals">
        <tr>
            <td class="label">Total Items:</td>
            <td class="text-right">{{$data->total_items}}</td>
        </tr>
        <tr>
            <td class="label">Total Before Discount:</td>
            <td class="text-right">Rp{{number_format($data->real_total,2)}}</td>
        </tr>
        <tr>
            <td class="label">Discount:</td>
            <td class="text-right">(Rp{{number_format($data->real_total - $data->total,2)}})</td>
        </tr>
        <tr>
            <td class="label"><strong>Grand Total:</strong></td>
            <td class="text-right"><strong>Rp{{number_format($data->total,2)}}</strong></td>
        </tr>
    </table>

    {{-- Footer --}}
    <div class="footer-note">
        @corenationactive 082244226656
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
