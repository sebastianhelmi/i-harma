@extends('layouts.head-of-division')

@section('title', 'Proyek')

@section('page-title', 'Daftar Proyek')

@section('content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="projects-header mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('head-of-division.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Proyek</li>
                </ol>
            </nav>
            <h1 class="h3 mb-2 text-gray-800">Daftar Proyek</h1>
            <p class="text-muted">Berikut adalah proyek yang sedang ditangani oleh divisi Anda.</p>
        </div>

        <!-- Filter Section -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" placeholder="Cari proyek...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="statusFilter">
                            <option value="">Semua Status</option>
                            <option value="running">Berjalan</option>
                            <option value="completed">Selesai</option>
                            <option value="delayed">Tertunda</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Projects List -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Nama Proyek</th>
                                <th>Lokasi</th>
                                <th>Deadline</th>
                                <th>Progres</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Project 1 -->
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="project-icon bg-primary-soft me-3">
                                            <i class="fas fa-building text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Pembangunan Gudang A</h6>
                                            <small class="text-muted">ID: PRJ-001</small>
                                        </div>
                                    </div>
                                </td>
                                <td>Jakarta Timur</td>
                                <td>2025-05-10</td>
                                <td style="width: 200px;">
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 80%">
                                            </div>
                                        </div>
                                        <span class="text-muted small">80%</span>
                                    </div>
                                </td>
                                <td><span class="badge bg-primary">Berjalan</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                        <i class="fas fa-eye me-1"></i>Detail
                                    </button>
                                </td>
                            </tr>

                            <!-- Project 2 -->
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="project-icon bg-success-soft me-3">
                                            <i class="fas fa-building text-success"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Renovasi Kantor B</h6>
                                            <small class="text-muted">ID: PRJ-002</small>
                                        </div>
                                    </div>
                                </td>
                                <td>Bandung</td>
                                <td>2025-04-30</td>
                                <td style="width: 200px;">
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%">
                                            </div>
                                        </div>
                                        <span class="text-muted small">100%</span>
                                    </div>
                                </td>
                                <td><span class="badge bg-success">Selesai</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                        <i class="fas fa-eye me-1"></i>Detail
                                    </button>
                                </td>
                            </tr>

                            <!-- Project 3 -->
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="project-icon bg-danger-soft me-3">
                                            <i class="fas fa-building text-danger"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Pembuatan Pos Satpam</h6>
                                            <small class="text-muted">ID: PRJ-003</small>
                                        </div>
                                    </div>
                                </td>
                                <td>Depok</td>
                                <td class="text-danger">2025-05-20</td>
                                <td style="width: 200px;">
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 40%">
                                            </div>
                                        </div>
                                        <span class="text-muted small">40%</span>
                                    </div>
                                </td>
                                <td><span class="badge bg-danger">Tertunda</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                        <i class="fas fa-eye me-1"></i>Detail
                                    </button>
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

    <!-- Mobile View Cards (Shown only on small screens) -->
    <div class="d-md-none mt-4">
        <div class="project-card mb-3">
            <!-- Mobile cards will be shown here -->
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .project-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bg-primary-soft {
            background-color: rgba(30, 58, 138, 0.1);
        }

        .bg-success-soft {
            background-color: rgba(34, 197, 94, 0.1);
        }

        .bg-danger-soft {
            background-color: rgba(239, 68, 68, 0.1);
        }

        .progress {
            background-color: #e9ecef;
            border-radius: 10px;
        }

        .progress-bar {
            border-radius: 10px;
        }

        .badge {
            padding: 0.5em 1em;
            font-weight: 500;
        }

        .table> :not(caption)>*>* {
            padding: 1rem 0.75rem;
        }

        .table>tbody>tr {
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .table>tbody>tr:hover {
            background-color: rgba(30, 58, 138, 0.05);
        }

        @media (max-width: 768px) {
            .project-card {
                background: white;
                border-radius: 10px;
                padding: 1rem;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add click event to table rows
            const projectRows = document.querySelectorAll('tbody tr');
            projectRows.forEach(row => {
                row.addEventListener('click', function(e) {
                    if (!e.target.closest('button')) {
                        // Get project ID from the row and redirect to detail page
                        const projectId = this.querySelector('small').textContent.split(':')[1]
                            .trim();
                        window.location.href = `/head-of-division/projects/${projectId}`;
                    }
                });
            });
        });
    </script>
@endpush
