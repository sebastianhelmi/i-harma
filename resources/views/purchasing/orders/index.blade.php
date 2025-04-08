@extends('layouts.purchasing')

@section('title', 'Purchase Orders')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('purchasing.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Purchase Orders</li>
@endsection

@section('content')
    <div class="purchase-orders">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">📋 Purchase Orders</h1>
                <p class="text-muted">Manage purchase orders and process SPB requests</p>
            </div>
            <button class="btn btn-teal" data-bs-toggle="modal" data-bs-target="#createPOModal">
                <i class="icon" data-lucide="plus"></i>
                Create Manual PO
            </button>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-4" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#spb-tab">
                    <i class="icon" data-lucide="clipboard-list"></i>
                    SPB Ready to Process
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#po-tab">
                    <i class="icon" data-lucide="shopping-cart"></i>
                    Purchase Orders
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- SPB Tab -->
            <div class="tab-pane fade show active" id="spb-tab">
                <div class="card">
                    <div class="card-body">
                        <!-- Filters -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="search-box">
                                    <i class="icon" data-lucide="search"></i>
                                    <input type="text" class="form-control" placeholder="Search SPB number...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select">
                                    <option value="">All Categories</option>
                                    <option value="site">Site</option>
                                    <option value="workshop">Workshop</option>
                                </select>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>SPB Number</th>
                                        <th>Date</th>
                                        <th>Project</th>
                                        <th>Items</th>
                                        <th>Requested By</th>
                                        <th>Category</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>SPB-00123</td>
                                        <td>Apr 6, 2025</td>
                                        <td>Tower A</td>
                                        <td><span class="badge bg-info">3 Items</span></td>
                                        <td>Budi</td>
                                        <td><span class="badge bg-secondary">Site</span></td>
                                        <td>
                                            <button class="btn btn-light" title="View SPB" data-bs-toggle="modal"
                                                data-bs-target="#viewSpbModal">
                                                <i data-lucide="eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-teal">
                                                <i data-lucide="plus"></i> Create PO
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <nav class="mt-4">
                            <ul class="pagination justify-content-center">
                                <!-- pagination items -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- PO Tab -->
            <div class="tab-pane fade" id="po-tab">
                <div class="card">
                    <div class="card-body">
                        <!-- Filters -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="search-box">
                                    <i class="icon" data-lucide="search"></i>
                                    <input type="text" class="form-control" placeholder="Search PO number...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select">
                                    <option value="">All Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>PO Number</th>
                                        <th>SPB Number</th>
                                        <th>Date</th>
                                        <th>Project</th>
                                        <th>Supplier</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>PO-00021</td>
                                        <td>SPB-00123</td>
                                        <td>Apr 6, 2025</td>
                                        <td>Tower A</td>
                                        <td>PT. Karya Abadi</td>
                                        <td>Rp 12,500,000</td>
                                        <td><span class="badge bg-warning">Pending</span></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-light" title="View">
                                                    <i data-lucide="eye"></i>
                                                </button>
                                                <button class="btn btn-light" title="Download PDF">
                                                    <i data-lucide="download"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <nav class="mt-4">
                            <ul class="pagination justify-content-center">
                                <!-- pagination items -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal for viewing SPB -->
    <div class="modal fade" id="viewSpbModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">SPB Details - SPB-00123</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- SPB Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th class="ps-0">SPB Number</th>
                                    <td>SPB-00123</td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Project</th>
                                    <td>Tower A</td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Category</th>
                                    <td>Site</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th class="ps-0">Date</th>
                                    <td>Apr 6, 2025</td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Requested By</th>
                                    <td>Electrical</td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Status</th>
                                    <td><span class="badge bg-success">Approved</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <h6 class="mb-3">Requested Items</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Unit</th>
                                    <th>Quantity</th>
                                    <th>Information</th>
                                    <th>Check</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Semen Portland</td>
                                    <td>Sak</td>
                                    <td>50</td>
                                    <td>For foundation work</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">Cek item</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Besi Beton 12mm</td>
                                    <td>Batang</td>
                                    <td>100</td>
                                    <td>Column reinforcement</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">Cek item</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Notes -->
                    <div class="mt-4">
                        <h6 class="mb-2">Additional Notes</h6>
                        <p class="text-muted mb-0">
                            Delivery needed by next week for ongoing foundation work.
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-teal">
                        <i data-lucide="plus"></i> Create PO from SPB
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Create PO Modal -->
    <div class="modal fade" id="createPOModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Purchase Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createPOForm">
                        <!-- Header Information -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title mb-3">PO Information</h6>
                                        <div class="mb-3">
                                            <label class="form-label">PO Number</label>
                                            <input type="text" class="form-control" value="PO-{{ date('Ymd') }}-001"
                                                readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Order Date</label>
                                            <input type="date" class="form-control" value="{{ date('Y-m-d') }}"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Estimated Usage Date</label>
                                            <input type="date" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Supplier</label>
                                            <select class="form-select" required>
                                                <option value="">Select Supplier</option>
                                                <option value="1">PT. Karya Abadi</option>
                                                <option value="2">CV. Baja Makmur</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Shipping Address</label>
                                            <select class="form-select mb-2" id="addressType">
                                                <option value="site">Site Address</option>
                                                <option value="warehouse">Warehouse Address</option>
                                                <option value="custom">Custom Address</option>
                                            </select>
                                            <textarea class="form-control" id="shippingAddress" rows="3" required
                                                placeholder="Enter complete shipping address..."></textarea>
                                            <small class="text-muted">Include any specific delivery instructions if
                                                needed</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title mb-3">SPB Reference</h6>
                                        <div class="mb-3">
                                            <label class="form-label">SPB Number</label>
                                            <input type="text" class="form-control" value="SPB-00123" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Project</label>
                                            <input type="text" class="form-control" value="Tower A" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Requested By</label>
                                            <input type="text" class="form-control" value="Electrical" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Items Table -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h6 class="card-title mb-3">Order Items</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th style="width: 40px">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="checkAll">
                                                    </div>
                                                </th>
                                                <th>Item Name</th>
                                                <th>Unit</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Total Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input item-check" type="checkbox">
                                                    </div>
                                                </td>
                                                <td>Semen Portland</td>
                                                <td>Sak</td>
                                                <td>
                                                    <input type="number" class="form-control form-control-sm quantity"
                                                        value="50" style="width: 80px">
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control form-control-sm price"
                                                        placeholder="Enter price" style="width: 120px">
                                                </td>
                                                <td class="total-price">Rp 0</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input item-check" type="checkbox">
                                                    </div>
                                                </td>
                                                <td>Besi Beton 12mm</td>
                                                <td>Batang</td>
                                                <td>
                                                    <input type="number" class="form-control form-control-sm quantity"
                                                        value="100" style="width: 80px">
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control form-control-sm price"
                                                        placeholder="Enter price" style="width: 120px">
                                                </td>
                                                <td class="total-price">Rp 0</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5" class="text-end fw-bold">Grand Total:</td>
                                                <td class="fw-bold grand-total">Rp 0</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title mb-3">Additional Notes</h6>
                                <textarea class="form-control" rows="3" placeholder="Enter any additional notes..."></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="createPOForm" class="btn btn-teal">
                        <i class="icon" data-lucide="save"></i> Save Purchase Order
                    </button>
                </div>
            </div>
        </div>
    </div>
    @push('styles')
        <style>
            .purchase-orders {
                .nav-tabs {
                    border-bottom-color: var(--teal-200);

                    .nav-link {
                        color: var(--gray-600);
                        border: none;
                        padding: 1rem 1.5rem;

                        &:hover {
                            color: var(--teal-600);
                        }

                        &.active {
                            color: var(--teal-600);
                            border-bottom: 2px solid var(--teal-600);
                        }

                        .icon {
                            width: 18px;
                            height: 18px;
                            margin-right: 0.5rem;
                        }
                    }
                }

                .table {
                    th {
                        font-weight: 500;
                        color: var(--gray-800);
                    }

                    td {
                        vertical-align: middle;
                    }

                    .badge {
                        font-weight: 500;
                        padding: 0.4rem 0.8rem;
                    }

                    .btn-group {
                        .btn {
                            padding: 0.25rem 0.5rem;

                            .icon {
                                width: 16px;
                                height: 16px;
                            }
                        }
                    }
                }
            }

            .badge {
                &.bg-pending {
                    background-color: $warning;
                    color: $gray-900;
                }

                &.bg-approved {
                    background-color: $success;
                    color: white;
                }

                &.bg-rejected {
                    background-color: $danger;
                    color: white;
                }

                &.bg-completed {
                    background-color: $info;
                    color: white;
                }
            }

            .table-responsive {
                border-radius: 0.5rem;

                .table {
                    margin-bottom: 0;

                    thead th {
                        background-color: $gray-50;
                        border-bottom: 1px solid $gray-200;
                    }
                }
            }

            .search-box {
                position: relative;

                .icon {
                    position: absolute;
                    left: 1rem;
                    top: 50%;
                    transform: translateY(-50%);
                    width: 20px;
                    height: 20px;
                    color: $gray-500;
                }

                input {
                    padding-left: 2.5rem;
                }
            }

            .form-check {
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0;
            }

            .form-check-input {
                margin: 0;
                cursor: pointer;
            }

            .icon-sm {
                width: 16px;
                height: 16px;
            }

            .table-sm td {
                vertical-align: middle;
            }

            .modal-xl {
                max-width: 1140px;
            }

            .form-control:read-only {
                background-color: var(--gray-50);
            }

            .card {
                border: 1px solid var(--gray-200);
                border-radius: 0.5rem;

                .card-title {
                    color: var(--gray-800);
                    font-weight: 600;
                }
            }

            .table {
                .form-control-sm {
                    padding: 0.25rem 0.5rem;
                    font-size: 0.875rem;
                }
            }

            .total-price,
            .grand-total {
                font-family: monospace;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Handle "Check All" functionality
                const checkAll = document.getElementById('checkAll');
                const itemChecks = document.querySelectorAll('.item-check');

                if (checkAll) {
                    checkAll.addEventListener('change', function() {
                        itemChecks.forEach(check => {
                            check.checked = this.checked;
                        });
                    });
                }

                // Handle price calculations
                const calculateTotals = () => {
                    const rows = document.querySelectorAll('tbody tr');
                    let grandTotal = 0;

                    rows.forEach(row => {
                        const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
                        const price = parseFloat(row.querySelector('.price').value) || 0;
                        const total = quantity * price;

                        row.querySelector('.total-price').textContent =
                            `Rp ${total.toLocaleString('id-ID')}`;
                        grandTotal += total;
                    });

                    document.querySelector('.grand-total').textContent =
                        `Rp ${grandTotal.toLocaleString('id-ID')}`;
                };

                // Add event listeners for price inputs
                document.querySelectorAll('.quantity, .price').forEach(input => {
                    input.addEventListener('input', calculateTotals);
                });

                // Handle form submission
                const createPOForm = document.getElementById('createPOForm');
                if (createPOForm) {
                    createPOForm.addEventListener('submit', function(e) {
                        e.preventDefault();

                        // Validate that at least one item is selected
                        const selectedItems = document.querySelectorAll('.item-check:checked');
                        if (selectedItems.length === 0) {
                            alert('Please select at least one item for the purchase order.');
                            return;
                        }

                        // Add your form submission logic here
                        console.log('Form submitted');
                    });
                }
            });
        </script>
    @endpush
@endsection
