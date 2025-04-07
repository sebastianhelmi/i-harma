@extends('layouts.head-of-division')

@section('title', 'Tugas')

@section('page-title', 'Daftar Tugas')

@section('content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="tasks-header mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('head-of-division.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('head-of-division.projects') }}">Proyek</a></li>
                    <li class="breadcrumb-item active">Tugas</li>
                </ol>
            </nav>
            <h1 class="h3 mb-2 text-gray-800">Daftar Tugas</h1>
            <p class="text-muted">Pantau dan kelola tugas yang sedang dikerjakan oleh divisi Anda.</p>
        </div>

        <!-- Filter Section -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" placeholder="Cari tugas...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="statusFilter">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Selesai</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="memberFilter">
                            <option value="">Semua Anggota</option>
                            <option value="andi">Andi</option>
                            <option value="budi">Budi</option>
                            <option value="rina">Rina</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tasks Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 40%">Nama Tugas</th>
                                <th>Anggota</th>
                                <th>Deadline</th>
                                <th>Status</th>
                                <th>Progres</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Main Task 1 -->
                            <tr class="task-main">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <button class="btn btn-sm btn-link toggle-subtasks me-2">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                        <div>
                                            <h6 class="mb-0">Membuat Kamar Mandi</h6>
                                            <small class="text-muted">ID: TSK-001</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name=Andi" class="rounded-circle me-2"
                                            width="32" height="32">
                                        <span>Andi</span>
                                    </div>
                                </td>
                                <td>2025-04-25</td>
                                <td><span class="badge bg-primary">In Progress</span></td>
                                <td style="width: 200px;">
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                            <div class="progress-bar" role="progressbar" style="width: 50%"></div>
                                        </div>
                                        <span class="text-muted small">50%</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" title="Beri Laporan">
                                            <i class="fas fa-file-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Subtask 1.1 -->
                            <tr class="subtask d-none bg-light">
                                <td>
                                    <div class="d-flex align-items-center ps-4">
                                        <span class="me-2">└──</span>
                                        <div>
                                            <h6 class="mb-0">Buat Bak Mandi</h6>
                                            <small class="text-muted">ID: TSK-001-1</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name=Andi" class="rounded-circle me-2"
                                            width="32" height="32">
                                        <span>Andi</span>
                                    </div>
                                </td>
                                <td>2025-04-20</td>
                                <td><span class="badge bg-success">Completed</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%">
                                            </div>
                                        </div>
                                        <span class="text-muted small">100%</span>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Subtask 1.2 -->
                            <tr class="subtask d-none bg-light">
                                <td>
                                    <div class="d-flex align-items-center ps-4">
                                        <span class="me-2">└──</span>
                                        <div>
                                            <h6 class="mb-0">Buat WC</h6>
                                            <small class="text-muted">ID: TSK-001-2</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name=Budi" class="rounded-circle me-2"
                                            width="32" height="32">
                                        <span>Budi</span>
                                    </div>
                                </td>
                                <td>2025-04-23</td>
                                <td><span class="badge bg-primary">In Progress</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                            <div class="progress-bar" role="progressbar" style="width: 30%"></div>
                                        </div>
                                        <span class="text-muted small">30%</span>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Main Task 2 -->
                            <tr class="task-main">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <button class="btn btn-sm btn-link toggle-subtasks me-2" disabled>
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <div>
                                            <h6 class="mb-0">Pemasangan Keramik</h6>
                                            <small class="text-muted">ID: TSK-002</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name=Rina" class="rounded-circle me-2"
                                            width="32" height="32">
                                        <span>Rina</span>
                                    </div>
                                </td>
                                <td>2025-04-30</td>
                                <td><span class="badge bg-secondary">Pending</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                            <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                        </div>
                                        <span class="text-muted small">0%</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" title="Beri Laporan">
                                            <i class="fas fa-file-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Mobile Task Cards (visible only on small screens) -->
        <div class="d-md-none mt-4">
            <!-- Mobile cards will be added here -->
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .toggle-subtasks {
            padding: 0;
            color: #6B7280;
            transition: transform 0.2s ease;
        }

        .toggle-subtasks.active {
            transform: rotate(90deg);
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

        .btn-group .btn {
            padding: 0.25rem 0.5rem;
        }

        .subtask {
            background-color: #f8f9fa;
            font-size: 0.95em;
        }

        @media (max-width: 768px) {
            .task-card {
                background: white;
                border-radius: 10px;
                padding: 1rem;
                margin-bottom: 1rem;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle subtasks
            const toggleButtons = document.querySelectorAll('.toggle-subtasks:not([disabled])');
            toggleButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const mainRow = button.closest('tr');
                    const subtasks = [];
                    let next = mainRow.nextElementSibling;

                    while (next && next.classList.contains('subtask')) {
                        subtasks.push(next);
                        next = next.nextElementSibling;
                    }

                    button.classList.toggle('active');
                    subtasks.forEach(subtask => {
                        subtask.classList.toggle('d-none');
                    });
                });
            });
        });
    </script>
@endpush
