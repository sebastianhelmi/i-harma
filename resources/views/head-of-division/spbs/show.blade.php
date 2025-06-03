@extends('layouts.head-of-division')

@section('title', 'Detail SPB')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Detail SPB</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('head-of-division.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('head-of-division.spbs.index') }}">SPB</a></li>
                    <li class="breadcrumb-item active">{{ $spb->spb_number }}</li>
                </ol>
            </nav>
        </div>

        <div class="d-flex gap-2">
            @if($canTakeItems)
                <button type="button"
                        class="btn btn-success"
                        onclick="confirmTakeItems('{{ $spb->id }}', '{{ $spb->spb_number }}')">
                    <i class="fas fa-hand-holding me-2"></i>Ambil Barang
                </button>
            @endif
            <a href="{{ route('head-of-division.spbs.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Status Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Status SPB</h6>
                    <span class="badge bg-{{ $spb->getStatusBadgeClass() }} fs-6">
                        {{ match($spb->status) {
                            'pending' => 'Pending',
                            'approved' => 'Disetujui',
                            'rejected' => 'Ditolak',
                            'completed' => 'Selesai',
                        } }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Status PO</h6>
                    <span class="badge bg-{{ $spb->po?->status === 'completed' ? 'success' : 'warning' }} fs-6">
                        {{ $spb->po ? ($spb->po->status === 'completed' ? 'PO Selesai' : 'PO Dalam Proses') : 'Belum Ada PO' }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Status Barang</h6>
                    <span class="badge bg-{{ $spb->status === 'completed' ? 'success' : 'warning' }} fs-6">
                        {{ $spb->status === 'completed' ? 'Sudah Diambil' : 'Belum Diambil' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- SPB Info -->
    <div class="row">
        <div class="col-md-8">
            <!-- Items Table -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Daftar Barang</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($spb->category_entry === 'site')
                                    @foreach($spb->siteItems as $item)
                                    <tr>
                                        <td>{{ $item->item_name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->unit }}</td>
                                        <td>
                                            @php
                                                $inventory = App\Models\Inventory::where('item_name', $item->item_name)
                                                    ->where('unit', $item->unit)
                                                    ->first();
                                                $collected = $collectedItems[$inventory->id ?? 0] ?? collect();
                                                $isCollected = $collected->sum('quantity') >= $item->quantity;
                                            @endphp
                                            <span class="badge bg-{{ $isCollected ? 'success' : 'warning' }}">
                                                {{ $isCollected ? 'Sudah Diambil' : 'Belum Diambil' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    @foreach($spb->workshopItems as $item)
                                    <tr>
                                        <td>{{ $item->explanation_items }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->unit }}</td>
                                        <td>
                                            @php
                                                $inventory = App\Models\Inventory::where('item_name', $item->explanation_items)
                                                    ->where('unit', $item->unit)
                                                    ->first();
                                                $collected = $collectedItems[$inventory->id ?? 0] ?? collect();
                                                $isCollected = $collected->sum('quantity') >= $item->quantity;
                                            @endphp
                                            <span class="badge bg-{{ $isCollected ? 'success' : 'warning' }}">
                                                {{ $isCollected ? 'Sudah Diambil' : 'Belum Diambil' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Info Cards -->
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="text-muted mb-3">Informasi SPB</h6>
                    <dl class="row mb-0">
                        <dt class="col-sm-4">No. SPB</dt>
                        <dd class="col-sm-8">{{ $spb->spb_number }}</dd>

                        <dt class="col-sm-4">Tanggal</dt>
                        <dd class="col-sm-8">{{ $spb->spb_date->format('d/m/Y') }}</dd>

                        <dt class="col-sm-4">Proyek</dt>
                        <dd class="col-sm-8">{{ $spb->project->name }}</dd>

                        <dt class="col-sm-4">Tugas</dt>
                        <dd class="col-sm-8">{{ $spb->task->name }}</dd>

                        <dt class="col-sm-4">Kategori</dt>
                        <dd class="col-sm-8">{{ $spb->itemCategory->name }}</dd>

                        <dt class="col-sm-4">Jenis</dt>
                        <dd class="col-sm-8">
                            <span class="badge bg-info">
                                {{ $spb->category_entry === 'site' ? 'Site' : 'Workshop' }}
                            </span>
                        </dd>
                    </dl>
                </div>
            </div>

            @if($spb->po)
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="text-muted mb-3">Informasi PO</h6>
                    <dl class="row mb-0">
                        <dt class="col-sm-4">No. PO</dt>
                        <dd class="col-sm-8">{{ $spb->po->po_number }}</dd>

                        <dt class="col-sm-4">Supplier</dt>
                        <dd class="col-sm-8">{{ $spb->po->supplier->name }}</dd>

                        <dt class="col-sm-4">Total</dt>
                        <dd class="col-sm-8">Rp {{ number_format($spb->po->total_amount, 0, ',', '.') }}</dd>
                    </dl>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@include('head-of-division.spbs._take_items_modal')

@push('scripts')
<script>
// ...existing scripts for take items modal...
</script>
@endpush
@endsection
