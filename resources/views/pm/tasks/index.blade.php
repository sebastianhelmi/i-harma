@extends('layouts.pm')

@section('title', 'Task Management')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Task Management</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('pm.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Tasks</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('pm.tasks.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Create Task
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('pm.tasks.index') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <select name="project_id" class="form-select">
                            <option value="">All Projects</option>
                            @foreach ($projects as $id => $name)
                                <option value="{{ $id }}" @selected(request('project_id') == $id)>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                            <option value="in_progress" @selected(request('status') == 'in_progress')>In Progress</option>
                            <option value="completed" @selected(request('status') == 'completed')>Completed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-secondary w-100">Apply Filters</button>
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
                                <th>Task Name</th>
                                <th>Project</th>
                                <th>Assigned To</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tasks as $task)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if ($task->subtasks->count() > 0)
                                                <button class="btn btn-sm btn-light me-2 toggle-subtasks"
                                                    data-task-id="{{ $task->id }}">
                                                    <i class="fas fa-chevron-right"></i>
                                                </button>
                                            @endif
                                            {{ $task->name }}
                                        </div>
                                    </td>
                                    <td>{{ $task->project->name }}</td>
                                    <td>{{ $task->assignedTo->name }}</td>
                                    <td>{{ $task->due_date }}</td>
                                    <td>
                                        <span class="badge bg-{{ $task->getStatusBadgeClass() }}">
                                            <i class="fas {{ $task->getStatusIcon() }} me-1"></i>
                                            {{ $task->getStatusLabel() }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('pm.tasks.edit', $task) }}" class="btn btn-outline-primary"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('pm.tasks.create', ['project_id' => $task->project_id, 'parent_id' => $task->id]) }}"
                                                class="btn btn-outline-info" title="Add Subtask">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                            <form action="{{ route('pm.tasks.destroy', $task) }}" method="POST"
                                                onsubmit="return confirm('Are you sure?')" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @php
                                                $divisionReports = \App\Models\DivisionReport::where(
                                                    'project_id',
                                                    $task->project_id,
                                                )
                                                    ->get()
                                                    ->filter(function ($report) use ($task) {
                                                        $related = is_array($report->related_tasks)
                                                            ? $report->related_tasks
                                                            : json_decode($report->related_tasks, true);
                                                        return in_array($task->id, $related ?? []);
                                                    });
                                            @endphp
                                            @if ($divisionReports->count() === 1)
                                                <a href="{{ route('pm.reports.show', $divisionReports->first()) }}"
                                                    class="btn btn-outline-success" title="Lihat Laporan Divisi">
                                                    <i class="fas fa-file-alt"></i>
                                                </a>
                                            @elseif($divisionReports->count() > 1)
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-outline-success dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-file-alt"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        @foreach ($divisionReports as $report)
                                                            <li><a class="dropdown-item"
                                                                    href="{{ route('pm.reports.show', $report) }}">Laporan
                                                                    {{ $report->report_number }}
                                                                    ({{ $report->report_date->format('d M Y') }})
                                                                </a></li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @foreach ($task->subtasks as $subtask)
                                    <tr class="subtask subtask-{{ $task->id }} d-none">
                                        <td class="ps-5">
                                            <i class="fas fa-level-down-alt fa-rotate-90 me-2 text-muted"></i>
                                            {{ $subtask->name }}
                                        </td>
                                        <td>{{ $subtask->project->name }}</td>
                                        <td>{{ $subtask->assignedTo->name }}</td>
                                        <td>{{ $subtask->due_date }}</td>
                                        <td>
                                            <span class="badge bg-{{ $subtask->getStatusBadgeClass() }}">
                                                <i class="fas {{ $subtask->getStatusIcon() }} me-1"></i>
                                                {{ $subtask->getStatusLabel() }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('pm.tasks.edit', $subtask) }}"
                                                    class="btn btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('pm.tasks.destroy', $subtask) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure?')" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">No tasks found</div>
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
