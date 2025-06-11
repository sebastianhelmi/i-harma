@extends('layouts.delivery')

@section('title', 'Rencana Pengiriman')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Daftar Rencana Pengiriman</h5>
                <a href="{{ route('delivery.plans.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Buat Rencana
                </a>
            </div>
        </div>

        <div class="card-body">
            <!-- Filters -->
            <form class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" {{ request('status')==$value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Cari</label>
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                        placeholder="No. Rencana / Tujuan">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Filter
                    </button>
                </div>
            </form>

            <!-- Plans Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No. Rencana</th>
                            <th>Project</th> <!-- Tambahkan ini -->
                            <th>Tujuan</th>
                            <th>Tgl. Rencana</th>
                            <th>Kendaraan</th>
                            <th>Status</th>
                            <th>Dibuat Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($plans as $plan)
                        <tr>
                            <td>{{ $plan->plan_number }}</td>
                            <td>{{ $plan->project->name ?? '-' }}</td> <!-- Tambahkan ini -->
                            <td>{{ $plan->destination }}</td>
                            <td>{{ $plan->planned_date->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $plan->vehicle_count }} {{ Str::ucfirst($plan->vehicle_type) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $plan->getStatusBadgeClass() }}">
                                    {{ $plan->getStatusLabel() }}
                                </span>
                            </td>
                            <td>{{ $plan->creator->name }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('delivery.plans.show', $plan) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($plan->canBeUpdated())
                                    <a href="{{ route('delivery.plans.edit', $plan) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif
                                    @if($plan->canBeCancelled())
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="confirmCancel({{ $plan->id }})">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-3">
                                <i class="fas fa-info-circle me-2"></i>Tidak ada data
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $plans->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Hidden Delete Form -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('styles')
<style>
    .btn-group .btn {
        margin: 0 2px;
    }

    .badge {
        font-size: 0.85em;
        padding: 0.35em 0.65em;
    }

    .table td {
        vertical-align: middle;
    }
</style>
@endpush

@push('scripts')
<script>
    function confirmCancel(planId) {
    Swal.fire({
        title: 'Batalkan Rencana?',
        text: "Rencana pengiriman akan dibatalkan",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Batalkan',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('delete-form');
            form.action = `{{ url('delivery/plans') }}/${planId}`;
            form.submit();
        }
    });
}

// Auto-submit filter form when date or status changes
document.querySelectorAll('select[name="status"], input[name="date"]').forEach(element => {
    element.addEventListener('change', function() {
        this.closest('form').submit();
    });
});
</script>
@endpush
@endsection