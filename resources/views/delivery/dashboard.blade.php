@extends('layouts.delivery')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <!-- Pengiriman Hari Ini -->
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="delivery-icon primary me-3">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted">Pengiriman Hari Ini</h6>
                            <h2 class="mb-0">5</h2>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-success">
                            <i class="fas fa-arrow-up me-1"></i>2 dari kemarin
                        </small>
                        <a href="#" class="text-decoration-none">Detail</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menunggu Pengiriman -->
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="delivery-icon warning me-3">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted">Menunggu Pengiriman</h6>
                            <h2 class="mb-0">8</h2>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-warning">
                            <i class="fas fa-exclamation-circle me-1"></i>3 mendekati deadline
                        </small>
                        <a href="#" class="text-decoration-none">Detail</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dalam Perjalanan -->
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="delivery-icon info me-3">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted">Dalam Perjalanan</h6>
                            <h2 class="mb-0">3</h2>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-info">
                            <i class="fas fa-route me-1"></i>Sedang dalam rute
                        </small>
                        <a href="#" class="text-decoration-none">Detail</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Selesai Bulan Ini -->
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="delivery-icon success me-3">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted">Selesai Bulan Ini</h6>
                            <h2 class="mb-0">42</h2>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-success">
                            <i class="fas fa-chart-line me-1"></i>90% tepat waktu
                        </small>
                        <a href="#" class="text-decoration-none">Detail</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Pengiriman Hari Ini -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Pengiriman Hari Ini</h5>
                        <a href="#" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i>Tambah Pengiriman
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No. SPB</th>
                                    <th>Tujuan</th>
                                    <th>Item</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>SPB-202506-001</td>
                                    <td>Site A - Jakarta Utara</td>
                                    <td>3 items</td>
                                    <td><span class="badge bg-warning">Menunggu Pickup</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>SPB-202506-002</td>
                                    <td>Site B - Bekasi</td>
                                    <td>5 items</td>
                                    <td><span class="badge bg-info">Dalam Perjalanan</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <!-- More rows... -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rencana Pengiriman -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Rencana Pengiriman</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between mb-1">
                                <h6 class="mb-0">Site C - Tangerang</h6>
                                <small class="text-muted">Besok</small>
                            </div>
                            <p class="mb-1">4 items - Material Bangunan</p>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>Jadwal: 09:00 WIB
                            </small>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between mb-1">
                                <h6 class="mb-0">Site D - Depok</h6>
                                <small class="text-muted">2 hari lagi</small>
                            </div>
                            <p class="mb-1">2 items - Peralatan</p>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>Jadwal: 10:30 WIB
                            </small>
                        </div>
                        <!-- More items... -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('styles')
<style>
    .delivery-icon {
        width: 3rem;
        height: 3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.5rem;
        font-size: 1.25rem;
    }

    .delivery-icon.primary {
        background-color: rgba(37, 99, 235, 0.1);
        color: #2563eb;
    }

    .delivery-icon.warning {
        background-color: rgba(234, 179, 8, 0.1);
        color: #eab308;
    }

    .delivery-icon.info {
        background-color: rgba(6, 182, 212, 0.1);
        color: #06b6d4;
    }

    .delivery-icon.success {
        background-color: rgba(22, 163, 74, 0.1);
        color: #16a34a;
    }

    .card {
        border: none;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
    }
</style>
@endpush