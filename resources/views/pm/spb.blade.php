@extends('layouts.pm')

@section('title', 'Daftar SPB')

@section('page-title', 'Surat Permintaan Barang')

@section('content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('pm.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">SPB</li>
                    </ol>
                </nav>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSpbModal">
                <i class="fas fa-plus me-2"></i>Buat SPB Baru
            </button>
        </div>

        <!-- Filter Section -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <select class="form-select" id="projectFilter">
                            <option value="">Semua Proyek</option>
                            <option value="1">Proyek A</option>
                            <option value="2">Proyek B</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="statusFilter">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="date" class="form-control" placeholder="Dari">
                            <input type="date" class="form-control" placeholder="Sampai">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" placeholder="Cari nomor SPB...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SPB Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Nomor SPB</th>
                                <th>Tanggal</th>
                                <th>Proyek</th>
                                <th>Tugas Terkait</th>
                                <th>Kategori Item</th>
                                <th>Status SPB</th>
                                <th>Status PO</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- SPB Item 1 -->
                            <tr>
                                <td>
                                    <span class="fw-medium">SPB-001</span>
                                </td>
                                <td>04 Apr 2025</td>
                                <td>Proyek A</td>
                                <td>Buat Meja Workshop</td>
                                <td>
                                    <span class="badge bg-info">Workshop</span>
                                </td>
                                <td>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">Belum PO</span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- SPB Item 2 -->
                            <tr>
                                <td>
                                    <span class="fw-medium">SPB-002</span>
                                </td>
                                <td>05 Apr 2025</td>
                                <td>Proyek B</td>
                                <td>Pasang Atap</td>
                                <td>
                                    <span class="badge bg-primary">Site</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">Approved</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">Sudah PO</span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- SPB Item 3 -->
                            <tr>
                                <td>
                                    <span class="fw-medium">SPB-003</span>
                                </td>
                                <td>06 Apr 2025</td>
                                <td>Proyek A</td>
                                <td>Bangun Kamar Mandi</td>
                                <td>
                                    <span class="badge bg-primary">Site</span>
                                </td>
                                <td>
                                    <span class="badge bg-info">Completed</span>
                                </td>
                                <td>
                                    <span class="badge bg-danger">Tidak Perlu PO</span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation" class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">Previous</a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Create SPB Modal -->
    <div class="modal fade" id="createSpbModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Buat SPB Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Proyek</label>
                                <select class="form-select">
                                    <option value="">Pilih Proyek</option>
                                    <option value="1">Proyek A</option>
                                    <option value="2">Proyek B</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tugas Terkait</label>
                                <select class="form-select">
                                    <option value="">Pilih Tugas</option>
                                    <option value="1">Buat Meja Workshop</option>
                                    <option value="2">Pasang Atap</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Kategori</label>
                                <select class="form-select">
                                    <option value="">Pilih Kategori</option>
                                    <option value="workshop">Workshop</option>
                                    <option value="site">Site</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Kebutuhan</label>
                                <input type="date" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Item yang Diminta</label>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nama Item</th>
                                            <th>Jumlah</th>
                                            <th>Satuan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" class="form-control form-control-sm"></td>
                                            <td><input type="number" class="form-control form-control-sm"></td>
                                            <td><input type="text" class="form-control form-control-sm"></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-sm btn-secondary">
                                <i class="fas fa-plus me-1"></i>Tambah Item
                            </button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .badge {
            padding: 0.5em 1em;
        }

        .table> :not(caption)>*>* {
            padding: 1rem 0.75rem;
        }

        .btn-group .btn {
            padding: 0.25rem 0.5rem;
        }

        .modal-lg {
            max-width: 900px;
        }

        /* Custom status colors */
        .badge.bg-warning {
            background-color: #FBBF24 !important;
        }

        .badge.bg-success {
            background-color: #22C55E !important;
        }

        .badge.bg-info {
            background-color: #3B82F6 !important;
            color: white;
        }

        .badge.bg-danger {
            background-color: #EF4444 !important;
        }
    </style>
@endpush
