@extends('layouts.pm')

@section('title', 'Riwayat Pengadaan')
@section('page-title')
    <i class="fas fa-history me-2"></i>Riwayat Pengadaan
@endsection

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('pm.riwayat.index') }}" method="GET" class="row g-3 align-items-end mb-3">
            <div class="col-md-3">
                <label class="form-label">Jenis Dokumen</label>
                <select name="type" class="form-select">
                    <option value="">Semua</option>
                    <option value="spb" {{ request('type') == 'spb' ? 'selected' : '' }}>SPB</option>
                    <option value="po" {{ request('type') == 'po' ? 'selected' : '' }}>PO</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="ordered" {{ request('status') == 'ordered' ? 'selected' : '' }}>Ordered</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Nomor Dokumen</th>
                        <th>Status</th>
                        <th>Aktor</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($histories as $history)
                    <tr>
                        <td>{{ ($histories->currentPage() - 1) * $histories->perPage() + $loop->iteration }}</td>
                        <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <span class="badge bg-info">
                                {{ $history->getDocumentTypeLabel() }}
                            </span>
                        </td>
                        <td>{{ $history->document_number }}</td>
                        <td>
                            <span class="badge bg-{{ $history->getStatusBadgeClass() }}">
                                {{ ucfirst($history->status) }}
                            </span>
                        </td>
                        <td>{{ $history->actor?->name ?? '-' }}</td>
                        <td>{{ $history->description }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-history fa-2x mb-2 text-secondary"></i>
                            <p class="text-secondary mb-0">Tidak ada riwayat</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $histories->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
