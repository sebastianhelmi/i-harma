@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-title')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-chart-line fa-lg text-primary"></i>
        <span>Dashboard Admin</span>
    </div>
@endsection

@section('page-subtitle')
    <div class="d-flex align-items-center text-muted">
        <i class="fas fa-calendar-alt me-2"></i>
        <span>{{ now()->format('l, d F Y') }}</span>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Welcome Message -->
        <div class="welcome-message mb-4">
            <h4>👋 Selamat datang kembali, {{ auth()->user()->name }}!</h4>
            <p class="text-muted">Berikut adalah ringkasan aktivitas sistem hari ini.</p>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <!-- Users Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="stat-icon bg-primary-soft">
                                <i class="fas fa-users text-primary"></i>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    {{-- {{ route('admin.users.index') }} --}}
                                    <a href="" class="dropdown-item">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                        <h3 class="mt-3 mb-1">128</h3>
                        <p class="text-muted mb-0">Total User</p>
                    </div>
                </div>
            </div>

            <!-- Items Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="stat-icon bg-success-soft">
                                <i class="fas fa-box text-success"></i>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    {{-- {{ route('admin.inventory.index') }} --}}
                                    <a href="" class="dropdown-item">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                        <h3 class="mt-3 mb-1">340</h3>
                        <p class="text-muted mb-0">Total Item</p>
                    </div>
                </div>
            </div>

            <!-- SPB Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="stat-icon bg-warning-soft">
                                <i class="fas fa-file-alt text-warning"></i>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    {{-- {{ route('admin.spb.index') }} --}}
                                    <a href="" class="dropdown-item">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                        <h3 class="mt-3 mb-1">89</h3>
                        <p class="text-muted mb-0">Total SPB Bulan Ini</p>
                    </div>
                </div>
            </div>

            <!-- Logs Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="stat-icon bg-info-soft">
                                <i class="fas fa-history text-info"></i>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    {{-- {{ route('admin.logs.index') }} --}}
                                    <a href="" class="dropdown-item">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                        <h3 class="mt-3 mb-1">15</h3>
                        <p class="text-muted mb-0">Log Hari Ini</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Aksi Cepat</h5>
                        <div class="d-flex flex-wrap gap-2">
                            {{-- {{ route('admin.users.create') }} --}}
                            <a href="" class="btn btn-light">
                                <i class="fas fa-user-plus me-2"></i>Tambah User
                            </a>
                            {{-- {{ route('admin.categories.create') }} --}}
                            <a href="" class="btn btn-light">
                                <i class="fas fa-folder-plus me-2"></i>Tambah Kategori
                            </a>
                            {{-- {{ route('admin.inventory.create') }} --}}
                            <a href="" class="btn btn-light">
                                <i class="fas fa-box-open me-2"></i>Tambah Item
                            </a>
                            {{-- {{ route('admin.settings.index') }} --}}
                            <a href="" class="btn btn-light">
                                <i class="fas fa-cog me-2"></i>Pengaturan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Latest SPB Table -->
            <div class="col-lg-7">
                <div class="card h-100">
                    <div class="card-header border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">SPB Terbaru</h5>
                            {{-- {{ route('admin.spb.index') }} --}}
                            <a href="" class="btn btn-sm btn-primary">
                                Lihat Semua
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Nomor SPB</th>
                                        <th>Proyek</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>SPB-202504</td>
                                        <td>Proyek A</td>
                                        <td>05 Apr 2025</td>
                                        <td><span class="badge bg-warning">Pending</span></td>
                                    </tr>
                                    <tr>
                                        <td>SPB-202503</td>
                                        <td>Proyek B</td>
                                        <td>04 Apr 2025</td>
                                        <td><span class="badge bg-success">Approved</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Latest Activity Logs -->
            <div class="col-lg-5">
                <div class="card h-100">
                    <div class="card-header border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Aktivitas Terbaru</h5>
                            {{-- {{ route('admin.logs.index') }} --}}
                            <a href="" class="btn btn-sm btn-primary">
                                Lihat Semua
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="activity-list">
                            <div class="activity-item">
                                <div class="activity-icon bg-primary-soft">
                                    <i class="fas fa-file-alt text-primary"></i>
                                </div>
                                <div class="activity-content">
                                    <p class="mb-0">User A menambahkan SPB baru</p>
                                    <small class="text-muted">5 menit yang lalu</small>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon bg-success-soft">
                                    <i class="fas fa-box text-success"></i>
                                </div>
                                <div class="activity-content">
                                    <p class="mb-0">Item baru ditambahkan oleh Admin</p>
                                    <small class="text-muted">10 menit yang lalu</small>
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
            background-color: rgba(59, 130, 246, 0.1);
        }

        .bg-success-soft {
            background-color: rgba(16, 185, 129, 0.1);
        }

        .bg-warning-soft {
            background-color: rgba(245, 158, 11, 0.1);
        }

        .bg-info-soft {
            background-color: rgba(6, 182, 212, 0.1);
        }

        .activity-list {
            .activity-item {
                display: flex;
                align-items: start;
                padding: 1rem 0;
                border-bottom: 1px solid #e5e7eb;

                &:last-child {
                    border-bottom: none;
                }

                .activity-icon {
                    width: 40px;
                    height: 40px;
                    border-radius: 8px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin-right: 1rem;
                }

                .activity-content {
                    flex: 1;
                }
            }
        }

        @media (max-width: 768px) {
            .activity-list {
                margin-top: 1rem;
            }
        }
    </style>
@endpush
