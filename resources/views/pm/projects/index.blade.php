@extends('layouts.pm')

@section('title', 'Project Management')

@section('content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Project Management</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('pm.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Projects</li>
                    </ol>
                </nav>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProjectModal">
                <i class="icon" data-lucide="plus"></i> Create Project
            </button>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('pm.projects.index') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <select class="form-select" name="status">
                            <option value="">All Status</option>
                            <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                            <option value="ongoing" @selected(request('status') == 'ongoing')>Ongoing</option>
                            <option value="completed" @selected(request('status') == 'completed')>Completed</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i data-lucide="search"></i></span>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                                placeholder="Search projects...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary w-100">Apply Filters</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Projects List -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Client Name</th>
                                <th>Project Location</th>
                                <th>Description</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Files</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($projects as $project)
                                <tr>
                                    <td><a href="{{ route('pm.projects.show', $project) }}">{{ $project->name }}</a></td>
                                    <td>{{ $project->client_name }}</td>
                                    <td>{{ $project->project_location }}</td>
                                    <td>{{ $project->description }}</td>
                                    <td>{{ $project->start_date->format('M d, Y') }}</td>
                                    <td>{{ $project->end_date?->format('M d, Y') ?? '-' }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $project->status === 'pending' ? 'warning' : ($project->status === 'ongoing' ? 'info' : 'success') }}">
                                            {{ ucfirst($project->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($project->files)
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light dropdown-toggle"
                                                    data-bs-toggle="dropdown">
                                                    {{ count($project->files) }} Files
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @foreach ($project->files as $file)
                                                        <li>
                                                            <a class="dropdown-item" href="{{ Storage::url($file) }}"
                                                                target="_blank">
                                                                <i data-lucide="file"></i> {{ basename($file) }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @else
                                            <span class="text-muted">No files</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('pm.tasks.index', ['project_id' => $project->id]) }}"
                                                class="btn btn-outline-secondary" title="View Tasks">
                                                <i class="fa-solid fa-list-check"></i>
                                            </a>
                                            <a href="{{ route('pm.projects.edit', $project) }}"
                                                class="btn btn-outline-primary">
                                                <i class="fa-solid fa-edit"></i>
                                            </a>
                                            <a href="{{ route('pm.projects.summary-pdf', $project) }}"
                                                class="btn btn-outline-info" title="Export Summary PDF" target="_blank">
                                                <i class="fa-solid fa-file-pdf"></i>
                                            </a>
                                            <form action="{{ route('pm.projects.destroy', $project) }}" method="POST"
                                                onsubmit="return confirm('Are you sure?')" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">No projects found</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $projects->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Create Project Modal -->
    <div class="modal fade" id="addProjectModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('pm.projects.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Create New Project</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Project Name</label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3" required>{{ old('description') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Client Name</label>
                            <input type="text" class="form-control" name="client_name"
                                value="{{ old('client_name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Project Location</label>
                            <input type="text" class="form-control" name="project_location"
                                value="{{ old('project_location') }}" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" class="form-control" name="start_date"
                                    value="{{ old('start_date') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">End Date</label>
                                <input type="date" class="form-control" name="end_date"
                                    value="{{ old('end_date') }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="ongoing">Ongoing</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Project Drawing Files</label>
                            <input type="file" class="form-control d-none" id="drawingFilesInput" accept="image/*"
                                multiple>
                            <button type="button" class="btn btn-outline-primary" id="addDrawingFilesBtn">
                                <i class="fa fa-plus me-1"></i> Add Image(s)
                            </button>
                            <div id="drawingFilesPreview" class="mt-2 d-flex flex-wrap gap-2"></div>
                            <small class="text-muted">You can select multiple images. Selected images will be previewed
                                below.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contract Document</label>
                            <input type="file" class="form-control" name="contract_document">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Project</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .badge {
                padding: 0.5em 0.75em;
            }

            .btn-group-sm .btn {
                padding: 0.25rem 0.5rem;
            }

            .btn-group-sm .btn i {
                width: 16px;
                height: 16px;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const input = document.getElementById('drawingFilesInput');
                const addBtn = document.getElementById('addDrawingFilesBtn');
                const preview = document.getElementById('drawingFilesPreview');
                let selectedFiles = [];

                addBtn.addEventListener('click', () => input.click());

                input.addEventListener('change', function(e) {
                    // Add new files to selectedFiles array
                    for (const file of Array.from(e.target.files)) {
                        // Hindari duplikat berdasarkan nama dan ukuran
                        if (!selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
                            selectedFiles.push(file);
                        }
                    }
                    renderPreview();
                    // Reset input agar bisa memilih file yang sama lagi jika perlu
                    input.value = '';
                });

                function renderPreview() {
                    preview.innerHTML = '';
                    selectedFiles.forEach((file, idx) => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const div = document.createElement('div');
                            div.className = 'position-relative';
                            div.style.width = '100px';
                            div.style.height = '100px';
                            div.innerHTML = `
                                <img src="${e.target.result}" class="img-thumbnail" style="width:100px;height:100px;object-fit:cover;">
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" data-idx="${idx}" style="z-index:2;">&times;</button>
                            `;
                            preview.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    });
                }

                preview.addEventListener('click', function(e) {
                    if (e.target.tagName === 'BUTTON') {
                        const idx = parseInt(e.target.getAttribute('data-idx'));
                        selectedFiles.splice(idx, 1);
                        renderPreview();
                    }
                });

                // Saat submit form, tambahkan file ke FormData
                const form = addBtn.closest('form');
                form.addEventListener('submit', function(e) {
                    // Hapus input file yang sudah ada
                    form.querySelectorAll('input[name="files[]"]').forEach(el => el.remove());
                    // Tambahkan file ke form secara dinamis
                    selectedFiles.forEach(file => {
                        const dt = new DataTransfer();
                        dt.items.add(file);
                        const fileInput = document.createElement('input');
                        fileInput.type = 'file';
                        fileInput.name = 'files[]';
                        fileInput.files = dt.files;
                        fileInput.classList.add('d-none');
                        form.appendChild(fileInput);
                    });
                });
            });
        </script>
    @endpush
@endsection
