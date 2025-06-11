@extends('layouts.delivery')

@section('title', 'Buat Rencana Pengiriman')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Buat Rencana Pengiriman</h5>
                <a href="{{ route('delivery.plans.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('delivery.plans.store') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <!-- Delivery Destination -->
                    <div class="col-md-12">
                        <label class="form-label required">Project</label>
                        <select name="project_id" class="form-select @error('project_id') is-invalid @enderror"
                            required>
                            <option value="">Pilih Project</option>
                            @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id')==$project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('project_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <label class="form-label required">Tujuan Pengiriman</label>
                        <input type="text" name="destination"
                            class="form-control @error('destination') is-invalid @enderror"
                            value="{{ old('destination') }}" placeholder="Masukkan lokasi tujuan" required>
                        @error('destination')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Planned Date -->
                    <div class="col-md-6">
                        <label class="form-label required">Tanggal Rencana</label>
                        <input type="date" name="planned_date"
                            class="form-control @error('planned_date') is-invalid @enderror"
                            value="{{ old('planned_date') }}" min="{{ date('Y-m-d') }}" required>
                        @error('planned_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Vehicle Type -->
                    <div class="col-md-3">
                        <label class="form-label required">Jenis Kendaraan</label>
                        <select name="vehicle_type" class="form-select @error('vehicle_type') is-invalid @enderror"
                            required>
                            <option value="">-- Pilih Jenis --</option>
                            @foreach($vehicleTypes as $value => $label)
                            <option value="{{ $value }}" {{ old('vehicle_type')==$value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                        @error('vehicle_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Vehicle Count -->
                    <div class="col-md-3">
                        <label class="form-label required">Jumlah Kendaraan</label>
                        <input type="number" name="vehicle_count"
                            class="form-control @error('vehicle_count') is-invalid @enderror"
                            value="{{ old('vehicle_count', 1) }}" min="1" required>
                        @error('vehicle_count')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Delivery Notes -->
                    <div class="col-md-12">
                        <label class="form-label">Catatan Pengiriman</label>
                        <textarea name="delivery_notes"
                            class="form-control @error('delivery_notes') is-invalid @enderror" rows="3"
                            placeholder="Tambahkan catatan jika diperlukan">{{ old('delivery_notes') }}</textarea>
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
                                <i class="fas fa-save me-2"></i>Simpan Rencana
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .required::after {
        content: " *";
        color: red;
    }

    .form-label {
        font-weight: 500;
    }

    .card {
        box-shadow: 0 0 10px rgba(0, 0, 0, .1);
    }

    .btn-group-toggle .btn {
        min-width: 100px;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Auto-format destination to uppercase
    const destinationInput = document.querySelector('input[name="destination"]');
    if (destinationInput) {
        destinationInput.addEventListener('input', function(e) {
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
                text: "Semua data yang telah diisi akan dihapus",
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

    // Form validation before submit
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value) {
                isValid = false;
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();
            Swal.fire({
                title: 'Periksa Kembali',
                text: 'Mohon lengkapi semua field yang wajib diisi',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
});
</script>
@endpush