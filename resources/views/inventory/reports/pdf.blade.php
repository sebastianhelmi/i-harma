<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi Inventory</title>
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
        <h2 class="text-center" style="flex:1;">Laporan Transaksi Inventory</h2>
    </div>
    <p>Periode: <b>{{ $startDate }} s/d {{ $endDate }}</b></p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Barang</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Penanggung Jawab</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $i => $trx)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $trx->transaction_date ? \Carbon\Carbon::parse($trx->transaction_date)->format('d M Y') : '-' }}
                    </td>
                    <td>{{ $trx->inventory->item_name ?? '-' }}</td>
                    <td class="text-center">{{ $trx->getTransactionTypeTextAttribute() }}</td>
                    <td class="text-right">{{ $trx->quantity }}</td>
                    <td>{{ $trx->handler->name ?? '-' }}</td>
                    <td>{{ $trx->remarks ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data transaksi</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
