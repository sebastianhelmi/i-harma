@extends('layouts.inventory')

@section('title', 'Edit Item')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Edit Item</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('inventory.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('inventory.items.index') }}">Inventory</a></li>
                        <li class="breadcrumb-item active">Edit Item</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('inventory.items.update', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="item_name" class="form-label required">Nama Item</label>
                                <input type="text" class="form-control @error('item_name') is-invalid @enderror"
                                    id="item_name" name="item_name" value="{{ old('item_name', $item->item_name) }}" required>
                                @error('item_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="item_category_id" class="form-label required">Kategori</label>
                                <select class="form-select @error('item_category_id') is-invalid @enderror"
                                    id="item_category_id" name="item_category_id" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($categories as $id => $name)
                                        <option value="{{ $id }}" @selected(old('item_category_id', $item->item_category_id) == $id)>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('item_category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                             <div class="mb-3">
                                <label for="category" class="form-label">Sub Kategori</label>
                                <input type="text" class="form-control @error('category') is-invalid @enderror"
                                    id="category" name="category" value="{{ old('category', $item->category) }}">
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="unit" class="form-label required">Satuan</label>
                                <input type="text" class="form-control @error('unit') is-invalid @enderror"
                                    id="unit" name="unit" value="{{ old('unit', $item->unit) }}" required>
                                @error('unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="unit_price" class="form-label">Harga Satuan</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('unit_price') is-invalid @enderror"
                                        id="unit_price" name="unit_price" value="{{ old('unit_price', $item->unit_price) }}">
                                </div>
                                @error('unit_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                             <div class="mb-3">
                                <label for="weight" class="form-label">Berat (kg)</label>
                                <input type="number" step="0.01" class="form-control @error('weight') is-invalid @enderror"
                                    id="weight" name="weight" value="{{ old('weight', $item->weight) }}">
                                @error('weight')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('inventory.items.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection