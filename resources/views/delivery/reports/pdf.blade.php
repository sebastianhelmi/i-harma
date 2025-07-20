<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Pengiriman Barang</title>
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
        <h2 class="text-center" style="flex:1;">Laporan Pengiriman Barang</h2>
    </div>
    <p>Periode: <b>{{ $startDate }} s/d {{ $endDate }}</b></p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Project</th>
                <th>No Surat Jalan</th>
                <th>Ekspedisi</th>
                <th>Tgl Berangkat</th>
                <th>Nama Item</th>
                <th>Jumlah</th>
                <th>Satuan</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @php $row = 1; @endphp
            @forelse($deliveryNotes as $note)
                @foreach ($note->items as $item)
                    <tr>
                        <td class="text-center">{{ $row++ }}</td>
                        <td>{{ $note->deliveryPlan->project->name ?? '-' }}</td>
                        <td>{{ $note->delivery_note_number }}</td>
                        <td>{{ $note->expedition ?? '-' }}</td>
                        <td>{{ $note->departure_date ? \Carbon\Carbon::parse($note->departure_date)->format('d M Y') : '-' }}
                        </td>
                        <td>{{ $item->item_name }}</td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td>{{ $item->unit }}</td>
                        <td>{{ $item->item_notes ?? '-' }}</td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data pengiriman</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
