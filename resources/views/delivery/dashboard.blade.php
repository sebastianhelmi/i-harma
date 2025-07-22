@extends('layouts.delivery')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

        <!-- Summary Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Rencana Pengiriman
                                    (Siap Kirim)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingPlans }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Packing Hari Ini</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $packingToday }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-box-open fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Sedang Dikirim Hari
                                    Ini</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $deliveringToday }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-truck fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Selesai Bulan Ini
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $completedThisMonth }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Activities -->
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Aktivitas Terbaru</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @forelse($recentActivities as $activity)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $activity->plan_number }}</strong> - {{ $activity->status }}
                                        <small
                                            class="d-block text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                    </div>
                                    <a href="{{ route('delivery.plans.show', $activity) }}"
                                        class="btn btn-sm btn-outline-primary">Detail</a>
                                </li>
                            @empty
                                <li class="list-group-item">Tidak ada aktivitas terbaru.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Delivery on Progress -->
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Pengiriman Berjalan</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No. Surat Jalan</th>
                                        <th>Proyek</th>
                                        <th>Ekspedisi</th>
                                        <th>Tgl. Kirim</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($onProgress as $delivery)
                                        <tr>
                                            <td>{{ $delivery->delivery_note_number }}</td>
                                            <td>{{ $delivery->deliveryPlan->project->name ?? 'N/A' }}</td>
                                            <td>{{ $delivery->expedition }}
                                            </td>
                                            <td>{{ $delivery->departure_date ? $delivery->departure_date->format('d M Y') : 'N/A' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada pengiriman yang sedang
                                                berjalan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
