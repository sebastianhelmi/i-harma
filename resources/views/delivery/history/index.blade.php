@extends('layouts.delivery')

@section('title', 'Riwayat Pengiriman')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Riwayat Pengiriman</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('delivery.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Riwayat Pengiriman</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Filter Riwayat</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('delivery.history.index') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">Tanggal Selesai</label>
                        <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Hasil Riwayat</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>No. Delivery Note</th>
                                <th>Tanggal Pengiriman</th>
                                <th>Proyek</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(request()->has('start_date'))
                                @forelse($deliveryNotes as $note)
                                    <tr>
                                        <td>
                                            <a href="{{ route('delivery.notes.show', $note->deliveryPlan->id) }}">{{ $note->delivery_note_number }}</a>
                                        </td>
                                        <td>{{ $note->departure_date->format('d/m/Y') }}</td>
                                        <td>{{ $note->deliveryPlan->project->name }}</td>
                                        <td>
                                            <span class="badge bg-{{ $note->deliveryPlan->getStatusBadgeClass() }}">
                                                {{ ucfirst($note->deliveryPlan->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('delivery.notes.show', $note->deliveryPlan->id) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-times-circle fa-2x mb-3"></i>
                                                <p class="mb-0">Tidak ada data Delivery Note yang ditemukan untuk rentang tanggal yang dipilih.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            @else
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                                            <p class="mb-0">Silakan gunakan filter untuk menampilkan riwayat.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
