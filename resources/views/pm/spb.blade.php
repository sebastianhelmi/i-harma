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
                                        <button class="btn btn-sm btn-outline-primary" title="Lihat Detail"
                                            data-bs-toggle="modal" data-bs-target="#viewSpbModal" data-spb-id="1"
                                            data-spb-number="SPB-001" data-spb-date="04 Apr 2025" data-project="Proyek A"
                                            data-task="Buat Meja Workshop" data-category="Workshop" data-status="pending"
                                            data-po-status="Belum PO">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success approve-spb" title="Setujui"
                                            data-id="1">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger reject-spb" title="Tolak"
                                            data-id="1">
                                            <i class="fas fa-times"></i>
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

    <!-- View SPB Modal -->
    <div class="modal fade" id="viewSpbModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail SPB</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Nomor SPB:</strong> <span id="spbNumber"></span></p>
                            <p><strong>Tanggal:</strong> <span id="spbDate"></span></p>
                            <p><strong>Proyek:</strong> <span id="spbProject"></span></p>
                            <p><strong>Tugas Terkait:</strong> <span id="spbTask"></span></p>
                            <p><strong>Diajukan Oleh:</strong> <span id="requestedBy">Electrical</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status SPB:</strong> <span id="spbStatus"></span></p>
                            <p><strong>Status PO:</strong> <span id="poStatus"></span></p>
                            <p><strong>Kategori Item:</strong> <span id="itemCategory"></span></p>
                            <p><strong>Kategori Entry:</strong> <span id="categoryEntry"></span></p>
                            <p><strong>Catatan:</strong> <span id="remarks"></span></p>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody id="spbItems">
                                <tr>
                                    <td>Semen</td>
                                    <td>10</td>
                                    <td>Sak</td>
                                    <td>Urgent</td>
                                </tr>
                                <tr>
                                    <td>Pasir</td>
                                    <td>2</td>
                                    <td>Truk</td>
                                    <td>-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <div id="approvalButtons">
                        <button type="button" class="btn btn-success approve-spb" data-id="">Setujui</button>
                        <button type="button" class="btn btn-danger reject-spb" data-id="">Tolak</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Populate modal with data when shown
            const viewModal = document.getElementById('viewSpbModal');
            viewModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const spbId = button.dataset.spbId;
                const spbNumber = button.dataset.spbNumber;
                const spbDate = button.dataset.spbDate;
                const project = button.dataset.project;
                const task = button.dataset.task;
                const category = button.dataset.category;
                const status = button.dataset.status;
                const poStatus = button.dataset.poStatus;

                // Set modal content
                viewModal.querySelector('#spbNumber').textContent = spbNumber;
                viewModal.querySelector('#spbDate').textContent = spbDate;
                viewModal.querySelector('#spbProject').textContent = project;
                viewModal.querySelector('#spbTask').textContent = task;
                viewModal.querySelector('#itemCategory').textContent = category;
                viewModal.querySelector('#spbStatus').textContent = status;
                viewModal.querySelector('#poStatus').textContent = poStatus;

                // Show/hide approval buttons based on status
                const approvalButtons = viewModal.querySelector('#approvalButtons');
                approvalButtons.style.display = status === 'pending' ? 'block' : 'none';
            });

            // ...existing approval and rejection handlers...
        });
    </script>
@endpush
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
