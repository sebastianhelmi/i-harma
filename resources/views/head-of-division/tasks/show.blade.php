@extends('layouts.head-of-division')

@section('title', 'Detail Tugas')

@section('content')
    @push('styles')
        <style>
            .table th {
                background-color: #f8fafc;
            }

            .badge {
                font-size: 0.85em;
            }
        </style>
    @endpush
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Detail Tugas</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('head-of-division.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('head-of-division.tasks.index') }}">Tugas</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('head-of-division.tasks.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Task Info Card -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h5 class="card-title mb-4">{{ $task->name }}</h5>

                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Deskripsi</h6>
                            <p class="mb-0">{{ $task->description ?: 'Tidak ada deskripsi' }}</p>
                        </div>

                        @if ($task->drawing_file)
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Gambar Teknis</h6>
                                <a href="{{ $task->getDrawingUrl() }}" target="_blank">
                                    <img src="{{ $task->getDrawingUrl() }}" alt="Drawing File"
                                        class="img-fluid rounded shadow-sm" style="max-height: 300px;">
                                </a>
                            </div>
                        @endif

                        <div class="row g-3">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2">Proyek</h6>
                                <p class="mb-0">
                                    <a href="{{ route('head-of-division.projects.show', $task->project_id) }}"
                                        class="text-decoration-none">
                                        {{ $task->project->name }}
                                    </a>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2">Deadline</h6>
                                <p class="mb-0">{{ $task->due_date }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 border-start">
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Status</h6>
                            <span class="badge bg-{{ $task->getStatusBadgeClass() }}">
                                <i class="fas {{ $task->getStatusIcon() }} me-1"></i>
                                {{ match ($task->status) {
                                    'pending' => 'Pending',
                                    'in_progress' => 'Sedang Dikerjakan',
                                    'completed' => 'Selesai',
                                } }}
                            </span>
                        </div>
                        <div class="d-grid gap-2">
                            @if ($task->status !== 'completed')
                                @if ($task->spb && $task->spb->category_entry === 'workshop')
                                    <button type="button" class="btn btn-success" onclick="showCompleteWorkshopModal()">
                                        <i class="fas fa-check-circle me-2"></i>Selesaikan Tugas Workshop
                                    </button>
                                @else
                                    <button type="button" class="btn btn-success"
                                        onclick="confirmComplete('{{ $task->id }}')">
                                        <i class="fas fa-check-circle me-2"></i>Selesaikan Tugas
                                    </button>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($task->spb && $task->spb->category_entry === 'workshop')
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Output Workshop</h5>
                </div>
                <div class="card-body">
                    @if ($task->workshopOutputs->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                        <th>Pengiriman</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($task->workshopOutputs as $output)
                                        <tr>
                                            <td>{{ $output->workshopSpb->explanation_items ?? '-' }}</td>
                                            <td>{{ $output->quantity_produced }} {{ $output->workshopSpb->unit ?? '-' }}
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $output->status === 'completed' ? 'success' : 'warning' }}">
                                                    {{ $output->status === 'completed' ? 'Selesai' : 'Pending' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($output->need_delivery)
                                                    <span class="badge bg-info">
                                                        <i class="fas fa-truck me-1"></i>Perlu Dikirim
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-warehouse me-1"></i>Simpan di Gudang
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $output->notes ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        @if ($task->status !== 'completed')
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Output workshop belum dicatat. Klik tombol "Selesaikan & Catat Output" untuk mencatat
                                output.
                            </div>
                        @else
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Tidak ada output yang dicatat untuk tugas ini.
                            </div>
                        @endif
                    @endif
                </div>
            </div>

        @endif
        <!-- Subtasks Section -->
        @if ($task->subtasks->isNotEmpty())
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Sub Tugas</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Nama Sub Tugas</th>
                                    <th>Deadline</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($task->subtasks as $subtask)
                                    <tr>
                                        <td>{{ $subtask->name }}</td>
                                        <td>{{ $subtask->due_date }}</td>
                                        <td>
                                            <span class="badge bg-{{ $subtask->getStatusBadgeClass() }}">
                                                <i class="fas {{ $subtask->getStatusIcon() }} me-1"></i>
                                                {{ match ($subtask->status) {
                                                    'pending' => 'Pending',
                                                    'in_progress' => 'Sedang Dikerjakan',
                                                    'completed' => 'Selesai',
                                                } }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>



    @push('styles')
        <style>
            .badge {
                padding: 0.5em 0.75em;
            }

            .card-title {
                color: #2d3748;
                font-weight: 600;
            }

            .text-muted {
                color: #718096 !important;
            }

            @media (max-width: 768px) {
                .border-start {
                    border-left: none !important;
                    border-top: 1px solid #e2e8f0;
                    margin-top: 1.5rem;
                    padding-top: 1.5rem;
                }
            }
        </style>
    @endpush


    @if ($task->spb && $task->spb->category_entry === 'workshop')
        @include('head-of-division.tasks._complete_workshop_task_modal')
    @endif
@endsection

@push('scripts')
    <script>
        function confirmComplete(taskId) {
            Swal.fire({
                title: 'Selesaikan Tugas?',
                text: "Pastikan semua sub-tugas dan SPB sudah selesai",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Selesaikan',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ url('head-of-division/tasks') }}/${taskId}/complete`;
                    form.innerHTML = `@csrf @method('PATCH')`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function showWorkshopOutputModal() {
            const modal = new bootstrap.Modal(document.getElementById('workshopOutputModal'));
            modal.show();
        }

        function showCompleteWorkshopModal() {
            const modal = new bootstrap.Modal(document.getElementById('completeWorkshopTaskModal'));
            modal.show();
        }
        // Add form validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('completeWorkshopTaskForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Validate quantities
                    const quantities = form.querySelectorAll('input[name$="[quantity_produced]"]');
                    let isValid = true;

                    quantities.forEach(input => {
                        if (parseInt(input.value) < 1) {
                            isValid = false;
                            input.classList.add('is-invalid');
                        } else {
                            input.classList.remove('is-invalid');
                        }
                    });

                    if (!isValid) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            text: 'Pastikan semua jumlah produksi valid'
                        });
                        return;
                    }

                    // Confirm submission
                    Swal.fire({
                        title: 'Selesaikan Tugas?',
                        text: 'Pastikan semua data produksi sudah benar',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Selesaikan',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            }
        });
    </script>
@endpush
