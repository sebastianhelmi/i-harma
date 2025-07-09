@extends('layouts.pm')

@section('title', 'Detail Proyek')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Detail Proyek: {{ $project->name }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('pm.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('pm.projects.index') }}">Proyek</a></li>
                        <li class="breadcrumb-item active">{{ $project->name }}</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                @if($project->status === 'completed')
                    <a href="{{ route('pm.projects.export-excel', $project) }}" class="btn btn-success">
                        <i class="fas fa-file-excel me-2"></i>Export Excel
                    </a>
                @endif
                <a href="{{ route('pm.projects.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Informasi Proyek</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="20%">Nama Proyek</td>
                        <td>: {{ $project->name }}</td>
                    </tr>
                    <tr>
                        <td>Deskripsi</td>
                        <td>: {{ $project->description }}</td>
                    </tr>
                    <tr>
                        <td>Klien</td>
                        <td>: {{ $project->client_name }}</td>
                    </tr>
                    <tr>
                        <td>Lokasi</td>
                        <td>: {{ $project->project_location }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Mulai</td>
                        <td>: {{ $project->start_date->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Selesai</td>
                        <td>: {{ $project->end_date ? $project->end_date->format('d M Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>: <span class="badge bg-{{ $project->getStatusBadgeClass() }}">{{ ucfirst($project->status) }}</span></td>
                    </tr>
                    <tr>
                        <td>Manajer Proyek</td>
                        <td>: {{ $project->manager->name }}</td>
                    </tr>
                    <tr>
                        <td>Dokumen Kontrak</td>
                        <td>
                            @if($project->contract_document)
                                <a href="{{ Storage::url($project->contract_document) }}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-file-alt"></i> Lihat Dokumen
                                </a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>File Pendukung</td>
                        <td>
                            @if($project->files)
                                @foreach($project->files as $file)
                                    <a href="{{ Storage::url($file) }}" target="_blank" class="btn btn-sm btn-primary me-1 mb-1">
                                        <i class="fas fa-file"></i> {{ basename($file) }}
                                    </a>
                                @endforeach
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Daftar Tugas</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Nama Tugas</th>
                                <th>Deskripsi</th>
                                <th>Ditugaskan Kepada</th>
                                <th>Tanggal Jatuh Tempo</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($project->tasks as $task)
                                <tr>
                                    <td>{{ $task->name }}</td>
                                    <td>{{ $task->description }}</td>
                                    <td>{{ $task->assignedTo->name }}</td>
                                    <td>{{ $task->due_date->format('d M Y') }}</td>
                                    <td><span class="badge bg-{{ $task->getStatusBadgeClass() }}">{{ ucfirst($task->status) }}</span></td>
                                </tr>
                                @if($task->subtasks->count() > 0)
                                    @foreach($task->subtasks as $subtask)
                                        <tr>
                                            <td class="ps-4">- {{ $subtask->name }}</td>
                                            <td>{{ $subtask->description }}</td>
                                            <td>{{ $subtask->assignedTo->name }}</td>
                                            <td>{{ $subtask->due_date->format('d M Y') }}</td>
                                            <td><span class="badge bg-{{ $subtask->getStatusBadgeClass() }}">{{ ucfirst($subtask->status) }}</span></td>
                                        </tr>
                                    @endforeach
                                @endif
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">Tidak ada tugas untuk proyek ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
