@extends('layouts.inventory')

@section('title', 'Barang Keluar')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">Daftar Barang Keluar</h5>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-outline-primary" onclick="exportData()">
                        <i class="fas fa-file-export me-1"></i>Export
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <form id="filterForm" class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label">Periode</label>
                    <div class="input-group">
                        <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}">
                        <span class="input-group-text">s/d</span>
                        <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Barang</label>
                    <select class="form-select" name="inventory_id">
                        <option value="">Semua Barang</option>
                        @foreach($inventoryItems as $item)
                        <option value="{{ $item['id'] }}" {{ request('inventory_id')==$item['id'] ? 'selected' : '' }}>
                            {{ $item['name'] }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">No. PO</label>
                    <input type="text" class="form-control" name="po_number" value="{{ request('po_number') }}"
                        placeholder="Cari No. PO...">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="resetFilters()">
                        <i class="fas fa-undo me-1"></i>Reset
                    </button>
                </div>
            </form>

            <!-- Summary -->
            <div class="alert alert-info mb-4">
                <div class="row align-items-center">
                    <div class="col">
                        <i class="fas fa-info-circle me-2"></i>
                        Total barang keluar: <strong>{{ number_format($totalOutgoing) }}</strong> item
                    </div>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>No. PO</th>
                            <th>No. SPB</th>
                            <th>Ditangani Oleh</th>
                            <th>Keterangan</th>
                            <th width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                            <td>
                                {{ $transaction->inventory->item_name }}
                                <small class="text-muted d-block">
                                    {{ $transaction->inventory->unit }}
                                </small>
                            </td>
                            <td class="text-center">{{ number_format($transaction->quantity) }}</td>
                            <td>{{ $transaction->po->po_number ?? '-' }}</td>
                            <td>{{ $transaction->po->spb->spb_number ?? '-' }}</td>
                            <td>{{ $transaction->handler->name }}</td>
                            <td>{{ $transaction->remarks }}</td>
                            <td>
                                <a href="{{ route('inventory.outgoing.show', $transaction) }}"
                                    class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-3">
                                <i class="fas fa-info-circle me-2"></i>Tidak ada data
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $transactions->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    function resetFilters() {
    const form = document.getElementById('filterForm');
    form.reset();
    form.submit();
}

function exportData() {
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);

    window.location.href = `{{ route('inventory.outgoing.export') }}?${params.toString()}`;
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('filterForm');

    // Auto submit on inventory selection
    form.querySelector('[name="inventory_id"]').addEventListener('change', function() {
        form.submit();
    });
});
</script>
@endpush