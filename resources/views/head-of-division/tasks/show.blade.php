@extends('layouts.head-of-division')

@section('title', 'Detail Tugas')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Detail Tugas</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('head-of-division.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('head-of-division.tasks.index') }}">Tugas</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('head-of-division.tasks.index') }}" class="btn btn-outline-secondary">
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

    <!-- Task Info Card -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h5 class="card-title mb-4">{{ $task->name }}</h5>

                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Deskripsi</h6>
                        <p class="mb-0">{{ $task->description ?: 'Tidak ada deskripsi' }}</p>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Proyek</h6>
                            <p class="mb-0">
                                <a href="{{ route('head-of-division.projects.show', $task->project_id) }}"
                                   class="text-decoration-none">
                                    {{ $task->project->name }}
                                </a>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Deadline</h6>
                            <p class="mb-0">{{ $task->due_date }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 border-start">
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Status</h6>
                        <span class="badge bg-{{ $task->getStatusBadgeClass() }}">
                            <i class="fas {{ $task->getStatusIcon() }} me-1"></i>
                            {{ match($task->status) {
                                'pending' => 'Pending',
                                'in_progress' => 'Sedang Dikerjakan',
                                'completed' => 'Selesai',
                            } }}
                        </span>
                    </div>

                    @if($canCreateSpb)
                    <a href="{{ route('head-of-division.spbs.create', ['task' => $task]) }}"
                       class="btn btn-primary w-100">
                        <i class="fas fa-file-alt me-2"></i>Ajukan SPB
                    </a>
                @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Subtasks Section -->
    @if($task->subtasks->isNotEmpty())
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Sub Tugas</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Nama Sub Tugas</th>
                                <th>Deadline</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($task->subtasks as $subtask)
                                <tr>
                                    <td>{{ $subtask->name }}</td>
                                    <td>{{ $subtask->due_date }}</td>
                                    <td>
                                        <span class="badge bg-{{ $subtask->getStatusBadgeClass() }}">
                                            <i class="fas {{ $subtask->getStatusIcon() }} me-1"></i>
                                            {{ match($subtask->status) {
                                                'pending' => 'Pending',
                                                'in_progress' => 'Sedang Dikerjakan',
                                                'completed' => 'Selesai',
                                            } }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
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
