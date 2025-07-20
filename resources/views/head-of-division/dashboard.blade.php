@extends('layouts.head-of-division')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="dashboard-header mb-4">
            <h1 class="h3 mb-2 text-gray-800">Dashboard Kepala Divisi</h1>
            <p class="text-muted">Ringkasan progres tugas dan proyek Anda</p>
        </div>

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
                                <h6 class="card-subtitle mb-1 text-muted">Total Proyek</h6>
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
            <div class="col-md-3 mt-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <i class="fas fa-chart-bar fa-2x text-info"></i>
                            </div>
                            <div>
                                <h6 class="card-subtitle mb-1 text-muted">Laporan Bulan Ini</h6>
                                <h3 class="card-title mb-0">{{ $reportsThisMonth }}</h3>
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

        .bg-primary-soft {
            background-color: rgba(30, 58, 138, 0.1);
        }

        .bg-success-soft {
            background-color: rgba(34, 197, 94, 0.1);
        }

        .bg-warning-soft {
            background-color: rgba(250, 204, 21, 0.1);
        }

        .bg-danger-soft {
            background-color: rgba(239, 68, 68, 0.1);
        }

        .bg-info-soft {
            background-color: rgba(59, 130, 246, 0.1);
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .progress {
            border-radius: 10px;
            background-color: #e9ecef;
        }

        .badge {
            padding: 0.5em 1em;
            font-weight: 500;
        }

        .table> :not(caption)>*>* {
            padding: 1rem 0.75rem;
        }
    </style>
@endpush
