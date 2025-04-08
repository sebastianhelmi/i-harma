@extends('layouts.inventory')

@section('title', 'Dashboard')

@section('content')
    <div class="dashboard">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">📦 Dashboard Inventory</h1>
                <p class="text-muted">Ringkasan data logistik proyek</p>
            </div>
            <button class="btn btn-light" id="dateFilter">
                <i class="icon" data-lucide="calendar"></i>
                Filter periode
            </button>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card stat-card bg-warning bg-opacity-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-warning bg-opacity-20">
                                <i data-lucide="package"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1">Total Item di Gudang</h6>
                                <h3 class="mb-0">1,250</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-success bg-opacity-10">
                                <i data-lucide="download"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1">Barang Masuk Bulan Ini</h6>
                                <h3 class="mb-0">320</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-danger bg-opacity-10">
                                <i data-lucide="upload"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1">Barang Keluar Bulan Ini</h6>
                                <h3 class="mb-0">290</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card stat-card bg-danger bg-opacity-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-danger bg-opacity-20">
                                <i data-lucide="alert-triangle"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1">Stok Menipis</h6>
                                <h3 class="mb-0">12</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts & Tables Section -->
        <div class="row g-4 mb-4">
            <!-- Activity Chart -->
            <div class="col-12 col-xl-8">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">📊 Aktivitas Barang Masuk & Keluar</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="activityChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="col-12 col-xl-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Aktivitas Terbaru</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <span class="activity-icon bg-success">
                                        <i data-lucide="check"></i>
                                    </span>
                                    <div class="ms-3">
                                        <p class="mb-0">10 Pipa PVC diterima</p>
                                        <small class="text-muted">2 jam yang lalu</small>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <span class="activity-icon bg-danger">
                                        <i data-lucide="x"></i>
                                    </span>
                                    <div class="ms-3">
                                        <p class="mb-0">25 Batako dikeluarkan</p>
                                        <small class="text-muted">3 jam yang lalu</small>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <span class="activity-icon bg-warning">
                                        <i data-lucide="alert-triangle"></i>
                                    </span>
                                    <div class="ms-3">
                                        <p class="mb-0">Stok Semen menipis</p>
                                        <small class="text-muted">5 jam yang lalu</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Daftar Barang Stok Menipis</h5>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Item</th>
                                <th>Stok</th>
                                <th>Lokasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>A001</td>
                                <td>Semen 50kg</td>
                                <td><span class="badge bg-danger">3</span></td>
                                <td>Gudang 1</td>
                                <td>
                                    <button class="btn btn-sm btn-light">
                                        <i data-lucide="refresh-cw"></i> Reorder
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>B014</td>
                                <td>Paku 3 inch</td>
                                <td><span class="badge bg-warning">5</span></td>
                                <td>Gudang 2</td>
                                <td>
                                    <button class="btn btn-sm btn-light">
                                        <i data-lucide="refresh-cw"></i> Reorder
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('activityChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                        datasets: [{
                            label: 'Barang Masuk',
                            data: [65, 59, 80, 81, 56, 55],
                            borderColor: '#22C55E',
                            tension: 0.3
                        }, {
                            label: 'Barang Keluar',
                            data: [28, 48, 40, 19, 86, 27],
                            borderColor: '#EF4444',
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            });
        </script>
    @endpush
@endsection
