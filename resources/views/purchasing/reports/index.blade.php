@extends('layouts.purchasing')

@section('title', 'Laporan Pembelian')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Laporan Pembelian</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('purchasing.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Laporan</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Filter Laporan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('purchasing.reports.index') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" id="start_date" name="start_date" class="form-control"
                            value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">Tanggal Selesai</label>
                        <input type="date" id="end_date" name="end_date" class="form-control"
                            value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Hasil Laporan</h5>
                @if (request('start_date') && request('end_date'))
                    <a href="{{ route('purchasing.reports.export-pdf', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                        target="_blank" class="btn btn-success">
                        <i class="fas fa-file-pdf me-1"></i> Export PDF
                    </a>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>No. PO</th>
                                <th>Tanggal PO</th>
                                <th>Proyek</th>
                                <th>Supplier</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (request()->has('start_date'))
                                @forelse($pos as $po)
                                    <tr>
                                        <td>
                                            <a href="{{ route('purchasing.pos.show', $po) }}">{{ $po->po_number }}</a>
                                        </td>
                                        <td>{{ $po->order_date->format('d/m/Y') }}</td>
                                        <td>{{ $po->spb->project->name }}</td>
                                        <td>{{ $po->supplier->name ?? ($po->company_name ?? '-') }}</td>
                                        <td>Rp {{ number_format($po->total_amount, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $po->getStatusBadgeClass() }}">
                                                {{ ucfirst($po->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-times-circle fa-2x mb-3"></i>
                                                <p class="mb-0">Tidak ada data PO yang ditemukan untuk rentang tanggal
                                                    yang dipilih.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            @else
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                                            <p class="mb-0">Silakan gunakan filter untuk menampilkan laporan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
