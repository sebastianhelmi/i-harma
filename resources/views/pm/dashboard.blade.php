@extends('layouts.pm')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <!-- Summary Stats -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <i class="fas fa-building fa-2x text-primary"></i>
                            </div>
                            <div>
                                <h6 class="card-subtitle mb-1 text-muted">Total Proyek Aktif</h6>
                                <h3 class="card-title mb-0">{{ $totalProjects }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            </div>
                            <div>
                                <h6 class="card-subtitle mb-1 text-muted">Tugas Selesai Bulan Ini</h6>
                                <h3 class="card-title mb-0">{{ $completedTasksThisMonth }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <i class="fas fa-tasks fa-2x text-warning"></i>
                            </div>
                            <div>
                                <h6 class="card-subtitle mb-1 text-muted">Tugas Berjalan</h6>
                                <h3 class="card-title mb-0">{{ $activeTasks }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <i class="fas fa-file-alt fa-2x text-danger"></i>
                            </div>
                            <div>
                                <h6 class="card-subtitle mb-1 text-muted">SPB Menunggu</h6>
                                <h3 class="card-title mb-0">{{ $pendingSpb }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Progress and Tasks -->
        <div class="row g-4">
            <!-- Project Progress -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Progress Proyek</h5>
                    </div>
                    <div class="card-body">
                        <div class="project-list">
                            @forelse($projectProgress as $project)
                                <div class="project-item mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0">{{ $project['name'] }}</h6>
                                        <span
                                            class="badge bg-{{ $project['status'] === 'completed' ? 'success' : ($project['status'] === 'pending' ? 'warning' : 'primary') }}">
                                            {{ ucfirst($project['status']) }}
                                        </span>
                                    </div>
                                    <div class="progress" style="height: 10px">
                                        <div class="progress-bar bg-{{ $project['status'] === 'completed' ? 'success' : ($project['status'] === 'pending' ? 'warning' : 'primary') }}"
                                            role="progressbar" style="width: {{ $project['progress'] }}%"
                                            aria-valuenow="{{ $project['progress'] }}" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-muted">{{ $project['progress'] }}% selesai</small>
                                </div>
                            @empty
                                <div class="text-muted">Tidak ada proyek</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tasks -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Tugas Deadline Minggu Ini</h5>
                    </div>
                    <div class="card-body">
                        <div class="task-list">
                            @forelse($tasksThisWeek as $task)
                                <div class="task-item p-3 mb-2 bg-light rounded">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1">{{ $task->name }}</h6>
                                        <small
                                            class="{{ $task->due_date->isPast() ? 'text-danger' : 'text-muted' }}">{{ $task->due_date->format('d M') }}</small>
                                    </div>
                                    <p class="mb-1 small">Proyek: {{ $task->project->name }}</p>
                                    <small class="text-muted">Assigned to: {{ $task->assignedTo->name ?? '-' }}</small>
                                </div>
                            @empty
                                <div class="text-muted">Tidak ada tugas deadline minggu ini</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Notifikasi Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <div class="notification-list">
                            @forelse($notifications as $notification)
                                <div class="notification-item d-flex align-items-center p-3 border-bottom">
                                    <div class="flex-shrink-0 me-3">
                                        <i class="fas fa-bell text-primary fa-lg"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $notification->data['message'] ?? '-' }}</h6>
                                        <small
                                            class="text-muted">{{ $notification->created_at->format('d M Y H:i') }}</small>
                                    </div>
                                </div>
                            @empty
                                <div class="text-muted">Tidak ada notifikasi baru</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .project-item:last-child {
            margin-bottom: 0 !important;
        }

        .task-item {
            transition: all 0.3s ease;
        }

        .task-item:hover {
            background-color: #f8f9fa !important;
            cursor: pointer;
        }

        .notification-item {
            transition: all 0.3s ease;
        }

        .notification-item:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        .progress {
            background-color: #e9ecef;
            border-radius: 0.5rem;
        }

        .progress-bar {
            border-radius: 0.5rem;
        }
    </style>
@endpush
