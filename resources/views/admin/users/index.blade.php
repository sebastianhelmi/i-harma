@extends('layouts.admin')

@section('title', 'Manajemen User')

@section('page-title')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-users fa-lg text-primary"></i>
        <span>Manajemen User</span>
    </div>
@endsection

@section('page-subtitle')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">User</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- User Stats -->
        <div class="row g-4 mb-4">
            <div class="col-sm-4">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-primary-soft me-3">
                                <i class="fas fa-users text-primary"></i>
                            </div>
                            <div>
                                <h3 class="mb-0">{{ $stats['total'] }}</h3>
                                <p class="text-muted mb-0">Total User</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-success-soft me-3">
                                <i class="fas fa-check-circle text-success"></i>
                            </div>
                            <div>
                                <h3 class="mb-0">{{ $stats['active'] }}</h3>
                                <p class="text-muted mb-0">User Aktif</p>
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
                                <h3 class="mb-0">{{ $stats['inactive'] }}</h3>
                                <p class="text-muted mb-0">User Tidak Aktif</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters & Actions -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('admin.users.index') }}" method="GET" id="filterForm">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" name="search" class="form-control" placeholder="Cari user..."
                                    value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" name="role" onchange="this.form.submit()">
                                <option value="">Semua Role</option>
                                @foreach ($roles as $value => $label)
                                    <option value="{{ $value }}" {{ request('role') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" name="status" onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Tidak Aktif
                                </option>
                            </select>
                        </div>
                        <div class="col-md-5 text-md-end">
                            <button type="reset" class="btn btn-light me-2" onclick="resetFilters()">
                                <i class="fas fa-redo me-2"></i>Reset
                            </button>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addUserModal">
                                <i class="fas fa-plus me-2"></i>Tambah User
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Users Table -->
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                    </div>
                                </th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="selected_users[]"
                                                value="{{ $user->id }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}"
                                                class="rounded-circle me-2" width="32" height="32"
                                                alt="{{ $user->name }}">
                                            <div>
                                                <div class="fw-medium">{{ $user->name }}</div>
                                                <small class="text-muted">Dibuat:
                                                    {{ $user->created_at->format('d M Y') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $user->getRoleBadgeClass() }}">
                                            <i class="fas {{ $user->getRoleIcon() }} me-1"></i>
                                            {{ $user->getRoleLabel() }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                            {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                data-bs-target="#editUserModal" data-user="{{ $user->id }}"
                                                onclick="prepareEdit({{ $user->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="confirmDelete('{{ $user->id }}', '{{ $user->name }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-users fa-2x mb-3 d-block"></i>
                                            <p class="mb-0">Belum ada data user</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if ($users->hasPages())
                <div class="card-footer border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Menampilkan {{ $users->firstItem() }}-{{ $users->lastItem() }} dari {{ $users->total() }}
                            data
                        </div>
                        {{ $users->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Add User Modal -->
    <x-admin.user-management.create-user-modal :roles="$roles" />
    <x-admin.user-management.edit-user-modal :roles="$roles" />
@endsection

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

        .bg-success-soft {
            background-color: rgba(16, 185, 129, 0.1);
        }

        .bg-danger-soft {
            background-color: rgba(239, 68, 68, 0.1);
        }

        .table> :not(caption)>*>* {
            padding: 1rem 0.75rem;
        }

        .badge {
            padding: 0.5em 1em;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .modal-dialog {
                margin: 0.5rem;
                max-width: none;
            }
        }


        .is-invalid {
            border-color: #dc3545 !important;
        }

        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function confirmDelete(userId, userName) {
            Swal.fire({
                title: 'Hapus User?',
                html: `Anda yakin ingin menghapus user <strong>${userName}</strong>?<br>
               <small class="text-danger">Tindakan ini tidak dapat dibatalkan!</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create form and submit
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ route('admin.users.index') }}/${userId}`;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';

                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';

                    form.appendChild(csrfToken);
                    form.appendChild(method);

                    document.body.appendChild(form);

                    // Submit form
                    fetch(form.action, {
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
                                text: 'Terjadi kesalahan saat menghapus user',
                            });
                        });
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize select all checkbox
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('tbody .form-check-input');

            selectAll.addEventListener('change', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        });


        function resetFilters() {
            window.location.href = "{{ route('admin.users.index') }}";
        }

        // Auto submit form on filter change
        document.querySelectorAll('#filterForm select').forEach(select => {
            select.addEventListener('change', () => {
                document.getElementById('filterForm').submit();
            });
        });

        // Submit form on search after delay
        let searchTimeout;
        document.querySelector('input[name="search"]').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('filterForm').submit();
            }, 500);
        });
    </script>
@endpush
