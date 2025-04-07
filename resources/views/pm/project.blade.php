@extends('layouts.pm')

@section('title', 'Proyek')

@section('page-title', 'Proyek')

@section('content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('pm.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Proyek</li>
                    </ol>
                </nav>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProjectModal">
                <i class="fas fa-plus me-2"></i>Tambah Proyek
            </button>
        </div>

        <!-- Filter Section -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <select class="form-select" id="statusFilter">
                            <option value="">Semua Status</option>
                            <option value="running">Berjalan</option>
                            <option value="completed">Selesai</option>
                            <option value="delayed">Tertunda</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" placeholder="Cari proyek...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-secondary w-100">Terapkan Filter</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Toggle -->
        <div class="d-flex justify-content-end mb-3">
            <div class="btn-group">
                <button class="btn btn-outline-primary active" data-view="card">
                    <i class="fas fa-grid-2"></i>
                </button>
                <button class="btn btn-outline-primary" data-view="table">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>

        <!-- Card View -->
        <div id="cardView" class="row g-4 mb-4">
            <!-- Project Card 1 -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="card-title">Gedung A</h5>
                            <span class="badge bg-warning">Berjalan</span>
                        </div>
                        <div class="mb-3">
                            <p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>Jakarta</p>
                            <p class="mb-1"><i class="fas fa-calendar me-2"></i>1 Jan - 30 Apr</p>
                        </div>
                        <div class="progress mb-3" style="height: 10px">
                            <div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <p class="text-muted mb-3">75% selesai</p>
                        <div class="project-actions">
                            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Project Card 2 -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="card-title">Pabrik X</h5>
                            <span class="badge bg-success">Selesai</span>
                        </div>
                        <div class="mb-3">
                            <p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>Bekasi</p>
                            <p class="mb-1"><i class="fas fa-calendar me-2"></i>1 Feb - 1 Apr</p>
                        </div>
                        <div class="progress mb-3" style="height: 10px">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <p class="text-muted mb-3">100% selesai</p>
                        <div class="project-actions">
                            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Project Card 3 -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="card-title">Gudang Y</h5>
                            <span class="badge bg-danger">Tertunda</span>
                        </div>
                        <div class="mb-3">
                            <p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>Tangerang</p>
                            <p class="mb-1"><i class="fas fa-calendar me-2"></i>5 Mar - 30 Mei</p>
                        </div>
                        <div class="progress mb-3" style="height: 10px">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 35%" aria-valuenow="35"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <p class="text-muted mb-3">35% selesai</p>
                        <div class="project-actions">
                            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table View (Initially Hidden) -->
        <div id="tableView" class="card border-0 shadow-sm mb-4" style="display: none;">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama Proyek</th>
                                <th>Lokasi</th>
                                <th>Mulai</th>
                                <th>Deadline</th>
                                <th>Progress</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Gedung A</td>
                                <td>Jakarta</td>
                                <td>1 Jan</td>
                                <td>30 Apr</td>
                                <td>
                                    <div class="progress" style="height: 5px; width: 100px;">
                                        <div class="progress-bar" role="progressbar" style="width: 75%"></div>
                                    </div>
                                    <small>75%</small>
                                </td>
                                <td><span class="badge bg-warning">Berjalan</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <!-- Add more rows for other projects -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation" class="d-flex justify-content-center">
            <ul class="pagination">
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

    <!-- Add Project Modal -->
    <div class="modal fade" id="addProjectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Proyek Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Nama Proyek</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Lokasi</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Deadline</label>
                                <input type="date" class="form-control">
                            </div>
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
        .project-actions {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .card:hover .project-actions {
            opacity: 1;
        }

        @media (max-width: 768px) {
            .project-actions {
                opacity: 1;
            }
        }

        .progress {
            border-radius: 10px;
        }

        .progress-bar {
            transition: width 1s ease;
        }

        .badge {
            padding: 0.5em 1em;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // View toggle functionality
            const viewButtons = document.querySelectorAll('[data-view]');
            const cardView = document.getElementById('cardView');
            const tableView = document.getElementById('tableView');

            viewButtons.forEach(button => {
                button.addEventListener('click', () => {
                    viewButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');

                    if (button.dataset.view === 'card') {
                        cardView.style.display = 'flex';
                        tableView.style.display = 'none';
                    } else {
                        cardView.style.display = 'none';
                        tableView.style.display = 'block';
                    }
                });
            });
        });
    </script>
@endpush
