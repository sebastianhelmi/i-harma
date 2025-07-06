@extends('layouts.pm')

@section('title', 'Edit Project')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Edit Project</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('pm.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('pm.projects.index') }}">Projects</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Project</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('pm.projects.update', $project) }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Basic Info Section -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Basic Information</h5>

                                <div class="mb-3">
                                    <label class="form-label required">Project Name</label>
                                    <input type="text"
                                           name="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $project->name) }}"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">Description</label>
                                    <textarea name="description"
                                              rows="4"
                                              class="form-control @error('description') is-invalid @enderror"
                                              required>{{ old('description', $project->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">Client Name</label>
                                    <input type="text"
                                           name="client_name"
                                           class="form-control @error('client_name') is-invalid @enderror"
                                           value="{{ old('client_name', $project->client_name) }}"
                                           required>
                                    @error('client_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">Project Location</label>
                                    <input type="text"
                                           name="project_location"
                                           class="form-control @error('project_location') is-invalid @enderror"
                                           value="{{ old('project_location', $project->project_location) }}"
                                           required>
                                    @error('project_location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Start Date</label>
                                        <input type="date"
                                               name="start_date"
                                               class="form-control @error('start_date') is-invalid @enderror"
                                               value="{{ old('start_date', $project->start_date->format('Y-m-d')) }}"
                                               required>
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">End Date</label>
                                        <input type="date"
                                               name="end_date"
                                               class="form-control @error('end_date') is-invalid @enderror"
                                               value="{{ old('end_date', $project->end_date?->format('Y-m-d')) }}">
                                        @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status & Files Section -->
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Project Status</h5>

                                <div class="mb-3">
                                    <label class="form-label required">Status</label>
                                    <select name="status"
                                            class="form-select @error('status') is-invalid @enderror"
                                            required>
                                        @foreach(['pending', 'ongoing', 'completed'] as $status)
                                            <option value="{{ $status }}"
                                                {{ old('status', $project->status) == $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Project Files</h5>

                                @if($project->files)
                                    <div class="mb-3">
                                        <label class="form-label d-block">Current Files</label>
                                        <div class="list-group">
                                            @foreach($project->files as $file)
                                                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                    <a href="{{ Storage::url($file) }}"
                                                       target="_blank"
                                                       class="text-decoration-none text-body">
                                                        <i class="icon" data-lucide="file-text"></i>
                                                        {{ basename($file) }}
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label class="form-label">Add New Files</label>
                                    <input type="file"
                                           name="files[]"
                                           class="form-control @error('files.*') is-invalid @enderror"
                                           multiple>
                                    <small class="text-muted">Upload multiple files (max 10MB each)</small>
                                    @error('files.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card mt-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Contract Document</h5>

                                @if($project->contract_document)
                                    <div class="mb-3">
                                        <label class="form-label d-block">Current Document</label>
                                        <div class="list-group">
                                            <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                <a href="{{ Storage::url($project->contract_document) }}"
                                                   target="_blank"
                                                   class="text-decoration-none text-body">
                                                    <i class="icon" data-lucide="file-text"></i>
                                                    {{ basename($project->contract_document) }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label class="form-label">Upload New Contract</label>
                                    <input type="file"
                                           name="contract_document"
                                           class="form-control @error('contract_document') is-invalid @enderror">
                                    <small class="text-muted">Uploading a new file will replace the old one.</small>
                                    @error('contract_document')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('pm.projects.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="icon" data-lucide="save"></i> Update Project
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

    .card {
        border-radius: 0.5rem;
        border: 1px solid rgba(0,0,0,0.125);
    }

    .card-title {
        color: #1f2937;
        font-weight: 600;
    }

    .list-group-item {
        i {
            margin-right: 8px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    lucide.createIcons();
});
</script>
@endpush
@endsection
