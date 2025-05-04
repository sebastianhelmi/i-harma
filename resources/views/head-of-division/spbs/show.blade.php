@extends('layouts.head-of-division')

@section('title', 'Detail SPB')

@section('content')
<div class="container-fluid">
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
        <div>
            <a href="{{ route('head-of-division.spbs.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi SPB</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="30%">Nomor SPB</td>
                            <td>: {{ $spb->spb_number }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal</td>
                            <td>: {{ $spb->spb_date->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td>Proyek</td>
                            <td>: {{ $spb->project->name }}</td>
                        </tr>
                        <tr>
                            <td>Tugas</td>
                            <td>: {{ $spb->task->name }}</td>
                        </tr>
                        <tr>
                            <td>Kategori Item</td>
                            <td>: {{ $spb->itemCategory->name }}</td>
                        </tr>
                        <tr>
                            <td>Jenis Entry</td>
                            <td>: {{ $spb->category_entry === 'site' ? 'Site' : 'Workshop' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Status & Approval</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="30%">Status</td>
                            <td>
                                <span class="badge bg-{{ $spb->getStatusBadgeClass() }}">
                                    {{ ucfirst($spb->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Status PO</td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ ucfirst($spb->status_po) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Diminta Oleh</td>
                            <td>: {{ $spb->requester->name }}</td>
                        </tr>
                        @if($spb->approved_by)
                        <tr>
                            <td>Disetujui Oleh</td>
                            <td>: {{ $spb->approver->name }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal Approval</td>
                            <td>: {{ $spb->approved_at->format('d M Y H:i') }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Detail Item</h5>
        </div>
        <div class="card-body">
            @if($spb->category_entry === 'site')
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Item</th>
                                <th>Jumlah</th>
                                <th>Satuan</th>
                                <th>Keterangan</th>
                                <th>Dokumen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($spb->siteItems as $item)
                            <tr>
                                <td>{{ $item->item_name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->unit }}</td>
                                <td>{{ $item->information }}</td>
                                <td>
                                    @if($item->document_file)
                                        @foreach($item->document_file as $file)
                                        <a href="{{ Storage::url($file) }}"
                                           target="_blank"
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-file-alt"></i>
                                        </a>
                                        @endforeach
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Penjelasan Item</th>
                                <th>Jumlah</th>
                                <th>Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($spb->workshopItems as $item)
                            <tr>
                                <td>{{ $item->explanation_items }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->unit }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    @if($spb->remarks)
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Catatan</h5>
        </div>
        <div class="card-body">
            {{ $spb->remarks }}
        </div>
    </div>
    @endif
</div>
@endsection
