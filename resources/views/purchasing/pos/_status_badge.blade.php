<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>PO {{ $po->po_number }}</title>
    <style>
        @page {
            margin: 0.5cm 1cm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }

        .header {
            width: 100%;
            margin-bottom: 20px;
        }

        .header::after {
            content: '';
            display: table;
            clear: both;
        }

        .company-logo {
            float: left;
            width: 100px;
        }

        .company-info {
            float: left;
            margin-left: 20px;
        }

        .po-info {
            float: right;
            width: 40%;
        }

        .supplier-info {
            clear: both;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #000;
            padding: 5px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .notes {
            margin-top: 20px;
        }

        .signatures {
            margin-top: 50px;
            page-break-inside: avoid;
        }

        .signature-box {
            float: left;
            width: 23%;
            margin-right: 2%;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <div class="header">
        <table>
            <tr>
                <td width="200">
                    <img src="{{ public_path('images/logo.png') }}" height="60">
                </td>
                <td>
                    <strong>PT. HARMA CONTRACTOR INDONESIA</strong><br>
                    Jl. Margomulyo 44 Pergudangan Suri Mulia Blok HH-20<br>
                    Telp/Fax: 031-7490656-57/7490657
                </td>
                <td width="250">
                    <table style="border: 1px solid #000; padding: 5px;">
                        <tr>
                            <td colspan="2" class="text-center"><strong>PURCHASE ORDER</strong></td>
                        </tr>
                        <tr>
                            <td>No PO/FJ.BL</td>
                            <td>: {{ $po->po_number }}</td>
                        </tr>
                        <tr>
                            <td>Date Order</td>
                            <td>: {{ $po->order_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td>Delivery Time</td>
                            <td>: {{ $po->estimated_usage_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td>Payment</td>
                            <td>: 30 Hari</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <table class="supplier-info">
        <tr>
            <td width="80">Supplier</td>
            <td>: {{ $po->supplier->name }}</td>
        </tr>
        <tr>
            <td>PIC</td>
            <td>: </td>
        </tr>
        <tr>
            <td>Address</td>
            <td>: {{ $po->supplier->address ?? '-' }}</td>
        </tr>
        <tr>
            <td>Telp.</td>
            <td>: {{ $po->supplier->phone ?? '-' }}</td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Uraian Item</th>
                <th width="60">Qty</th>
                <th width="60">Unit</th>
                <th width="120">Harga</th>
                <th width="120">Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($po->items as $item)
            <tr>
                <td>{{ $item->item_name }}</td>
                <td class="text-center">{{ number_format($item->quantity, 0) }}</td>
                <td class="text-center">{{ $item->unit }}</td>
                <td class="text-right">{{ number_format($item->unit_price, 0) }}</td>
                <td class="text-right">{{ number_format($item->total_price, 0) }}</td>
            </tr>
            @endforeach
            @for($i = 0; $i < max(0, 5 - count($po->items)); $i++)
                <tr>
                    <td>&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endfor
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right">Sub Total</td>
                <td class="text-right">{{ number_format($po->total_amount, 0) }}</td>
            </tr>
            <tr>
                <td colspan="4" class="text-right">Diskon</td>
                <td class="text-right">0</td>
            </tr>
            <tr>
                <td colspan="4" class="text-right">PPN</td>
                <td class="text-right">0</td>
            </tr>
            <tr>
                <td colspan="4" class="text-right"><strong>TOTAL HARGA</strong></td>
                <td class="text-right"><strong>{{ number_format($po->total_amount, 0) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="notes">
        <div><strong>Alamat Pengiriman :</strong> Up. Bp.Yusril ( 081-749 6500 )</div>
        <div><strong>WORKSHOP</strong></div>
        <div>Jl. Margomulyo 44 Pergudangan Suri Mulia Blok HH - 20 Surabaya</div>
        <div style="margin-top: 10px">
            NPWP: 02.254.554.1-631.000<br>
            Non PKP<br>
            Non PKP
        </div>
    </div>

    <div class="signatures">
        <div class="signature-box">
            <div>Accepted and confirmed by supplier</div>
            <div class="signature-line"></div>
            <div>By :</div>
            <div style="font-style: italic; font-size: 10px;">Please return this copy duly signed by your representative
            </div>
        </div>
        <div class="signature-box">
            <div>Prepared by,</div>
            <div class="signature-line"></div>
            <div>Adminlokhm</div>
        </div>
        <div class="signature-box">
            <div>Checked by,</div>
            <div class="signature-line"></div>
            <div>Yuni</div>
        </div>
        <div class="signature-box">
            <div>Approval by,</div>
            <div class="signature-line"></div>
            <div>Mariani</div>
        </div>
    </div>
</body>

</html>