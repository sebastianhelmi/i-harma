<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 4px 6px;
        }

        th {
            background: #eee;
        }

        .row-task {
            background: #ffe066;
            font-weight: bold;
        }

        .row-subtask {
            background: #cce5ff;
            font-weight: bold;
        }

        .row-item {
            background: #fff;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .divisi-header {
            background: #f2f2f2;
            font-size: 16px;
            font-weight: bold;
            padding: 8px 0;
            margin-top: 24px;
        }
    </style>
</head>

<body>
    <div style="display: flex; align-items: center; margin-bottom: 10px;">
        <img src="{{ public_path('images/logo.png') }}" alt="Logo" style="height: 40px; margin-right: 16px;">
        <div style="flex:1;">
            <!-- Judul dan info proyek -->
            <table style="margin-bottom: 0;">
                <tr>
                    <td><b>Project</b></td>
                    <td>{{ $project->name }}</td>
                </tr>
                <tr>
                    <td><b>Client</b></td>
                    <td>{{ $project->client_name ?? '-' }}</td>
                </tr>
                <tr>
                    <td><b>Location</b></td>
                    <td>{{ $project->project_location ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>
    @foreach ($divisions as $divisi => $summary)
        <div class="divisi-header">Summary - Divisi: {{ $divisi }}</div>
        <table style="margin-bottom: 18px;">
            <thead>
                <tr>
                    <th class="text-center" width="30">NO</th>
                    <th>DESCRIPTION</th>
                    <th class="text-center" width="40">QTY</th>
                    <th class="text-center" width="40">UNIT</th>
                    <th class="text-right" width="70">UNIT PRICE</th>
                    <th class="text-right" width="80">TOTAL COST</th>
                    <th>REMARK</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($summary as $row)
                    <tr class="row-{{ $row['type'] }}">
                        <td class="text-center">{{ $row['no'] }}</td>
                        <td>{{ $row['description'] }}</td>
                        <td class="text-center">{{ $row['qty'] }}</td>
                        <td class="text-center">{{ $row['unit'] }}</td>
                        <td class="text-right">
                            @if ($row['unit_price'] !== '')
                                {{ number_format($row['unit_price'], 0, ',', '.') }}
                            @endif
                        </td>
                        <td class="text-right">
                            @if ($row['total_cost'] !== '')
                                {{ number_format($row['total_cost'], 0, ',', '.') }}
                            @endif
                        </td>
                        <td>{{ $row['remark'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>

</html>
