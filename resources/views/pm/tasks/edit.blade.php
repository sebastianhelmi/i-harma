@extends('layouts.pm')

@section('title', 'Edit Task')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Edit Task</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('pm.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pm.tasks.index') }}">Tasks</a></li>
                    <li class="breadcrumb-item active">Edit Task</li>
                </ol>
            </nav>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('pm.tasks.update', $task) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label required">Task Name</label>
                            <input type="text"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $task->name) }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description"
                                      rows="4"
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $task->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label required">Project</label>
                            <select name="project_id"
                                    class="form-select @error('project_id') is-invalid @enderror"
                                    required
                                    {{ $task->parent_task_id ? 'disabled' : '' }}>
                                <option value="">Select Project</option>
                                @foreach($projects as $id => $name)
                                    <option value="{{ $id }}"
                                        @selected(old('project_id', $task->project_id) == $id)>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @if($task->parent_task_id)
                                <input type="hidden" name="project_id" value="{{ $task->project_id }}">
                            @endif
                            @error('project_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Assign To</label>
                            <select name="assigned_to"
                                    class="form-select @error('assigned_to') is-invalid @enderror"
                                    required>
                                <option value="">Select Division Head</option>
                                @foreach($divisionHeads as $id => $name)
                                    <option value="{{ $id }}"
                                        @selected(old('assigned_to', $task->assigned_to) == $id)>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('assigned_to')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Due Date</label>
                            <input type="date"
                                   name="due_date"
                                   class="form-control @error('due_date') is-invalid @enderror"
                                   value="{{ old('due_date', $task->due_date) }}"
                                   required>
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Status</label>
                            <select name="status"
                                    class="form-select @error('status') is-invalid @enderror"
                                    required>
                                <option value="pending" @selected(old('status', $task->status) == 'pending')>Pending</option>
                                <option value="in_progress" @selected(old('status', $task->status) == 'in_progress')>In Progress</option>
                                <option value="completed" @selected(old('status', $task->status) == 'completed')>Completed</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if(!$task->parent_task_id && $parentTasks->isNotEmpty())
                            <div class="mb-3">
                                <label class="form-label">Parent Task</label>
                                <select name="parent_task_id"
                                        class="form-select @error('parent_task_id') is-invalid @enderror">
                                    <option value="">No Parent Task</option>
                                    @foreach($parentTasks as $id => $name)
                                        <option value="{{ $id }}"
                                            @selected(old('parent_task_id', $task->parent_task_id) == $id)>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent_task_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        @if($task->subtasks->count() > 0)
                            <div class="mb-3">
                                <label class="form-label d-block">Subtasks</label>
                                <div class="list-group">
                                    @foreach($task->subtasks as $subtask)
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span>{{ $subtask->name }}</span>
                                                <span class="badge bg-{{ $subtask->getStatusBadgeClass() }}">
                                                    {{ $subtask->getStatusLabel() }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="text-end mt-4">
                    <a href="{{ route('pm.tasks.index') }}" class="btn btn-light me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .form-label.required::after {
        content: "*";
        color: red;
        margin-left: 4px;
    }

    .list-group-item {
        padding: 0.5rem 1rem;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
</style>
@endpush
@endsection
