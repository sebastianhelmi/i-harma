@extends('layouts.purchasing')

@section('title', 'Daftar Purchase Order')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Daftar Purchase Order</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('purchasing.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Purchase Orders</li>
                </ol>
            </nav>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Search & Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('purchasing.pos.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari PO Number/Supplier..."
                            value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" @selected(request('status')==$value)>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @if(request('search') || request('status'))
                <div class="col-md-2">
                    <a href="{{ route('purchasing.pos.index') }}" class="btn btn-light">
                        <i class="fas fa-times me-2"></i>Reset
                    </a>
                </div>
                @endif
            </form>
        </div>
    </div>

    <!-- PO Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No. PO</th>
                            <th>No. SPB</th>
                            <th>Supplier</th>
                            <th>Tanggal Order</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Dibuat Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pos as $po)
                        <tr>
                            <td>{{ $po->po_number }}</td>
                            <td>
                                <a href="{{ route('purchasing.spbs.show', $po->spb_id) }}">
                                    {{ $po->spb->spb_number }}
                                </a>
                            </td>
                            <td>{{ $po->supplier->name }}</td>
                            <td>{{ $po->order_date->format('d/m/Y') }}</td>
                            <td>{{ number_format($po->total_amount, 0, ',', '.') }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $po->status === 'pending' ? 'warning' : ($po->status === 'completed' ? 'success' : 'danger') }}">
                                    {{ $statuses[$po->status] }}
                                </span>
                            </td>
                            <td>{{ $po->creator->name }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('purchasing.pos.show', $po) }}" class="btn btn-sm btn-info"
                                        title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('purchasing.pos.print', $po) }}" class="btn btn-sm btn-primary"
                                        title="Cetak" target="_blank">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    @if($po->status === 'pending')
                                    <a href="{{ route('purchasing.pos.edit', $po) }}" class="btn btn-warning btn-sm"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" title="Batalkan"
                                        onclick="confirmCancel('{{ $po->id }}')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                Tidak ada data Purchase Order
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $pos->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modals for complete and cancel actions -->
<form id="complete-form" action="" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

<form id="cancel-form" action="" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const tooltips = document.querySelectorAll('[title]');
    tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));
    });
    function confirmComplete(id) {
    if (confirm('Apakah Anda yakin ingin menyelesaikan PO ini?')) {
        const form = document.getElementById('complete-form');
        form.action = `{{ url('purchasing/pos') }}/${id}/complete`;
        form.submit();
    }
}

function confirmCancel(id) {
    if (confirm('Apakah Anda yakin ingin membatalkan PO ini?')) {
        const form = document.getElementById('cancel-form');
        form.action = `{{ url('purchasing/pos') }}/${id}/cancel`;
        form.submit();
    }
}
</script>
@endpush
@endsection