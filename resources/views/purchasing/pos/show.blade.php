@extends('layouts.purchasing')

@section('title', 'Detail Purchase Order')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Detail Purchase Order</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('purchasing.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('purchasing.pos.index') }}">PO</a></li>
                    <li class="breadcrumb-item active">{{ $po->po_number }}</li>
                </ol>
            </nav>
        </div>
        <div>
            @if($po->status === 'pending')
            <a href="{{ route('purchasing.pos.edit', $po) }}" class="btn btn-warning btn-sm me-2">
                <i class="fas fa-edit me-1"></i>Edit
            </a>
            <button type="button" class="btn btn-danger btn-sm me-2" onclick="confirmCancel()">
                <i class="fas fa-times me-1"></i>Batalkan
            </button>
            @endif
            <a href="{{ route('purchasing.pos.print', $po) }}" class="btn btn-primary btn-sm" target="_blank">
                <i class="fas fa-print me-1"></i>Cetak PO
            </a>
        </div>
    </div>

    <div class="row">
        <!-- PO Details -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi PO</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td width="150">Nomor PO</td>
                                    <td>: {{ $po->po_number }}</td>
                                </tr>
                                <tr>
                                    <td>Tanggal Order</td>
                                    <td>: {{ $po->order_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td>Estimasi Penggunaan</td>
                                    <td>: {{ $po->estimated_usage_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>: @include('purchasing.pos._status_badge')</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td width="150">Supplier</td>
                                    <td>: {{ $po->supplier->name }}</td>
                                </tr>
                                <tr>
                                    <td>Nomor SPB</td>
                                    <td>: {{ $po->spb->spb_number }}</td>
                                </tr>
                                <tr>
                                    <td>Dibuat Oleh</td>
                                    <td>: {{ $po->creator->name }}</td>
                                </tr>
                                <tr>
                                    <td>Catatan</td>
                                    <td>: {{ $po->remarks ?: '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Daftar Item</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Item</th>
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                    <th>Harga Satuan</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($po->items as $item)
                                <tr>
                                    <td>{{ $item->item_name }}</td>
                                    <td class="text-center">{{ number_format($item->quantity) }}</td>
                                    <td class="text-center">{{ $item->unit }}</td>
                                    <td class="text-end">Rp {{ number_format($item->unit_price) }}</td>
                                    <td class="text-end">Rp {{ number_format($item->total_price) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">Total:</th>
                                    <th class="text-end">Rp {{ number_format($po->total_amount) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmComplete() {
    Swal.fire({
        title: 'Konfirmasi',
        text: 'Yakin ingin menandai PO ini selesai?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Selesai',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "{{ route('purchasing.pos.complete', $po) }}";
        }
    });
}

function confirmCancel() {
    Swal.fire({
        title: 'Konfirmasi',
        text: 'Yakin ingin membatalkan PO ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Batalkan',
        cancelButtonText: 'Tidak',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "{{ route('purchasing.pos.cancel', $po) }}";
        }
    });
}
</script>
@endpush