@extends('layouts.purchasing')

@section('title', 'Dashboard')

@section('page-title')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-box fa-lg text-accent"></i>
        <span>Dashboard Purchasing</span>
    </div>
@endsection

@section('page-subtitle')
    <p class="text-muted mb-0">
        Selamat datang kembali, {{ auth()->user()->name }} 👋
    </p>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Summary Statistics -->
        <div class="row g-4 mb-4">
            <!-- SPB Requests Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="stat-icon bg-accent-soft">
                                <i class="fas fa-inbox text-accent"></i>
                            </div>
                            <span class="badge bg-accent">Hari Ini</span>
                        </div>
                        <h3 class="mt-3 mb-1">12</h3>
                        <p class="text-muted mb-0">Permintaan SPB Masuk</p>
                    </div>
                </div>
            </div>

            <!-- Processing PO Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="stat-icon bg-warning-soft">
                                <i class="fas fa-sync text-warning"></i>
                            </div>
                            <span class="badge bg-warning">Aktif</span>
                        </div>
                        <h3 class="mt-3 mb-1">5</h3>
                        <p class="text-muted mb-0">PO Sedang Diproses</p>
                    </div>
                </div>
            </div>

            <!-- Completed PO Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="stat-icon bg-success-soft">
                                <i class="fas fa-check text-success"></i>
                            </div>
                            <span class="badge bg-success">Bulan Ini</span>
                        </div>
                        <h3 class="mt-3 mb-1">8</h3>
                        <p class="text-muted mb-0">PO Selesai</p>
                    </div>
                </div>
            </div>

            <!-- Verified Vendors Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="stat-icon bg-purple-soft">
                                <i class="fas fa-building text-purple"></i>
                            </div>
                            <span class="badge bg-purple">Total</span>
                        </div>
                        <h3 class="mt-3 mb-1">14</h3>
                        <p class="text-muted mb-0">Vendor Terverifikasi</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest SPB and PO Activity -->
        <div class="row g-4 mb-4">
            <!-- Latest SPB Table -->
            <div class="col-xl-8">
                <div class="card h-100">
                    <div class="card-header border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-file-alt me-2"></i>
                                Permintaan SPB Terbaru
                            </h5>
                            {{-- {{ route('purchasing.spb.
                                ') }} --}}
                            <a href="" class="btn btn-sm btn-outline-accent">
                                Lihat Semua
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>No. SPB</th>
                                        <th>Tanggal</th>
                                        <th>Proyek</th>
                                        <th>Diajukan Oleh</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-medium">SPB-024</td>
                                        <td>2025-04-06</td>
                                        <td>Proyek Gudang A</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://ui-avatars.com/api/?name=Deni" class="rounded-circle me-2"
                                                    width="32" height="32">
                                                <span>Deni</span>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-accent">Baru</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-accent">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">SPB-023</td>
                                        <td>2025-04-05</td>
                                        <td>Proyek Kantor B</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://ui-avatars.com/api/?name=Wulan"
                                                    class="rounded-circle me-2" width="32" height="32">
                                                <span>Wulan</span>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-warning">Dalam Proses</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-accent">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Vendors -->
            <div class="col-xl-4">
                <div class="card h-100">
                    <div class="card-header border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-building me-2"></i>
                                Vendor Aktif
                            </h5>
                            {{-- {{ route('purchasing.vendors.index') }} --}}
                            <a href="" class="btn btn-sm btn-outline-accent">
                                Lihat Semua
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="vendor-list">
                            <div class="vendor-item d-flex align-items-center p-3 border-bottom">
                                <div class="vendor-icon bg-accent-soft rounded-circle p-3 me-3">
                                    <i class="fas fa-building text-accent"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">PT. Mitra Beton</h6>
                                    <small class="text-muted">3 PO Bulan Ini</small>
                                </div>
                                <span class="badge bg-success">Aktif</span>
                            </div>
                            <div class="vendor-item d-flex align-items-center p-3 border-bottom">
                                <div class="vendor-icon bg-accent-soft rounded-circle p-3 me-3">
                                    <i class="fas fa-building text-accent"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">CV. Sumber Makmur</h6>
                                    <small class="text-muted">2 PO Bulan Ini</small>
                                </div>
                                <span class="badge bg-success">Aktif</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-bell me-2"></i>
                    Notifikasi & Reminder
                </h5>
            </div>
            <div class="card-body">
                <div class="notification-list">
                    <div class="notification-item d-flex align-items-start p-3 border-bottom">
                        <div class="notification-icon bg-warning-soft rounded-circle p-2 me-3">
                            <i class="fas fa-exclamation-triangle text-warning"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">3 SPB menunggu persetujuan</h6>
                            <p class="text-muted mb-0">Harap segera ditindaklanjuti</p>
                        </div>
                    </div>
                    <div class="notification-item d-flex align-items-start p-3">
                        <div class="notification-icon bg-danger-soft rounded-circle p-2 me-3">
                            <i class="fas fa-clock text-danger"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">2 PO mendekati deadline pengiriman</h6>
                            <p class="text-muted mb-0">PT. Mitra Beton, CV. Sumber Makmur</p>
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
            background: #111827;
            /* darker-bg color */
            border: 1px solid #4B5563;
            /* border-color */
            color: $text-color;

            h3 {
                color: #F3F4F6;
            }

            .text-muted {
                color: #9CA3AF !important;
            }
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-color: #3B82F6;
            /* accent-color */
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Soft background colors with dark theme */
        .bg-accent-soft {
            background-color: rgba(59, 130, 246, 0.15);
        }

        .bg-accent {
            background-color: #60A5FA !important;
            color: #111827;
        }

        .bg-warning-soft {
            background-color: rgba(250, 204, 21, 0.15);
        }

        .bg-success-soft {
            background-color: rgba(132, 204, 22, 0.15);
        }

        .bg-purple-soft {
            background-color: rgba(147, 51, 234, 0.15);
        }

        .bg-danger-soft {
            background-color: rgba(239, 68, 68, 0.15);
        }

        .bg-warning {
            background-color: #FBBF24 !important;
            color: #111827;
        }

        .bg-success {
            background-color: #34D399 !important;
            color: #111827;
        }

        .bg-purple {
            background-color: #A78BFA !important;
            color: #111827;
        }


        /* Text colors for dark theme */
        .text-accent {
            color: #60A5FA;
        }

        /* Brighter blue for dark theme */
        .text-warning {
            color: #FCD34D;
        }

        /* Brighter yellow for dark theme */
        .text-success {
            color: #A3E635;
        }

        /* Brighter green for dark theme */
        .text-purple {
            color: #C084FC;
        }

        /* Brighter purple for dark theme */
        .text-danger {
            color: #F87171;
        }

        /* Brighter red for dark theme */

        .badge {
            padding: 0.5em 1em;
            font-weight: 500;
        }

        /* Table styling for dark theme */
        .table {
            color: #F9FAFB;
            /* text-color */
        }

        .table> :not(caption)>*>* {
            padding: 1rem 0.75rem;
            background-color: transparent;
            border-bottom-color: #4B5563;
            /* border-color */
        }

        .table>thead {
            background-color: #1F2937 !important;
            /* dark-bg */
        }

        .table-hover>tbody>tr:hover {
            background-color: rgba(59, 130, 246, 0.1);
            /* accent-color with opacity */
        }

        /* Card styling */
        .card {
            background: #111827;
            /* darker-bg */
            border: 1px solid #4B5563;
            /* border-color */
        }

        .card-header {
            background-color: #1F2937 !important;
            /* dark-bg */
            border-bottom-color: #4B5563 !important;
            /* border-color */
        }

        /* Notification items */
        .notification-item {
            border-bottom-color: #4B5563 !important;
            /* border-color */
        }

        .notification-item:hover {
            background-color: rgba(59, 130, 246, 0.1);
            /* accent-color with opacity */
        }

        /* Vendor items */
        .vendor-item {
            border-bottom-color: #4B5563 !important;
            /* border-color */
        }

        /* Custom button styles */
        .btn-outline-accent {
            color: #60A5FA;
            border-color: #60A5FA;
        }

        .btn-outline-accent:hover {
            background-color: #3B82F6;
            color: #F9FAFB;
        }

        @media (max-width: 768px) {
            .stat-card {
                margin-bottom: 1rem;
            }
        }
    </style>
@endpush
