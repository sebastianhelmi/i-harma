@extends('layouts.purchasing')

@section('title', 'Detail SPB')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Detail SPB</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('purchasing.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('purchasing.spbs.index') }}">SPB</a></li>
                    <li class="breadcrumb-item active">{{ $spb->spb_number }}</li>
                </ol>
            </nav>
        </div>
        <div>
            @if($spb->status === 'approved' && $spb->status_po === 'pending')
            <a href="{{ route('purchasing.pos.create', $spb->id) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i>Buat PO
            </a>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- SPB Info -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi SPB</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td width="150">Nomor SPB</td>
                                    <td>: {{ $spb->spb_number }}</td>
                                </tr>
                                <tr>
                                    <td>Tanggal SPB</td>
                                    <td>: {{ $spb->spb_date }}</td>
                                </tr>
                                <tr>
                                    <td width="150">Proyek</td>
                                    <td>: {{ $spb->project?->name ?? 'Data tidak tersedia' }}</td>
                                </tr>
                                <tr>
                                    <td>Task</td>
                                    <td>: {{ $spb->task?->name ?? 'Data tidak tersedia' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td>Diminta Oleh</td>
                                    <td>: {{ $spb->requester?->name ?? 'Data tidak tersedia' }}</td>
                                </tr>
                                <tr>
                                    <td>Kategori</td>
                                    <td>: {{ $spb->category_entry === 'site' ? 'Site' : 'Workshop' }}</td>
                                </tr>
                                <tr>
                                    <td>Kategori Item</td>
                                    <td>: {{ $spb->itemCategory?->name ?? 'Data tidak tersedia' }}</td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>
                                        : <span class="badge bg-success">Approved</span>
                                        @if($spb->status_po === 'pending')
                                        <span class="badge bg-warning">Menunggu PO</span>
                                        @elseif($spb->status_po === 'ordered')
                                        <span class="badge bg-info">Sudah PO</span>
                                        @else
                                        <span class="badge bg-secondary">Tidak Perlu PO</span>
                                        @endif
                                    </td>
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
                        @if($spb->category_entry === 'site')
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Item</th>
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($spb->siteItems as $item)
                                <tr>
                                    <td>{{ $item->item_name }}</td>
                                    <td class="text-center">{{ number_format($item->quantity) }}</td>
                                    <td class="text-center">{{ $item->unit }}</td>
                                    <td>{{ $item->remarks ?: '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Deskripsi Item</th>
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($spb->workshopItems as $item)
                                <tr>
                                    <td>{{ $item->explanation_items }}</td>
                                    <td class="text-center">{{ number_format($item->quantity) }}</td>
                                    <td class="text-center">{{ $item->unit }}</td>
                                    <td>{{ $item->remarks ?: '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection