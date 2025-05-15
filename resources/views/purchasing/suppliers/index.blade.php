@extends('layouts.purchasing')

@section('title', 'Manajemen Supplier')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Manajemen Supplier</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('purchasing.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Supplier</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('purchasing.suppliers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Supplier
            </a>
        </div>

        <!-- Search -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('purchasing.suppliers.index') }}" method="GET" class="row g-3">
                    <div class="col-md-10">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama, email, atau nomor telepon supplier...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary w-100">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Suppliers Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Nama Supplier</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Alamat</th>
                                <th>Total PO</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($suppliers as $supplier)
                                <tr>
                                    <td>{{ $supplier->name }}</td>
                                    <td>{{ $supplier->email ?? '-' }}</td>
                                    <td>{{ $supplier->phone ?? '-' }}</td>
                                    <td>{{ Str::limit($supplier->address, 50) ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $supplier->pos_count ?? 0 }} PO
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('purchasing.suppliers.edit', $supplier) }}"
                                                class="btn btn-sm btn-warning" title="Edit Supplier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('purchasing.suppliers.destroy', $supplier) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus Supplier"
                                                    onclick="return confirm('Yakin ingin menghapus supplier ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">Tidak ada supplier yang ditemukan</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $suppliers->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
