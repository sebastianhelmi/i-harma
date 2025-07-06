<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Surat Jalan {{ $note->delivery_note_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            margin: 0;
            padding: 15px;
        }

        .header {
            margin-bottom: 20px;
        }

        .company-logo {
            float: left;
            width: 80px;
            margin-right: 15px;
        }

        .company-info {
            float: left;
        }

        .company-name {
            font-weight: bold;
            font-size: 14pt;
            margin-left: 80px;
        }

        .document-title {
            text-align: right;
            font-weight: bold;
            border: 1px solid black;
            padding: 5px;
            float: right;
            width: 200px;
        }

        .clear {
            clear: both;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
            font-size: 10pt;
        }

        th {
            background-color: #f0f0f0;
        }

        .project-info td {
            padding: 3px;
        }

        .signatures {
            margin-top: 30px;
            width: 100%;
        }

        .signatures td {
            text-align: center;
            padding: 5px;
            width: 20%;
        }

        .signature-line {
            margin-top: 60px;
            border-top: 1px solid black;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        .footer-note {
            font-size: 8pt;
            font-style: italic;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="company-logo">
            <img src="{{ public_path('images/logo.png') }}" height="60">
        </div>
        <div class="company-info">
            <div class="company-name">PT. HARMA CONTRACTOR INDONESIA</div>
        </div>
        <div class="document-title">
            DRAFT PACKING LIST<br>
            NO : {{ $note->delivery_note_number }}
        </div>
    </div>
    <div class="clear"></div>

    <table class="project-info">
        <tr>
            <td width="15%">SITE PROJECT</td>
            <td width="35%">: {{ $note->deliveryPlan->destination }}</td>
            <td width="15%">NO.POLISI</td>
            <td width="35%">: {{ $note->vehicle_license_plate }}</td>
        </tr>
        <tr>
            <td>ALAMAT SITE</td>
            <td>: {{ $note->deliveryPlan->destination_address ?? '-' }}</td>
            <td>ETD</td>
            <td>: {{ $note->departure_date->format('d/m/Y') }}</td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th>PACKING</th>
                <th>DRAWING NO</th>
                <th>ERECTION CODE/<br>NAMA ITEM</th>
                <th>MATERIALS</th>
                <th>LENGTH<br>(mm)</th>
                <th>QTY</th>
                <th>UNIT</th>
                <th>WEIGHT(kg)</th>
                <th>VOLUME(m3)</th>
                <th>REMARK</th>
            </tr>
        </thead>
        <tbody>
            @foreach($note->deliveryPlan->draftItems as $item)
            <tr>
                <td>{{ $item->packing_type ?? 'LOOSE' }}</td>
                <td>{{ $item->drawing_number ?? '-' }}</td>
                <td>{{ $item->item_name }}</td>
                <td>{{ $item->material ?? '-' }}</td>
                <td>{{ $item->length ?? '-' }}</td>
                <td style="text-align: center">{{ $item->quantity }}</td>
                <td>{{ $item->unit }}</td>
                <td style="text-align: right">{{ $item->weight ?? '-' }}</td>
                <td style="text-align: right">{{ $item->volume ?? '-' }}</td>
                <td>{{ $item->item_notes }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" style="text-align: right">WEIGHT TOTAL (packing):</td>
                <td style="text-align: right">{{ $note->deliveryPlan->total_weight ?? '-' }}</td>
                <td style="text-align: right">{{ $note->deliveryPlan->total_volume ?? '-' }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <table class="signatures" style="border: none">
        <tr>
            <td>Prepared By,</td>
            <td>Approved By,</td>
            <td>Transportation By,</td>
            <td>Received By,</td>
        </tr>
        <tr>
            <td>
                <div class="signature-line"></div>
                {{ $note->creator->name }}
            </td>
            <td>
                <div class="signature-line"></div>
                Supervisor
            </td>
            <td>
                <div class="signature-line"></div>
                {{ $note->expedition }}
            </td>
            <td>
                <div class="signature-line"></div>
                Site
            </td>
        </tr>
        <tr>
            <td>DATE:</td>
            <td>DATE:</td>
            <td>DATE:</td>
            <td>DATE:</td>
        </tr>
    </table>

    <div class="footer-note">
        * Note : Hari & Jam kerja wajib Corporate maka barang diterima dalam jumlah yang benar dan kondisi baik
    </div>
</body>

</html>