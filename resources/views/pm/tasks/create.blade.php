@extends('layouts.pm')

@section('title', 'Create Task')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Create Task</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('pm.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pm.tasks.index') }}">Tasks</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </nav>
        </div>
    </div>

    @if($parentTask)
        <div class="alert alert-info mb-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-info-circle me-2"></i>
                <div>
                    <strong>Creating subtask for:</strong>
                    <p class="mb-0">{{ $parentTask->name }}</p>
                    <input type="hidden" name="parent_task_id" value="{{ $parentTask->id }}">
                    <input type="hidden" name="project_id" value="{{ $parentTask->project_id }}">
                </div>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('pm.tasks.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label required">Task Name</label>
                            <input type="text"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description"
                                      rows="4"
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
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
                                    {{ $parentTask ? 'disabled' : '' }}>
                                <option value="">Select Project</option>
                                @foreach($projects as $id => $name)
                                    <option value="{{ $id }}"
                                        @selected(old('project_id', $parentTask?->project_id ?? $project_id) == $id)>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @if($parentTask)
                                <input type="hidden" name="project_id" value="{{ $parentTask->project_id }}">
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
                                        @selected(old('assigned_to') == $id)>
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
                                   value="{{ old('due_date') }}"
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
                                <option value="pending" @selected(old('status') == 'pending')>Pending</option>
                                <option value="in_progress" @selected(old('status') == 'in_progress')>In Progress</option>
                                <option value="completed" @selected(old('status') == 'completed')>Completed</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($parentTasks->isNotEmpty())
                            <div class="mb-3">
                                <label class="form-label">Parent Task</label>
                                <select name="parent_task_id"
                                        class="form-select @error('parent_task_id') is-invalid @enderror">
                                    <option value="">No Parent Task</option>
                                    @foreach($parentTasks as $id => $name)
                                        <option value="{{ $id }}"
                                            @selected(old('parent_task_id') == $id)>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent_task_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                    </div>
                </div>

                <div class="text-end mt-4">
                    <a href="{{ route('pm.tasks.index') }}" class="btn btn-light me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Create Task
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
</style>
@endpush
@endsection
