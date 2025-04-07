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

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <!-- Total Projects Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="stat-icon bg-primary-soft">
                                <i class="fas fa-folder text-primary"></i>
                            </div>
                            <div class="text-end">
                                <h3 class="mb-1">12</h3>
                                <span class="text-muted">Total Proyek</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completed Tasks Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="stat-icon bg-success-soft">
                                <i class="fas fa-check-circle text-success"></i>
                            </div>
                            <div class="text-end">
                                <h3 class="mb-1">86</h3>
                                <span class="text-muted">Tugas Selesai</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Tasks Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="stat-icon bg-warning-soft">
                                <i class="fas fa-clock text-warning"></i>
                            </div>
                            <div class="text-end">
                                <h3 class="mb-1">14</h3>
                                <span class="text-muted">Tugas Belum Selesai</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Overdue Tasks Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="stat-icon bg-danger-soft">
                                <i class="fas fa-exclamation-circle text-danger"></i>
                            </div>
                            <div class="text-end">
                                <h3 class="mb-1">3</h3>
                                <span class="text-muted">Tugas Lewat Deadline</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Projects and Tasks Section -->
        <div class="row g-4 mb-4">
            <!-- Project Progress -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Progres Proyek Aktif</h5>
                            <button class="btn btn-sm btn-outline-primary">Lihat Semua</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Project A -->
                        <div class="project-progress mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-medium">Proyek A</span>
                                <span class="text-muted">70%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 70%"></div>
                            </div>
                        </div>

                        <!-- Project B -->
                        <div class="project-progress mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-medium">Proyek B</span>
                                <span class="text-muted">45%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 45%"></div>
                            </div>
                        </div>

                        <!-- Project C -->
                        <div class="project-progress">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-medium">Proyek C</span>
                                <span class="text-muted">90%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 90%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Latest Tasks -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Tugas Terbaru</h5>
                            <button class="btn btn-sm btn-outline-primary">Lihat Semua</button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Tugas</th>
                                        <th>Proyek</th>
                                        <th>Tenggat</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Instalasi Pipa</td>
                                        <td>Proyek A</td>
                                        <td>2025-04-12</td>
                                        <td><span class="badge bg-primary">In Progress</span></td>
                                    </tr>
                                    <tr>
                                        <td>Finishing Dinding</td>
                                        <td>Proyek B</td>
                                        <td>2025-04-10</td>
                                        <td><span class="badge bg-warning text-dark">Pending</span></td>
                                    </tr>
                                    <tr>
                                        <td>Inspeksi Akhir</td>
                                        <td>Proyek C</td>
                                        <td>2025-04-07</td>
                                        <td><span class="badge bg-success">Completed</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications Section -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">Pemberitahuan Penting</h5>
            </div>
            <div class="card-body">
                <div class="notification-list">
                    <div class="notification-item p-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="notification-icon bg-warning-soft me-3">
                                <i class="fas fa-exclamation-circle text-warning"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">3 tugas melewati deadline minggu ini</h6>
                                <small class="text-muted">2 jam yang lalu</small>
                            </div>
                        </div>
                    </div>
                    <div class="notification-item p-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="notification-icon bg-info-soft me-3">
                                <i class="fas fa-file-alt text-info"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Laporan belum di-submit untuk Proyek B</h6>
                                <small class="text-muted">5 jam yang lalu</small>
                            </div>
                        </div>
                    </div>
                    <div class="notification-item p-3">
                        <div class="d-flex align-items-center">
                            <div class="notification-icon bg-primary-soft me-3">
                                <i class="fas fa-file-invoice text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">SPB #SPB-230 belum ditindaklanjuti</h6>
                                <small class="text-muted">1 hari yang lalu</small>
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
