@extends('layouts.pm')

@section('title', 'Laporan Proyek')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Laporan Proyek</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('pm.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Laporan</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Filter Laporan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('pm.reports.index') }}" method="GET" class="row g-3">
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
                <h5 class="card-title mb-0">Hasil Laporan</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Nama Proyek</th>
                                <th>Dokumen Kontrak</th>
                                <th>Jumlah SPB</th>
                                <th>Progress Tugas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(request()->has('start_date'))
                                @forelse($projectsData as $data)
                                    <tr>
                                        <td>{{ $data['project']->name }}</td>
                                        <td>
                                            @if($data['project']->contract_document)
                                                <a href="{{ Storage::url($data['project']->contract_document) }}" target="_blank" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-file-alt"></i> Lihat Dokumen
                                                </a>
                                            @else
                                                Tidak Ada
                                            @endif
                                        </td>
                                        <td>{{ $data['spbs']->count() }} SPB</td>
                                        <td>{{ $data['task_progress'] }}% ({{ $data['tasks']->where('status', 'completed')->count() }} dari {{ $data['tasks']->count() }} tugas selesai)</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-times-circle fa-2x mb-3"></i>
                                                <p class="mb-0">Tidak ada data proyek yang ditemukan untuk rentang tanggal yang dipilih.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            @else
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                                            <p class="mb-0">Silakan gunakan filter untuk menampilkan laporan.</p>
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
