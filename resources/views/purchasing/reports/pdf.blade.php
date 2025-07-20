<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan PO</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px 8px;
        }

        th {
            background: #f2f2f2;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <div style="display: flex; align-items: center; margin-bottom: 10px;">
        <img src="{{ public_path('images/logo.png') }}" alt="Logo" style="height: 40px; margin-right: 16px;">
        <h2 class="text-center" style="flex:1;">Laporan Purchase Order (PO)</h2>
    </div>
    <p>Periode: <b>{{ $startDate }} s/d {{ $endDate }}</b></p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No PO</th>
                <th>Tanggal</th>
                <th>Proyek</th>
                <th>Supplier</th>
                <th>Status</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pos as $i => $po)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $po->po_number }}</td>
                    <td>{{ $po->order_date ? \Carbon\Carbon::parse($po->order_date)->format('d M Y') : '-' }}</td>
                    <td>{{ $po->spb->project->name ?? '-' }}</td>
                    <td>{{ $po->supplier->name ?? ($po->company_name ?? '-') }}</td>
                    <td class="text-center">{{ ucfirst($po->status) }}</td>
                    <td class="text-right">{{ number_format($po->total_amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data PO</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
