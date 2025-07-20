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

        <!-- Summary Stats -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <i class="fas fa-boxes fa-2x text-primary"></i>
                            </div>
                            <div>
                                <h6 class="card-subtitle mb-1 text-muted">Total Item</h6>
                                <h3 class="card-title mb-0">{{ $totalItems }}</h3>
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
                                <i class="fas fa-warehouse fa-2x text-success"></i>
                            </div>
                            <div>
                                <h6 class="card-subtitle mb-1 text-muted">Total Stok</h6>
                                <h3 class="card-title mb-0">{{ $totalStock }}</h3>
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
                                <i class="fas fa-shopping-cart fa-2x text-warning"></i>
                            </div>
                            <div>
                                <h6 class="card-subtitle mb-1 text-muted">PO Pending</h6>
                                <h3 class="card-title mb-0">{{ $pendingPo }}</h3>
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
                                <i class="fas fa-check-circle fa-2x text-info"></i>
                            </div>
                            <div>
                                <h6 class="card-subtitle mb-1 text-muted">PO Selesai</h6>
                                <h3 class="card-title mb-0">{{ $completedPo }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Grafik & Notifikasi -->
        <div class="row g-4 mt-2">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Stok per Kategori</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="stockByCategoryChart" height="180"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Transaksi Masuk/Keluar per Bulan</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="transactionsByMonthChart" height="180"></canvas>
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
        <script>
            // Grafik Stok per Kategori
            const stockByCategoryCtx = document.getElementById('stockByCategoryChart').getContext('2d');
            new Chart(stockByCategoryCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($stockByCategory->pluck('category')) !!},
                    datasets: [{
                        label: 'Stok',
                        data: {!! json_encode($stockByCategory->pluck('stock')) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.6)'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            // Grafik Transaksi per Bulan
            const transactionsByMonthCtx = document.getElementById('transactionsByMonthChart').getContext('2d');
            new Chart(transactionsByMonthCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($transactionsByMonth->pluck('month')) !!},
                    datasets: [{
                            label: 'Masuk',
                            data: {!! json_encode($transactionsByMonth->pluck('in')) !!},
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Keluar',
                            data: {!! json_encode($transactionsByMonth->pluck('out')) !!},
                            borderColor: 'rgba(255, 99, 132, 1)',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection
