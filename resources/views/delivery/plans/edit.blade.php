@extends('layouts.delivery')

@section('title', 'Edit Rencana Pengiriman')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Edit Rencana Pengiriman</h5>
                <a href="{{ route('delivery.plans.show', $plan) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('delivery.plans.update', $plan) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Plan Number (Read Only) -->
                    <div class="col-md-6">
                        <label class="form-label text-muted">Nomor Rencana</label>
                        <p class="fw-bold mb-0">{{ $plan->plan_number }}</p>
                    </div>

                    <!-- Status (Read Only) -->
                    <div class="col-md-6">
                        <label class="form-label text-muted">Status</label>
                        <br>
                        <span class="badge bg-{{ $plan->getStatusBadgeClass() }}">
                            {{ $plan->getStatusLabel() }}
                        </span>
                    </div>

                    <!-- Destination -->
                    <div class="col-md-12">
                        <label class="form-label required">Tujuan Pengiriman</label>
                        <input type="text" name="destination"
                            class="form-control @error('destination') is-invalid @enderror"
                            value="{{ old('destination', $plan->destination) }}" required>
                        @error('destination')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Planned Date -->
                    <div class="col-md-4">
                        <label class="form-label required">Tanggal Rencana</label>
                        <input type="date" name="planned_date"
                            class="form-control @error('planned_date') is-invalid @enderror"
                            value="{{ old('planned_date', $plan->planned_date->format('Y-m-d')) }}"
                            min="{{ date('Y-m-d') }}" required>
                        @error('planned_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Vehicle Type -->
                    <div class="col-md-4">
                        <label class="form-label required">Jenis Kendaraan</label>
                        <select name="vehicle_type" class="form-select @error('vehicle_type') is-invalid @enderror"
                            required>
                            <option value="">-- Pilih Jenis --</option>
                            @foreach($vehicleTypes as $value => $label)
                            <option value="{{ $value }}" {{ old('vehicle_type', $plan->vehicle_type) == $value ?
                                'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                        @error('vehicle_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Vehicle Count -->
                    <div class="col-md-4">
                        <label class="form-label required">Jumlah Kendaraan</label>
                        <input type="number" name="vehicle_count"
                            class="form-control @error('vehicle_count') is-invalid @enderror"
                            value="{{ old('vehicle_count', $plan->vehicle_count) }}" min="1" required>
                        @error('vehicle_count')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Delivery Notes -->
                    <div class="col-md-12">
                        <label class="form-label">Catatan Pengiriman</label>
                        <textarea name="delivery_notes"
                            class="form-control @error('delivery_notes') is-invalid @enderror"
                            rows="3">{{ old('delivery_notes', $plan->delivery_notes) }}</textarea>
                        @error('delivery_notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="col-12">
                        <hr>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="reset" class="btn btn-secondary">
                                <i class="fas fa-undo me-2"></i>Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>
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

    .form-label {
        font-weight: 500;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Auto-format destination to uppercase
    const destinationInput = document.querySelector('input[name="destination"]');
    if (destinationInput) {
        destinationInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    }

    // Reset form confirmation
    const form = document.querySelector('form');
    const resetBtn = document.querySelector('button[type="reset"]');

    if (resetBtn) {
        resetBtn.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Reset Form?',
                text: "Perubahan yang belum disimpan akan hilang",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Reset',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.reset();
                }
            });
        });
    }
});
</script>
@endpush
@endsection