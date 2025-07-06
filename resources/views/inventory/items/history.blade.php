@extends('layouts.inventory')

@section('title', 'Riwayat Transaksi')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Riwayat Transaksi: {{ $item->item_name }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('inventory.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('inventory.items.index') }}">Inventory</a></li>
                        <li class="breadcrumb-item active">Riwayat</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('inventory.items.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Tipe</th>
                                <th>Jumlah</th>
                                <th>Stok Akhir</th>
                                <th>Oleh</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->transaction_date->format('d M Y H:i') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $transaction->getStatusBadgeClass() }}">
                                            {{ $transaction->transaction_type_text }}
                                        </span>
                                    </td>
                                    <td>{{ $transaction->quantity }} {{ $item->unit }}</td>
                                    <td>{{ $transaction->stock_after_transaction }} {{ $item->unit }}</td>
                                    <td>{{ $transaction->handler->name ?? 'N/A' }}</td>
                                    <td>{{ $transaction->remarks }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">Tidak ada riwayat transaksi untuk item ini.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
