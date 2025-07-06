@extends('layouts.head-of-division')

@section('title', 'Daftar Laporan')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Laporan</h5>
                <a href="{{ route('head-of-division.reports.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Buat Laporan
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($reports->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-clipboard fa-3x text-muted mb-3"></i>
                <p class="text-muted">Belum ada laporan</p>
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No. Laporan</th>
                            <th>Project</th>
                            <th>Tanggal</th>
                            <th>Tipe</th>
                            <th>Tasks</th>
                            <th>Status</th>
                            <th>Dibuat Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                        <tr>
                            <td>{{ $report->report_number }}</td>
                            <td>{{ $report->project->name }}</td>
                            <td>{{ $report->report_date->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ Str::ucfirst($report->report_type) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ is_array($report->related_tasks) ? count($report->related_tasks) : 0 }} Tasks
                                </span>
                            </td>
                            <td>
                                @if($report->acknowledged_by)
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>Dikonfirmasi
                                </span>
                                @else
                                <span class="badge bg-warning">
                                    <i class="fas fa-clock me-1"></i>Pending
                                </span>
                                @endif
                            </td>
                            <td>{{ $report->creator->name }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('head-of-division.reports.show', $report) }}"
                                        class="btn btn-sm btn-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if(!$report->acknowledged_by)
                                    <a href="{{ route('head-of-division.reports.edit', $report) }}"
                                        class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <button type="button" class="btn btn-sm btn-danger" title="Hapus"
                                        onclick="confirmDelete({{ $report->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $reports->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(reportId) {
    Swal.fire({
        title: 'Hapus Laporan',
        text: 'Apakah Anda yakin ingin menghapus laporan ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ url('head-of-division/reports') }}/${reportId}`;
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush
@endsection