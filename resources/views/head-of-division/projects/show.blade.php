@extends('layouts.head-of-division')

@section('title', 'Detail Proyek')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Detail Proyek</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('head-of-division.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('head-of-division.projects.index') }}">Proyek</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('head-of-division.projects.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Project Info Card -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h5 class="card-title mb-4">{{ $project->name }}</h5>

                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Deskripsi</h6>
                        <p class="mb-0">{{ $project->description ?: 'Tidak ada deskripsi' }}</p>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Project Manager</h6>
                            <p class="mb-0">{{ $project->manager->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Tanggal Mulai</h6>
                            <p class="mb-0">{{ $project->start_date }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Status</h6>
                            <span class="badge bg-{{ $project->getStatusBadgeClass() }}">
                                {{ match($project->status) {
                                    'pending' => 'Pending',
                                    'ongoing' => 'Sedang Berjalan',
                                    'completed' => 'Selesai',
                                } }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Tanggal Selesai</h6>
                            <p class="mb-0">{{ $project->end_date }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tasks List -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Daftar Tugas Saya</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Nama Tugas</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($project->tasks as $task)
                            <tr>
                                <td>{{ $task->name }}</td>
                                <td>{{ $task->due_date }}</td>
                                <td>
                                    <span class="badge bg-{{ $task->getStatusBadgeClass() }}">
                                        <i class="fas {{ $task->getStatusIcon() }} me-1"></i>
                                        {{ match($task->status) {
                                            'pending' => 'Pending',
                                            'in_progress' => 'Sedang Dikerjakan',
                                            'completed' => 'Selesai',
                                        } }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('head-of-division.tasks.show', $task) }}"
                                       class="btn btn-sm btn-info"
                                       title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <div class="text-muted">Tidak ada tugas yang ditemukan</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .badge {
        padding: 0.5em 0.75em;
    }

    .card-title {
        color: #2d3748;
        font-weight: 600;
    }

    .text-muted {
        color: #718096 !important;
    }

    @media (max-width: 768px) {
        .border-start {
            border-left: none !important;
            border-top: 1px solid #e2e8f0;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
        }
    }
</style>
@endpush
@endsection
