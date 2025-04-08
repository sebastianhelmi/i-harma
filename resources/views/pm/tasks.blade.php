@extends('layouts.pm')

@section('title', 'Tugas')

@section('page-title', 'Tugas')

@section('content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('pm.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Tugas</li>
                    </ol>
                </nav>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                <i class="fas fa-plus me-2"></i>Tambah Tugas
            </button>
        </div>

        <!-- Filter Section -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <select class="form-select" id="projectFilter">
                            <option value="">Semua Proyek</option>
                            <option value="1">Gedung A</option>
                            <option value="2">Pabrik X</option>
                            <option value="3">Gudang Y</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="statusFilter">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="progress">In Progress</option>
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
                            <input type="text" class="form-control" placeholder="Cari tugas...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tasks List -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 30%">Nama Tugas</th>
                                <th>Proyek</th>
                                <th>Deadline</th>
                                <th>Status</th>
                                <th>Progress</th>
                                <th>Ditugaskan Ke</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Main Task 1 -->
                            <tr class="task-main">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <button class="btn btn-sm btn-link p-0 me-2 toggle-subtasks">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                        <span>Bangun Kamar Mandi</span>
                                    </div>
                                </td>
                                <td>Gedung A</td>
                                <td>
                                    <span class="text-danger">
                                        <i class="fas fa-clock me-1"></i>10 Apr 2025
                                    </span>
                                </td>
                                <td><span class="badge bg-primary">In Progress</span></td>
                                <td>
                                    <div class="progress" style="height: 5px; width: 100px;">
                                        <div class="progress-bar" role="progressbar" style="width: 40%"></div>
                                    </div>
                                    <small>40%</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button>
                                        <button class="btn btn-sm btn-outline-secondary"><i
                                                class="fas fa-edit"></i></button>
                                        <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                        <button class="btn btn-sm btn-outline-info add-subtask" data-task-id="1">
                                            {{-- data-task-id="{{ $task->id }}" --}}
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <!-- Subtasks for Task 1 -->
                            <tr class="subtask d-none bg-light">
                                <td class="ps-5">
                                    <span>└─ Buat Bak Mandi</span>
                                </td>
                                <td>Gedung A</td>
                                <td>08 Apr 2025</td>
                                <td><span class="badge bg-warning text-dark">Pending</span></td>
                                <td>
                                    <div class="progress" style="height: 5px; width: 100px;">
                                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                    </div>
                                    <small>0%</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name=El" class="rounded-circle me-2"
                                            width="24">
                                        Electrical
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button>
                                        <button class="btn btn-sm btn-outline-secondary"><i
                                                class="fas fa-edit"></i></button>
                                        <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>

                            <!-- More tasks... -->
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

    <!-- Add Task Modal -->
    <div class="modal fade" id="addTaskModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Tugas Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="taskForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Tugas</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Proyek</label>
                                <select class="form-select" name="project_id" required>
                                    <option value="">Pilih Proyek</option>
                                    <option value="1">Gedung A</option>
                                    <option value="2">Pabrik X</option>
                                    <option value="3">Gudang Y</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Deadline</label>
                                <input type="date" class="form-control" name="due_date" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status" required>
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="saveTask">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Subtask Modal -->
    <div class="modal fade" id="addSubtaskModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Sub Tugas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="subtaskForm">
                        <input type="hidden" name="parent_task_id" id="parentTaskId">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Sub Tugas</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ditugaskan Ke</label>
                                <select class="form-select" name="assigned_to" required>
                                    <option value="">Pilih Staff</option>
                                    <option value="1">Andi</option>
                                    <option value="2">Budi</option>
                                    <option value="3">Dedi</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Deadline</label>
                                <input type="date" class="form-control" name="due_date" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status" required>
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="saveSubtask">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .task-main {
            cursor: pointer;
        }

        .toggle-subtasks .fas {
            transition: transform 0.3s ease;
        }

        .toggle-subtasks.active .fas {
            transform: rotate(90deg);
        }

        .badge {
            padding: 0.5em 1em;
        }

        .progress {
            border-radius: 10px;
        }

        .progress-bar {
            transition: width 1s ease;
        }

        .subtask {
            background-color: #f8f9fa;
            font-size: 0.95em;
        }

        .btn-group .btn {
            padding: 0.25rem 0.5rem;
        }

        .table> :not(caption)>*>* {
            padding: 1rem 0.75rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle subtasks
            const toggleButtons = document.querySelectorAll('.toggle-subtasks');
            toggleButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const mainRow = button.closest('tr');
                    const subtasks = mainRow.nextElementSibling;
                    button.classList.toggle('active');
                    subtasks.classList.toggle('d-none');
                });
            });

            // Row click to toggle subtasks
            const mainRows = document.querySelectorAll('.task-main');
            mainRows.forEach(row => {
                row.addEventListener('click', () => {
                    const button = row.querySelector('.toggle-subtasks');
                    button.click();
                });
            });

            document.querySelectorAll('.add-subtask').forEach(button => {
                button.addEventListener('click', () => {
                    const taskId = button.dataset.taskId;
                    document.getElementById('parentTaskId').value = 1;
                    const modal = new bootstrap.Modal(document.getElementById('addSubtaskModal'));
                    modal.show();
                });
            });
        });
    </script>
@endpush
