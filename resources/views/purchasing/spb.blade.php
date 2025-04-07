@extends('layouts.purchasing')

@section('title', 'Daftar SPB')

@section('page-title')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-file-alt fa-lg text-accent"></i>
        <span>Daftar SPB</span>
    </div>
@endsection

@section('page-subtitle')
    <p class="text-muted mb-0">
        Lihat dan kelola semua permintaan barang dari proyek yang sedang berjalan
    </p>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Filters Section -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <select class="form-select" id="statusFilter">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Disetujui</option>
                            <option value="rejected">Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="projectFilter">
                            <option value="">Semua Proyek</option>
                            <option value="1">Gudang A</option>
                            <option value="2">Kantor B</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" class="form-control" id="dateFilter">
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" placeholder="Cari nomor SPB...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row g-4 mb-4">
            <div class="col-sm-4">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-accent-soft me-3">
                                <i class="fas fa-file-alt text-accent"></i>
                            </div>
                            <div>
                                <h3 class="mb-0">20</h3>
                                <p class="text-muted mb-0">Total SPB Masuk</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-warning-soft me-3">
                                <i class="fas fa-shopping-cart text-warning"></i>
                            </div>
                            <div>
                                <h3 class="mb-0">8</h3>
                                <p class="text-muted mb-0">Perlu PO</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-success-soft me-3">
                                <i class="fas fa-check-circle text-success"></i>
                            </div>
                            <div>
                                <h3 class="mb-0">12</h3>
                                <p class="text-muted mb-0">SPB Selesai</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SPB Table -->
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Nomor SPB</th>
                                <th>Tanggal</th>
                                <th>Proyek</th>
                                <th>Diajukan Oleh</th>
                                <th>Status</th>
                                <th>Status PO</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-medium">SPB-025</td>
                                <td>2025-04-06</td>
                                <td>Gudang A</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name=Deni" class="rounded-circle me-2"
                                            width="32" height="32">
                                        <span>Deni</span>
                                    </div>
                                </td>
                                <td><span class="badge bg-success">Disetujui</span></td>
                                <td><span class="badge bg-warning">Belum Dibuat</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-accent" data-bs-toggle="modal"
                                        data-bs-target="#spbDetailModal">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <!-- Add more rows here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Floating Action Button -->
        <button class="fab-button" data-bs-toggle="modal" data-bs-target="#createPoModal">
            <i class="fas fa-plus"></i>
        </button>
    </div>

    <!-- SPB Detail Modal -->
    <div class="modal fade" id="spbDetailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail SPB</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Nomor SPB:</strong> SPB-025</p>
                            <p class="mb-1"><strong>Tanggal:</strong> 2025-04-06</p>
                            <p class="mb-1"><strong>Proyek:</strong> Gudang A</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Diajukan Oleh:</strong> Deni</p>
                            <p class="mb-1"><strong>Status:</strong> <span class="badge bg-success">Disetujui</span></p>
                            <p class="mb-1"><strong>Status PO:</strong> <span class="badge bg-warning">Belum Dibuat</span>
                            </p>
                        </div>
                    </div>

                    <h6 class="mb-3">Daftar Item</h6>
                    <div class="table-responsive">
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
                                <tr>
                                    <td>Semen</td>
                                    <td>50</td>
                                    <td>Sak</td>
                                    <td>Untuk pengecoran lantai</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-accent">
                        <i class="fas fa-shopping-cart me-2"></i>
                        Buat PO
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .stat-card {
            transition: transform 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bg-accent-soft {
            background-color: rgba(59, 130, 246, 0.1);
        }

        .bg-warning-soft {
            background-color: rgba(251, 191, 36, 0.1);
        }

        .bg-success-soft {
            background-color: rgba(34, 197, 94, 0.1);
        }

        .badge {
            padding: 0.5em 1em;
            font-weight: 500;
        }

        .table> :not(caption)>*>* {
            padding: 1rem 0.75rem;
        }

        /* Table styling for dark theme */
        .table {
            color: #F9FAFB;
            /* text-color */
        }

        .table> :not(caption)>*>* {
            padding: 1rem 0.75rem;
            background-color: transparent;
            border-bottom-color: #4B5563;
            /* border-color */
        }

        .table>thead {
            background-color: #1F2937 !important;
            /* dark-bg */
        }

        .table-hover>tbody>tr:hover {
            background-color: rgba(59, 130, 246, 0.1);
            /* accent-color with opacity */
        }

        /* Card styling */
        .card {
            background: #111827;
            /* darker-bg */
            border: 1px solid #4B5563;
            /* border-color */
        }

        .card-header {
            background-color: #1F2937 !important;
            /* dark-bg */
            border-bottom-color: #4B5563 !important;
            /* border-color */
        }

        .btn-outline-accent {
            color: #60A5FA;
            border-color: #60A5FA;
        }

        .btn-outline-accent:hover {
            background-color: #3B82F6;
            color: #F9FAFB;
        }

        .fab-button {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 56px;
            height: 56px;
            border-radius: 28px;
            background: #3B82F6;
            color: white;
            border: none;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            transition: transform 0.2s ease;
            z-index: 1000;
        }

        .fab-button:hover {
            transform: scale(1.1);
        }

        @media (max-width: 768px) {
            .fab-button {
                bottom: 5rem;
                /* Account for mobile navigation */
            }

            .modal-dialog {
                margin: 0.5rem;
                max-width: none;
            }
        }
    </style>
@endpush
