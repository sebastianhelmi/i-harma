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
                                <h3 class="card-title mb-0">12</h3>
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
                                <h3 class="card-title mb-0">42</h3>
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
                                <h3 class="card-title mb-0">17</h3>
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
                                <h3 class="card-title mb-0">5</h3>
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
                            <!-- Project 1 -->
                            <div class="project-item mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Gedung A</h6>
                                    <span class="badge bg-primary">Berjalan</span>
                                </div>
                                <div class="progress" style="height: 10px">
                                    <div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted">75% selesai</small>
                            </div>

                            <!-- Project 2 -->
                            <div class="project-item mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Pabrik X</h6>
                                    <span class="badge bg-success">Selesai</span>
                                </div>
                                <div class="progress" style="height: 10px">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%"
                                        aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted">100% selesai</small>
                            </div>

                            <!-- Project 3 -->
                            <div class="project-item mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Gudang Y</h6>
                                    <span class="badge bg-warning">Tertunda</span>
                                </div>
                                <div class="progress" style="height: 10px">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 35%"
                                        aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted">35% selesai</small>
                            </div>
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
                            <!-- Task 1 -->
                            <div class="task-item p-3 mb-2 bg-light rounded">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1">Pemasangan pipa</h6>
                                    <small class="text-danger">8 Apr</small>
                                </div>
                                <p class="mb-1 small">Proyek: Gedung A</p>
                                <small class="text-muted">Assigned to: Dedi</small>
                            </div>

                            <!-- Task 2 -->
                            <div class="task-item p-3 mb-2 bg-light rounded">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1">Cat dinding</h6>
                                    <small class="text-muted">10 Apr</small>
                                </div>
                                <p class="mb-1 small">Proyek: Gudang Y</p>
                                <small class="text-muted">Assigned to: Sinta</small>
                            </div>

                            <!-- Task 3 -->
                            <div class="task-item p-3 mb-2 bg-light rounded">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1">Pasang pintu</h6>
                                    <small class="text-muted">9 Apr</small>
                                </div>
                                <p class="mb-1 small">Proyek: Gedung A</p>
                                <small class="text-muted">Assigned to: Anton</small>
                            </div>
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
                            <!-- Notification 1 -->
                            <div class="notification-item d-flex align-items-center p-3 border-bottom">
                                <div class="flex-shrink-0 me-3">
                                    <i class="fas fa-check-circle text-success fa-lg"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Tugas "Bersihkan Area" diselesaikan</h6>
                                    <small class="text-muted">6 Apr 2025</small>
                                </div>
                            </div>

                            <!-- Notification 2 -->
                            <div class="notification-item d-flex align-items-center p-3 border-bottom">
                                <div class="flex-shrink-0 me-3">
                                    <i class="fas fa-file-alt text-primary fa-lg"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">SPB #SPB-00123 disetujui</h6>
                                    <small class="text-muted">5 Apr 2025</small>
                                </div>
                            </div>

                            <!-- Notification 3 -->
                            <div class="notification-item d-flex align-items-center p-3">
                                <div class="flex-shrink-0 me-3">
                                    <i class="fas fa-exclamation-triangle text-warning fa-lg"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Proyek "Gudang Y" tertunda</h6>
                                    <small class="text-muted">4 Apr 2025</small>
                                </div>
                            </div>
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
