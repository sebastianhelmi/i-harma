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

        @if (session('error'))
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
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name', $task->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="4">{{ old('description', $task->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Drawing File</label>
                                <input type="hidden" name="drawing_file" id="selected_drawing"
                                    value="{{ old('drawing_file', $task->drawing_file) }}">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="drawing_preview" readonly
                                        placeholder="Select drawing from project files"
                                        value="{{ old('drawing_file', $task->drawing_file ? basename($task->drawing_file) : '') }}">
                                    <button class="btn btn-outline-secondary" type="button" id="selectDrawingBtn">
                                        <i class="fas fa-image me-1"></i>Change Drawing
                                    </button>
                                </div>
                                @if ($task->drawing_file)
                                    <div class="mt-2">
                                        <img src="{{ $task->getDrawingUrl() }}" alt="Current drawing" class="img-thumbnail"
                                            style="max-height: 200px;">
                                    </div>
                                @endif
                                @error('drawing_file')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label required">Project</label>
                                <select name="project_id" id="project_select"
                                    class="form-select @error('project_id') is-invalid @enderror" required>
                                    <option value="">Select Project</option>
                                    @foreach ($projects as $id => $name)
                                        <option value="{{ $id }}" data-files='@json($projectFiles[$id] ?? [])'
                                            @selected(old('project_id', $task->project_id) == $id)>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Assign To</label>
                                <select name="assigned_to" class="form-select @error('assigned_to') is-invalid @enderror"
                                    required>
                                    <option value="">Select Division Head</option>
                                    @foreach ($divisionHeads as $id => $name)
                                        <option value="{{ $id }}" @selected(old('assigned_to', $task->assigned_to) == $id)>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_to')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    Update Task
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Project Files Modal -->
    <div class="modal fade" id="projectFilesModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select Drawing File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3" id="modal_files_container"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmDrawingBtn" disabled>
                        Select Drawing
                    </button>
                </div>
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

            .modal-file-preview {
                position: relative;
                width: 100%;
                height: 200px;
                border-radius: 8px;
                overflow: hidden;
                border: 1px solid #dee2e6;
                background-color: #f8f9fa;
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .modal-file-preview:hover {
                transform: scale(1.02);
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            .modal-file-preview.selected {
                border: 3px solid #0d6efd;
            }

            .modal-file-preview img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .modal-file-preview .file-overlay {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                background: rgba(0, 0, 0, 0.7);
                color: white;
                padding: 8px;
                font-size: 12px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const projectSelect = document.getElementById('project_select');
                const selectDrawingBtn = document.getElementById('selectDrawingBtn');
                const drawingPreview = document.getElementById('drawing_preview');
                const selectedDrawing = document.getElementById('selected_drawing');
                const modalFilesContainer = document.getElementById('modal_files_container');
                const confirmDrawingBtn = document.getElementById('confirmDrawingBtn');
                const projectFilesModal = new bootstrap.Modal(document.getElementById('projectFilesModal'));

                let currentFiles = [];
                let selectedFile = null;

                function isImageFile(filename) {
                    const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
                    const ext = filename.split('.').pop().toLowerCase();
                    return imageExtensions.includes(ext);
                }

                function getFileName(path) {
                    return path.split('/').pop();
                }

                function renderModalFiles() {
                    modalFilesContainer.innerHTML = '';
                    selectedFile = selectedDrawing.value;
                    confirmDrawingBtn.disabled = !selectedFile;

                    currentFiles.forEach(file => {
                        if (!isImageFile(file)) return;

                        const col = document.createElement('div');
                        col.className = 'col-md-4';

                        const preview = document.createElement('div');
                        preview.className = 'modal-file-preview';
                        if (file === selectedFile) {
                            preview.classList.add('selected');
                        }
                        preview.dataset.file = file;

                        const img = document.createElement('img');
                        img.src = `/storage/${file}`;
                        img.alt = 'Drawing file';
                        preview.appendChild(img);

                        const overlay = document.createElement('div');
                        overlay.className = 'file-overlay';
                        overlay.textContent = getFileName(file);
                        preview.appendChild(overlay);

                        preview.addEventListener('click', () => {
                            document.querySelectorAll('.modal-file-preview.selected')
                                .forEach(el => el.classList.remove('selected'));
                            preview.classList.add('selected');
                            selectedFile = file;
                            confirmDrawingBtn.disabled = false;
                        });

                        col.appendChild(preview);
                        modalFilesContainer.appendChild(col);
                    });
                }

                // Handle project selection change
                projectSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    currentFiles = selectedOption.dataset.files ? JSON.parse(selectedOption.dataset.files) : [];
                    selectDrawingBtn.disabled = !currentFiles.length;
                });

                // Handle select drawing button click
                selectDrawingBtn.addEventListener('click', function() {
                    renderModalFiles();
                    projectFilesModal.show();
                });

                // Handle confirm drawing selection
                confirmDrawingBtn.addEventListener('click', function() {
                    if (selectedFile) {
                        drawingPreview.value = getFileName(selectedFile);
                        selectedDrawing.value = selectedFile;
                        projectFilesModal.hide();
                    }
                });

                // Initial setup
                if (projectSelect.value) {
                    const selectedOption = projectSelect.options[projectSelect.selectedIndex];
                    currentFiles = selectedOption.dataset.files ? JSON.parse(selectedOption.dataset.files) : [];
                    selectDrawingBtn.disabled = !currentFiles.length;
                }
            });
        </script>
    @endpush
@endsection
