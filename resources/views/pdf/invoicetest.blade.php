<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice </title>
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
        <div style="display: flex; align-items: center;">
            <img
                src="{{asset('img/logo.png')}}"
                style="height: 40px; width: auto; display: block;"
                alt="CoreNation Logo"
            />
            <h2 style="margin: 0 0 0 5px; padding: 0;">CoreNation </h2>
        </div>
      
    </div>

    {{-- From & To --}}
    
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
           
        </tbody>
    </table>

    {{-- Totals --}}
   
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
