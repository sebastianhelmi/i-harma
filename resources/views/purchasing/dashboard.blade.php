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

        <!-- Summary Cards -->
        <div class="row g-4 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-teal bg-opacity-10">
                                <i data-lucide="shopping-cart"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1">Total POs</h6>
                                <h3 class="mb-0">124</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-warning bg-opacity-10">
                                <i data-lucide="clock"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1">Pending Approval</h6>
                                <h3 class="mb-0">8</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-info bg-opacity-10">
                                <i data-lucide="loader"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1">In Progress</h6>
                                <h3 class="mb-0">15</h3>
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
                                <i data-lucide="check-circle"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1">Completed</h6>
                                <h3 class="mb-0">101</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent POs and Supplier Performance -->
        <div class="row g-4 mb-4">
            <!-- Recent POs Table -->
            <div class="col-12 col-xl-8">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Recent Purchase Orders</h5>
                        <a href="{{ route('purchasing.orders.index') }}" class="btn btn-light btn-sm">
                            View All
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>PO Number</th>
                                        <th>Supplier</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>PO-2025-023</td>
                                        <td>PT Semen Indonesia</td>
                                        <td>Apr 8, 2025</td>
                                        <td>Rp 15,000,000</td>
                                        <td><span class="badge bg-warning">Pending</span></td>
                                    </tr>
                                    <tr>
                                        <td>PO-2025-022</td>
                                        <td>CV Baja Makmur</td>
                                        <td>Apr 7, 2025</td>
                                        <td>Rp 8,500,000</td>
                                        <td><span class="badge bg-success">Completed</span></td>
                                    </tr>
                                    <tr>
                                        <td>PO-2025-021</td>
                                        <td>PT Kayu Jaya</td>
                                        <td>Apr 7, 2025</td>
                                        <td>Rp 12,300,000</td>
                                        <td><span class="badge bg-info">In Progress</span></td>
                                    </tr>
                                </tbody>
                            </table>
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
