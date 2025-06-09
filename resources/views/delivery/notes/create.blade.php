@extends('layouts.delivery')

@section('title', 'Buat Surat Jalan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('delivery.notes.store', $plan) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Buat Surat Jalan</h5>
                            <a href="{{ route('delivery.plans.show', $plan) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Expedition Info -->
                            <div class="col-md-12">
                                <label class="form-label required">Ekspedisi</label>
                                <input type="text" name="expedition"
                                    class="form-control @error('expedition') is-invalid @enderror"
                                    value="{{ old('expedition') }}" required>
                                @error('expedition')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required">Jenis Kendaraan</label>
                                <input type="text" name="vehicle_type"
                                    class="form-control @error('vehicle_type') is-invalid @enderror"
                                    value="{{ old('vehicle_type') }}" required>
                                @error('vehicle_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required">Nomor Polisi</label>
                                <input type="text" name="vehicle_license_plate"
                                    class="form-control @error('vehicle_license_plate') is-invalid @enderror"
                                    value="{{ old('vehicle_license_plate') }}" required>
                                @error('vehicle_license_plate')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Dates -->
                            <div class="col-md-6">
                                <label class="form-label required">Tanggal Keberangkatan</label>
                                <input type="date" name="departure_date"
                                    class="form-control @error('departure_date') is-invalid @enderror"
                                    value="{{ old('departure_date') }}" required>
                                @error('departure_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required">Estimasi Kedatangan</label>
                                <input type="date" name="estimated_arrival_date"
                                    class="form-control @error('estimated_arrival_date') is-invalid @enderror"
                                    value="{{ old('estimated_arrival_date') }}" required>
                                @error('estimated_arrival_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Required Documents -->
                            <div class="col-12">
                                <h6 class="mb-3">Dokumen yang Diperlukan</h6>
                                <div class="row g-3">
                                    @foreach(['stnk' => 'STNK',
                                    'license_plate' => 'Plat Nomor',
                                    'vehicle' => 'Kendaraan',
                                    'driver_license' => 'SIM Driver',
                                    'driver_id' => 'KTP Driver',
                                    'loading' => 'Proses Muat'] as $key => $label)
                                    <div class="col-md-6">
                                        <label class="form-label required">Foto {{ $label }}</label>
                                        <input type="file" name="document_files[{{ $key }}]"
                                            class="form-control @error('document_files.'.$key) is-invalid @enderror"
                                            accept="image/*" required>
                                        @error('document_files.'.$key)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Buat Surat Jalan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Plan Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Detail Rencana</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">No. Rencana</dt>
                        <dd class="col-sm-8">{{ $plan->plan_number }}</dd>

                        <dt class="col-sm-4">Tujuan</dt>
                        <dd class="col-sm-8">{{ $plan->destination }}</dd>

                        <dt class="col-sm-4">Kendaraan</dt>
                        <dd class="col-sm-8">{{ $plan->vehicle_count }} {{ Str::ucfirst($plan->vehicle_type) }}</dd>
                    </dl>
                </div>
            </div>

            <!-- Items List -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Item Pengiriman</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($plan->draftItems as $item)
                                <tr>
                                    <td>{{ $item->item_name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->unit }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .required::after {
        content: " *";
        color: red;
    }

    .is-invalid {
        border-color: #dc3545;
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
@endsection