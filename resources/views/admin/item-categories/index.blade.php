@extends('layouts.admin')

@section('title', 'Kategori Item')

@section('page-title')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-boxes fa-lg text-primary"></i>
        <span>Kategori Item</span>
    </div>
@endsection

@section('page-subtitle')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">Inventori</li>
            <li class="breadcrumb-item active">Kategori</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Filter & Search -->
        <div class="card mb-4">
            <div class="card-body">
                <form id="filterForm" action="{{ route('admin.item-categories.index') }}" method="GET">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" name="search" class="form-control"
                                    placeholder="Cari nama kategori..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="sort" onchange="this.form.submit()">
                                <option value="">Urutkan</option>
                                <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>A-Z</option>
                                <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Z-A</option>
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                            </select>
                        </div>
                        <div class="col-md-5 text-md-end">
                            <button type="reset" class="btn btn-light me-2" onclick="resetFilters()">
                                <i class="fas fa-redo me-2"></i>Reset
                            </button>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addCategoryModal">
                                <i class="fas fa-plus me-2"></i>Tambah Kategori
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Categories Table -->
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px">#</th>
                                <th>Nama Kategori</th>
                                <th>Deskripsi</th>
                                <th style="width: 120px">Jumlah Item</th>
                                <th style="width: 100px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="fw-medium">{{ $category->name }}</span>
                                    </td>
                                    <td>{{ $category->description }}</td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary">
                                            {{ $category->items_count ?? 0 }} item
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                onclick="editCategory({{ $category->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="deleteCategory({{ $category->id }}, '{{ $category->name }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-boxes fa-2x mb-3 d-block"></i>
                                            <p class="mb-0">Belum ada kategori</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if ($categories->hasPages())
                <div class="card-footer border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Menampilkan {{ $categories->firstItem() }}-{{ $categories->lastItem() }} dari
                            {{ $categories->total() }} data
                        </div>
                        {{ $categories->links() }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Mobile View Cards -->
        <div class="d-md-none mt-4">
            @forelse($categories as $category)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="card-title mb-0">{{ $category->name }}</h6>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                    onclick="editCategory({{ $category->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    onclick="deleteCategory({{ $category->id }}, '{{ $category->name }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <p class="card-text text-muted small mb-2">{{ $category->description }}</p>
                        <span class="badge bg-primary-subtle text-primary">
                            {{ $category->items_count ?? 0 }} item
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-boxes fa-2x mb-3"></i>
                    <p class="mb-0">Belum ada kategori</p>
                </div>
            @endforelse

            <!-- Mobile Pagination -->
            @if ($categories->hasPages())
                <div class="mt-3">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>

    </div>

    <!-- Add/Edit Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="categoryForm" method="POST" action="{{ route('admin.item-categories.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required minlength="3"
                                placeholder="Masukkan nama kategori">
                            <div class="form-text">Minimal 3 karakter</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Masukkan deskripsi kategori"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Batal
                    </button>
                    <button type="submit" form="categoryForm" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function resetFilters() {
            window.location.href = "{{ route('admin.item-categories.index') }}";
        }

        function editCategory(id) {
            fetch(`{{ url('admin/item-categories') }}/${id}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(category => {
                    const form = document.getElementById('categoryForm');
                    const modal = document.getElementById('addCategoryModal');

                    // Update form action and method
                    form.action = `{{ url('admin/item-categories') }}/${id}`;
                    const methodInput = form.querySelector('input[name="_method"]') || document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'PUT';
                    if (!form.querySelector('input[name="_method"]')) {
                        form.appendChild(methodInput);
                    }

                    // Fill form fields
                    form.querySelector('[name="name"]').value = category.name;
                    form.querySelector('[name="description"]').value = category.description;

                    // Update modal title
                    modal.querySelector('.modal-title').textContent = 'Edit Kategori';

                    // Show modal
                    const bsModal = new bootstrap.Modal(modal);
                    bsModal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan saat memuat data kategori'
                    });
                });
        }

        function deleteCategory(id, name) {
            Swal.fire({
                title: 'Hapus Kategori?',
                html: `Anda yakin ingin menghapus kategori <strong>${name}</strong>?<br>
               <small class="text-danger">Tindakan ini tidak dapat dibatalkan!</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`{{ url('admin/item-categories') }}/${id}`, {
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
                                text: 'Terjadi kesalahan saat menghapus kategori'
                            });
                        });
                }
            });
        }

        // Form submission handling
        document.getElementById('categoryForm').addEventListener('submit', function(e) {
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
                        bootstrap.Modal.getInstance(document.getElementById('addCategoryModal')).hide();

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
    </script>
@endpush

@push('styles')
    <style>
        @media (max-width: 768px) {
            .table-responsive {
                display: none;
            }
        }

        .bg-primary-subtle {
            background-color: rgba(59, 130, 246, 0.1);
        }

        .badge {
            font-weight: 500;
            padding: 0.5em 1em;
        }
    </style>
@endpush
