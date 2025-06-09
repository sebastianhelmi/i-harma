@extends('layouts.delivery')

@section('title', 'Tambah Packing')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Tambah Data Packing</h5>
                <a href="{{ route('delivery.plans.show', $plan) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('delivery.plans.packings.store', $plan) }}" method="POST">
                @csrf
                <div class="row g-3">
                    <!-- Packing Type -->
                    <div class="col-md-6">
                        <label class="form-label required">Jenis Packing</label>
                        <select name="packing_type" class="form-select @error('packing_type') is-invalid @enderror"
                            required>
                            <option value="">Pilih Jenis</option>
                            @foreach($types as $value => $label)
                            <option value="{{ $value }}" {{ old('packing_type')==$value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                        @error('packing_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Packing Category -->
                    <div class="col-md-6">
                        <label class="form-label required">Kategori</label>
                        <select name="packing_category"
                            class="form-select @error('packing_category') is-invalid @enderror" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $value => $label)
                            <option value="{{ $value }}" {{ old('packing_category')==$value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                        @error('packing_category')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Dimensions -->
                    <div class="col-md-12">
                        <label class="form-label required">Dimensi (P x L x T)</label>
                        <input type="text" name="packing_dimensions"
                            class="form-control @error('packing_dimensions') is-invalid @enderror"
                            placeholder="Contoh: 100x50x30 cm" value="{{ old('packing_dimensions') }}" required>
                        @error('packing_dimensions')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="col-md-12">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror"
                            rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .required::after {
        content: " *";
        color: red;
    }
</style>
@endpush
@endsection