@extends('layouts.admin')

@section('title', 'Inventory Barang')

@section('page-title')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-box-open fa-lg text-primary"></i>
        <span>Inventory Barang</span>
    </div>
@endsection

@section('page-subtitle')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">Inventori</li>
            <li class="breadcrumb-item active">Stok</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <div class="col-sm-4">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-primary-soft me-3">
                                <i class="fas fa-boxes text-primary"></i>
                            </div>
                            <div>
                                <h3 class="mb-0">{{ $stats['total_items'] }}</h3>
                                <p class="text-muted mb-0">Total Item</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-warning-soft me-3">
                                <i class="fas fa-exclamation-triangle text-warning"></i>
                            </div>
                            <div>
                                <h3 class="mb-0">{{ $stats['low_stock'] }}</h3>
                                <p class="text-muted mb-0">Stok Menipis</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-danger-soft me-3">
                                <i class="fas fa-times-circle text-danger"></i>
                            </div>
                            <div>
                                <h3 class="mb-0">{{ $stats['out_of_stock'] }}</h3>
                                <p class="text-muted mb-0">Stok Habis</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card mb-4">
            <div class="card-body">
                <form id="filterForm" action="{{ route('admin.inventory.index') }}" method="GET">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control" name="search"
                                    placeholder="Cari nama item atau kode" value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="category">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $id => $name)
                                    <option value="{{ $id }}" {{ request('category') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" name="status">
                                <option value="">Semua Status</option>
                                <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>
                                    Tersedia
                                </option>
                                <option value="low" {{ request('status') == 'low' ? 'selected' : '' }}>
                                    Stok Menipis
                                </option>
                                <option value="out" {{ request('status') == 'out' ? 'selected' : '' }}>
                                    Stok Habis
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3 text-md-end">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#addItemModal">
                                <i class="fas fa-plus me-2"></i>Tambah Item
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Inventory Table -->
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Item</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Satuan</th>
                                <th>Status</th>
                                <th style="width: 100px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inventories as $inventory)
                                <tr>
                                    <td>
                                        <span class="fw-medium">{{ str_pad($inventory->id, 3, '0', STR_PAD_LEFT) }}</span>
                                    </td>
                                    <td>{{ $inventory->item_name }}</td>
                                    <td>{{ $inventory->itemCategory->name }}</td>
                                    <td>{{ $inventory->quantity }}</td>
                                    <td>{{ $inventory->unit }}</td>
                                    <td>
                                        @if ($inventory->quantity > 10)
                                            <span class="badge bg-success">Tersedia</span>
                                        @elseif($inventory->quantity > 0)
                                            <span class="badge bg-warning">Stok Menipis</span>
                                        @else
                                            <span class="badge bg-danger">Stok Habis</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                onclick="editItem({{ $inventory->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="deleteItem({{ $inventory->id }}, '{{ $inventory->item_name }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-box fa-2x mb-3 d-block"></i>
                                            <p class="mb-0">Belum ada item</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if ($inventories->hasPages())
                <div class="card-footer border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Menampilkan {{ $inventories->firstItem() }}-{{ $inventories->lastItem() }} dari
                            {{ $inventories->total() }} data
                        </div>
                        {{ $inventories->links() }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Mobile View Cards -->
        <div class="d-md-none mt-4">
            @forelse($inventories as $inventory)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="card-title mb-0">{{ $inventory->item_name }}</h6>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#"
                                            onclick="editItem({{ $inventory->id }})">
                                            <i class="fas fa-edit me-2"></i>Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#"
                                            onclick="deleteItem({{ $inventory->id }}, '{{ $inventory->item_name }}')">
                                            <i class="fas fa-trash me-2"></i>Hapus
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <p class="card-text">
                            <small class="text-muted">Kode: {{ str_pad($inventory->id, 3, '0', STR_PAD_LEFT) }}</small>
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-0">Stok: {{ $inventory->quantity }} {{ $inventory->unit }}</p>
                                <small class="text-muted">{{ $inventory->itemCategory->name }}</small>
                            </div>
                            @if ($inventory->quantity > 10)
                                <span class="badge bg-success">Tersedia</span>
                            @elseif($inventory->quantity > 0)
                                <span class="badge bg-warning">Stok Menipis</span>
                            @else
                                <span class="badge bg-danger">Stok Habis</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-box fa-2x mb-3"></i>
                    <p class="mb-0">Belum ada item</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Add/Edit Item Modal -->
    <x-admin.inventory.create-modal :categories="$categories" />
@endsection

@push('scripts')
    <script>
        function resetFilters() {
            window.location.href = "{{ route('admin.inventory.index') }}";
        }

        function openAddModal() {
            const form = document.getElementById('itemForm');
            const modal = document.getElementById('addItemModal');

            // Reset form
            form.reset();

            // Remove method if exists
            const methodInput = form.querySelector('input[name="_method"]');
            if (methodInput) methodInput.remove();

            // Reset action
            form.action = "{{ route('admin.inventory.store') }}";

            // Reset title
            modal.querySelector('.modal-title').textContent = 'Tambah Item';
        }

        async function editItem(id) {
            try {
                const response = await fetch(`{{ url('admin/inventory') }}/${id}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const item = await response.json();

                const form = document.getElementById('itemForm');
                const modal = document.getElementById('addItemModal');

                // Update form action and method
                form.action = `{{ url('admin/inventory') }}/${id}`;
                const methodInput = form.querySelector('input[name="_method"]') || document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                if (!form.querySelector('input[name="_method"]')) {
                    form.appendChild(methodInput);
                }

                // Fill form fields
                form.querySelector('[name="item_name"]').value = item.item_name;
                form.querySelector('[name="item_category_id"]').value = item.item_category_id;
                form.querySelector('[name="quantity"]').value = item.quantity;
                form.querySelector('[name="unit"]').value = item.unit;
                form.querySelector('[name="unit_price"]').value = item.unit_price || '';

                // Update modal title
                modal.querySelector('.modal-title').textContent = 'Edit Item';

                // Show modal
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan saat memuat data item'
                });
            }
        }

        function deleteItem(id, name) {
            Swal.fire({
                title: 'Hapus Item?',
                html: `Anda yakin ingin menghapus item <strong>${name}</strong>?<br>
               <small class="text-danger">Tindakan ini tidak dapat dibatalkan!</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`{{ url('admin/inventory') }}/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.message) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    window.location.reload();
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Terjadi kesalahan saat menghapus item'
                            });
                        });
                }
            });
        }

        // Form submission handling
        document.getElementById('itemForm')?.addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);
            const isEdit = form.querySelector('input[name="_method"]')?.value === 'PUT';

            fetch(form.action, {
                    method: isEdit ? 'POST' : form.method,
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        // Close modal
                        bootstrap.Modal.getInstance(document.getElementById('addItemModal')).hide();

                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (error.response?.status === 422) {
                        // Validation errors
                        error.response.json().then(data => {
                            Object.keys(data.errors).forEach(field => {
                                const input = form.querySelector(`[name="${field}"]`);
                                if (input) {
                                    input.classList.add('is-invalid');
                                    const feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    feedback.textContent = data.errors[field][0];
                                    input.parentNode.appendChild(feedback);
                                }
                            });
                        });
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan saat menyimpan data'
                    });
                });
        });

        // Filter form handling
        document.getElementById('filterForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            window.location.href = `${form.action}?${params.toString()}`;
        });

        // Handle filter changes
        document.querySelectorAll('#filterForm select').forEach(select => {
            select.addEventListener('change', () => {
                document.getElementById('filterForm').submit();
            });
        });

        // Auto submit search after typing delay
        let searchTimeout;
        document.querySelector('#filterForm input[name="search"]')?.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('filterForm').submit();
            }, 500);
        });

        // Initialize modals
        document.addEventListener('DOMContentLoaded', function() {
            // Reset form when modal is hidden
            document.getElementById('addItemModal')?.addEventListener('hidden.bs.modal', function() {
                const form = document.getElementById('itemForm');
                form.reset();
                form.querySelectorAll('.is-invalid').forEach(el => {
                    el.classList.remove('is-invalid');
                });
                form.querySelectorAll('.invalid-feedback').forEach(el => {
                    el.remove();
                });
            });

            // Initialize add button
            document.querySelector('[data-bs-target="#addItemModal"]')?.addEventListener('click', openAddModal);
        });
    </script>
@endpush

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

        .bg-warning-soft {
            background-color: rgba(245, 158, 11, 0.1);
        }

        .bg-danger-soft {
            background-color: rgba(239, 68, 68, 0.1);
        }

        @media (max-width: 768px) {
            .table-responsive {
                display: none;
            }
        }
    </style>
@endpush
