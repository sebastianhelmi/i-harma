@extends('layouts.head-of-division')

@section('title', 'Daftar Tugas Saya')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Daftar Tugas Saya</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('head-of-division.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Tugas</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('head-of-division.tasks.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text"
                               class="form-control"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Cari tugas atau proyek...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="project_id" class="form-select">
                        <option value="">Semua Proyek</option>
                        @foreach($projects as $id => $name)
                            <option value="{{ $id }}" @selected(request('project_id') == $id)>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                        <option value="in_progress" @selected(request('status') == 'in_progress')>Sedang Dikerjakan</option>
                        <option value="completed" @selected(request('status') == 'completed')>Selesai</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary w-100">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tasks Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Nama Tugas</th>
                            <th>Proyek</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($task->subtasks->count() > 0)
                                            <button class="btn btn-sm btn-light me-2 toggle-subtasks"
                                                    data-task-id="{{ $task->id }}">
                                                <i class="fas fa-chevron-right"></i>
                                            </button>
                                        @endif
                                        {{ $task->name }}
                                    </div>
                                </td>
                                <td>{{ $task->project->name }}</td>
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
                            @foreach($task->subtasks as $subtask)
                                <tr class="subtask subtask-{{ $task->id }} d-none">
                                    <td class="ps-5">
                                        <i class="fas fa-level-down-alt fa-rotate-90 me-2 text-muted"></i>
                                        {{ $subtask->name }}
                                    </td>
                                    <td>{{ $subtask->project->name }}</td>
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
                                    <td>
                                        <a href="{{route('head-of-division.tasks.show', $subtask->id)}}"
                                           class="btn btn-sm btn-info"
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">Tidak ada tugas yang ditemukan</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $tasks->links() }}
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .toggle-subtasks .fas {
        transition: transform 0.3s ease;
    }

    .toggle-subtasks.active .fas {
        transform: rotate(90deg);
    }

    .subtask {
        background-color: #f8f9fa;
    }

    .badge {
        padding: 0.5em 0.75em;
    }

    .table > :not(caption) > * > * {
        padding: 1rem 0.75rem;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle subtasks visibility
    document.querySelectorAll('.toggle-subtasks').forEach(button => {
        button.addEventListener('click', () => {
            const taskId = button.dataset.taskId;
            const subtasks = document.querySelectorAll(`.subtask-${taskId}`);
            button.classList.toggle('active');
            subtasks.forEach(subtask => {
                subtask.classList.toggle('d-none');
            });
        });
    });
});
</script>
@endpush
@endsection
