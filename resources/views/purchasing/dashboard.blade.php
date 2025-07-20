@extends('layouts.purchasing')

@section('title', 'Dashboard')

@section('content')
    <div class="dashboard">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">🛍️ Purchasing Dashboard</h1>
                <p class="text-muted">Overview of purchasing activities and metrics</p>
            </div>
            <button class="btn btn-teal">
                <i class="icon" data-lucide="plus"></i>
                Create New PO
            </button>
        </div>

        <!-- Summary Stats -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <i class="fas fa-file-alt fa-2x text-primary"></i>
                            </div>
                            <div>
                                <h6 class="card-subtitle mb-1 text-muted">Total SPB Disetujui</h6>
                                <h3 class="card-title mb-0">{{ $totalSpb }}</h3>
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
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            </div>
                            <div>
                                <h6 class="card-subtitle mb-1 text-muted">PO Selesai</h6>
                                <h3 class="card-title mb-0">{{ $completedPo }}</h3>
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
                                <i class="fas fa-users fa-2x text-info"></i>
                            </div>
                            <div>
                                <h6 class="card-subtitle mb-1 text-muted">Total Supplier</h6>
                                <h3 class="card-title mb-0">{{ $suppliers }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- PO Progress and SPB Waiting -->
        <div class="row g-4">
            <!-- PO Progress -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">PO Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <div class="po-list">
                            @forelse($poProgress as $po)
                                <div class="po-item mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0">PO #{{ $po->po_number }}</h6>
                                        <span
                                            class="badge bg-{{ $po->status === 'completed' ? 'success' : ($po->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($po->status) }}
                                        </span>
                                    </div>
                                    <div class="mb-1 small text-muted">Proyek: {{ $po->spb->project->name ?? '-' }}</div>
                                    <div class="mb-1 small text-muted">Supplier: {{ $po->supplier->name ?? '-' }}</div>
                                    <div class="mb-1 small text-muted">Tanggal:
                                        {{ $po->order_date ? $po->order_date->format('d M Y') : '-' }}</div>
                                </div>
                            @empty
                                <div class="text-muted">Tidak ada PO terbaru</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <!-- SPB Waiting for PO -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">SPB Menunggu PO</h5>
                    </div>
                    <div class="card-body">
                        <div class="spb-list">
                            @forelse($spbWaitingPo as $spb)
                                <div class="spb-item p-3 mb-2 bg-light rounded">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1">SPB #{{ $spb->spb_number }}</h6>
                                        <small
                                            class="text-muted">{{ $spb->spb_date ? $spb->spb_date->format('d M') : '-' }}</small>
                                    </div>
                                    <p class="mb-1 small">Proyek: {{ $spb->project->name ?? '-' }}</p>
                                    <small class="text-muted">Diminta oleh: {{ $spb->requester->name ?? '-' }}</small>
                                </div>
                            @empty
                                <div class="text-muted">Tidak ada SPB menunggu PO</div>
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
    @push('styles')
        <style>
            .dashboard {
                .stat-card {
                    border: none;
                    border-radius: 0.75rem;
                    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);

                    .stat-icon {
                        width: 48px;
                        height: 48px;
                        border-radius: 0.75rem;
                        display: flex;
                        align-items: center;
                        justify-content: center;

                        i {
                            width: 24px;
                            height: 24px;
                            color: $teal-600;
                        }
                    }

                    h3 {
                        font-weight: 600;
                        color: $gray-800;
                    }
                }

                .table {
                    th {
                        font-weight: 500;
                        color: $gray-800;
                        border-bottom-width: 1px;
                    }

                    td {
                        vertical-align: middle;
                    }

                    .badge {
                        font-weight: 500;
                        padding: 0.4rem 0.8rem;
                    }
                }

                .supplier-rating {
                    .progress {
                        border-radius: 0.5rem;
                        background-color: $gray-100;
                    }
                }
            }

            // Button Styles
            .btn-teal {
                background-color: $teal-600;
                border-color: $teal-600;
                color: white;

                &:hover {
                    background-color: $teal-700;
                    border-color: $teal-700;
                    color: white;
                }

                .icon {
                    width: 18px;
                    height: 18px;
                    margin-right: 0.5rem;
                }
            }
        </style>
    @endpush
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize any dashboard widgets or charts here
            });
        </script>
    @endpush
@endsection
