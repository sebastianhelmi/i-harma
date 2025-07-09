@extends('layouts.purchasing')

@section('title', 'Daftar SPB')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Daftar SPB untuk Diproses</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('purchasing.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">SPB</li>
                    </ol>
                </nav>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Search & Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('purchasing.spbs.index') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control"
                                placeholder="Cari No. SPB/Proyek/Pemohon..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="category_entry" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Kategori</option>
                            <option value="site" @selected(request('category_entry') == 'site')>Site</option>
                            <option value="workshop" @selected(request('category_entry') == 'workshop')>Workshop</option>
                        </select>
                    </div>
                    @if (request('search') || request('category_entry'))
                        <div class="col-md-2">
                            <a href="{{ route('purchasing.spbs.index') }}" class="btn btn-light">
                                <i class="fas fa-times me-2"></i>Reset Filter
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- SPB Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">SPB Menunggu Proses PO</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>No. SPB</th>
                                <th>Tanggal</th>
                                <th>Proyek</th>
                                <th>Task</th>
                                <th>Pemohon</th>
                                <th>Kategori</th>
                                <th>Status Pengajuan</th>
                                <th>Status PO</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($spbs as $spb)
                                <tr>
                                    <td>{{ $spb->spb_number }}</td>
                                    <td>{{ $spb->spb_date->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="text-truncate d-inline-block" style="max-width: 200px;"
                                            title="{{ $spb->project->name }}">
                                            {{ $spb->project->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-truncate d-inline-block" style="max-width: 150px;"
                                            title="{{ $spb->task->name }}">
                                            {{ $spb->task->name }}
                                        </span>
                                    </td>
                                    <td>{{ $spb->requester->name }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $spb->category_entry === 'site' ? 'Site' : 'Workshop' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $spb->getStatusBadgeClass() }}">
                                            {{ ucfirst($spb->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ ucfirst($spb->status_po) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('purchasing.spbs.show', $spb) }}" class="btn btn-sm btn-info"
                                                title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if ($spb->status === 'approved' && $spb->status_po === 'pending')
                                                <a href="{{ route('purchasing.pos.create', $spb) }}"
                                                    class="btn btn-sm btn-primary" title="Buat PO">
                                                    <i class="fas fa-plus"></i> Buat PO
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-2x mb-3"></i>
                                            <p class="mb-0">Tidak ada SPB yang perlu diproses</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $spbs->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

        <!-- SPB History -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Riwayat SPB yang Telah di-PO</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>No. SPB</th>
                                <th>No. PO</th>
                                <th>Tanggal PO</th>
                                <th>Proyek</th>
                                <th>Status PO</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($spbHistories as $spb)
                                <tr>
                                    <td>{{ $spb->spb_number }}</td>
                                    <td>
                                        @if($spb->po)
                                            <a href="{{ route('purchasing.pos.show', $spb->po->id) }}">{{ $spb->po->po_number }}</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $spb->po ? $spb->po->po_date->format('d/m/Y') : '-' }}</td>
                                    <td>{{ $spb->project->name }}</td>
                                    <td>
                                        <span class="badge bg-success">
                                            {{ ucfirst($spb->status_po) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('purchasing.spbs.show', $spb) }}" class="btn btn-sm btn-info"
                                                title="Lihat Detail SPB">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($spb->po)
                                                <a href="{{ route('purchasing.pos.show', $spb->po->id) }}" class="btn btn-sm btn-primary"
                                                    title="Lihat Detail PO">
                                                    <i class="fas fa-file-invoice"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-history fa-2x mb-3"></i>
                                            <p class="mb-0">Belum ada riwayat SPB yang di-PO.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $spbHistories->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .table> :not(caption)>*>* {
                padding: 1rem 0.75rem;
            }

            .badge {
                padding: 0.5em 0.75em;
            }

            .text-truncate {
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
        </style>
    @endpush
@endsection
