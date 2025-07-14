@extends('layouts.delivery')

@section('title', 'Konfirmasi Pengiriman')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Konfirmasi Pengiriman</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('delivery.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('delivery.plans.index') }}">Rencana Pengiriman</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('delivery.plans.show', $plan) }}">{{ $plan->plan_number }}</a></li>
                        <li class="breadcrumb-item active">Konfirmasi</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Konfirmasi Pengiriman: {{ $plan->plan_number }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('delivery.plans.confirm.store', $plan) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="proof_of_delivery" class="form-label required">Bukti Pengiriman (Foto/PDF)</label>
                        <input type="file" class="form-control @error('proof_of_delivery') is-invalid @enderror" id="proof_of_delivery" name="proof_of_delivery" required>
                        @error('proof_of_delivery')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Format: JPG, JPEG, PNG, PDF. Max: 2MB.</small>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan Tambahan</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $plan->delivery_notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('delivery.plans.show', $plan) }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check me-1"></i>Konfirmasi & Selesaikan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
