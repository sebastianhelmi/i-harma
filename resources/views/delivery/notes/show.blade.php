@extends('layouts.delivery')

@section('title', 'Detail Surat Jalan')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Detail Surat Jalan</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('delivery.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('delivery.plans.index') }}">Rencana Pengiriman</a></li>
                        <li class="breadcrumb-item active">{{ $note->delivery_note_number }}</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('delivery.notes.print', $note) }}" class="btn btn-info" target="_blank">
                    <i class="fas fa-print me-2"></i>Cetak Surat Jalan
                </a>
                <a href="{{ route('delivery.plans.show', $note->deliveryPlan->id) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Rencana Pengiriman
                </a>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Informasi Surat Jalan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%">Nomor Surat Jalan</td>
                                <td>: {{ $note->delivery_note_number }}</td>
                            </tr>
                            <tr>
                                <td>Tanggal Keberangkatan</td>
                                <td>: {{ $note->departure_date->format('d M Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td>Estimasi Tiba</td>
                                <td>: {{ $note->estimated_arrival_date->format('d M Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td>Ekspedisi</td>
                                <td>: {{ $note->expedition }}</td>
                            </tr>
                            <tr>
                                <td>Jenis Kendaraan</td>
                                <td>: {{ $note->vehicle_type }}</td>
                            </tr>
                            <tr>
                                <td>Plat Nomor Kendaraan</td>
                                <td>: {{ $note->vehicle_license_plate }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%">Rencana Pengiriman</td>
                                <td>: <a href="{{ route('delivery.plans.show', $note->deliveryPlan->id) }}">{{ $note->deliveryPlan->plan_number }}</a></td>
                            </tr>
                            <tr>
                                <td>Proyek</td>
                                <td>: {{ $note->deliveryPlan->project->name }}</td>
                            </tr>
                            <tr>
                                <td>Dibuat Oleh</td>
                                <td>: {{ $note->creator->name }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Item yang Dikirim</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Item</th>
                                <th>Jumlah</th>
                                <th>Satuan</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($note->deliveryPlan->draftItems as $item)
                                <tr>
                                    <td>{{ $item->item_name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->unit }}</td>
                                    <td>{{ $item->information }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada item dalam surat jalan ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Dokumen Pendukung</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($note->document)
                        @foreach(['stnk_photo', 'license_plate_photo', 'vehicle_photo', 'driver_license_photo', 'driver_id_photo', 'loading_process_photo'] as $photoField)
                            @if($note->document->$photoField)
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body text-center">
                                            <p class="card-text text-muted">{{ ucfirst(str_replace(['_', 'photo'], [' ', ''], $photoField)) }}</p>
                                            <a href="{{ Storage::url($note->document->$photoField) }}" target="_blank">
                                                <img src="{{ Storage::url($note->document->$photoField) }}" class="img-fluid rounded" alt="{{ $photoField }}" style="max-height: 200px; object-fit: contain;">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <div class="col-12">
                            <p class="text-muted text-center">Tidak ada dokumen pendukung.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
