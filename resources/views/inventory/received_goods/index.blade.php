@extends('layouts.inventory')

@section('title', 'Penerimaan Barang')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Penerimaan Barang</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <!-- Search Form -->
            <form class="mb-4">
                <div class="input-group">
                    <input type="text" class="form-control"
                           placeholder="Cari nomor PO, supplier, atau proyek..."
                           name="search" value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <!-- PO Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Nomor PO</th>
                            <th>Supplier</th>
                            <th>Proyek</th>
                            <th>Tanggal Order</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pos as $po)
                        <tr>
                            <td>{{ $po->po_number }}</td>
                            <td>{{ $po->supplier->name }}</td>
                            <td>{{ $po->spb->project->name }}</td>
                            <td>{{ $po->order_date->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $po->status === 'pending' ? 'warning' : 'success' }}">
                                    {{ $po->status === 'pending' ? 'Menunggu Penerimaan' : 'Selesai' }}
                                </span>
                            </td>
                            <td>Rp {{ number_format($po->total_amount, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('inventory.received-goods.create', $po) }}"
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus me-1"></i>Terima Barang
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                Tidak ada PO yang perlu diproses
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
@endsection
