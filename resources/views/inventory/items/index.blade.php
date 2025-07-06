@extends('layouts.inventory')

@section('title', 'Manajemen Inventory')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Manajemen Inventory</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('inventory.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Inventory</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('inventory.items.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Item
            </a>
        </div>

        <!-- Search & Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('inventory.items.index') }}" method="GET" class="row g-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                                placeholder="Cari item...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="category_id" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $id => $name)
                                <option value="{{ $id }}" @selected(request('category_id') == $id)>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary w-100">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Items Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Nama Item</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Satuan</th>
                                <th>Harga Satuan</th>
                                <th>Terakhir Update</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                                <tr>
                                    <td>{{ $item->item_name }}</td>
                                    <td>{{ $item->itemCategory->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $item->quantity > 0 ? 'success' : 'danger' }}">
                                            {{ $item->quantity }}
                                        </span>
                                    </td>
                                    <td>{{ $item->unit }}</td>
                                    <td>{{ $item->unit_price ? 'Rp ' . number_format($item->unit_price, 0, ',', '.') : '-' }}
                                    </td>
                                    <td>{{ $item->updated_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('inventory.items.edit', $item) }}"
                                                class="btn btn-sm btn-warning" title="Edit Item">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('inventory.items.history', $item) }}"
                                                class="btn btn-sm btn-info" title="Riwayat Transaksi">
                                                <i class="fas fa-history"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">Tidak ada item yang ditemukan</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $items->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
