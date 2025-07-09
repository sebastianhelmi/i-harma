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

        @if ($parentTask)
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
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Drawing File</label>
                                <input type="hidden" name="drawing_file" id="selected_drawing">
                                <div class="input-group">
                                    <input type="text" class="form-control @error('drawing_file') is-invalid @enderror" id="drawing_preview" readonly
                                        placeholder="Select drawing from project files" value="{{ old('drawing_file') }}">
                                    <button class="btn btn-outline-secondary @error('drawing_file') is-invalid @enderror" type="button" id="selectDrawingBtn" disabled>
                                        <i class="fas fa-image me-1"></i>Select Drawing
                                    </button>
                                </div>
                                @error('drawing_file')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label required">Project</label>
                                <select name="project_id" id="project_select"
                                    class="form-select @error('project_id') is-invalid @enderror" required
                                    {{ $parentTask ? 'disabled' : '' }}>
                                    <option value="">Select Project</option>
                                    @foreach ($projects as $id => $name)
                                        <option value="{{ $id }}"
                                            data-files="{{ json_encode($projectFiles[$id] ?? []) }}"
                                            @selected(old('project_id', $parentTask?->project_id ?? $project_id) == $id)>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($parentTask)
                                    <input type="hidden" name="project_id" value="{{ $parentTask->project_id }}">
                                @endif
                                @error('project_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Project Files Preview -->
                            <div id="project_files_preview" class="mb-3 d-none">
                                <button type="button" class="btn btn-outline-primary w-100" id="viewFilesBtn">
                                    <i class="fas fa-images me-2"></i>View Project Files
                                    <span class="badge bg-primary ms-2" id="fileCount">0</span>
                                </button>
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Assign To</label>
                                <select name="assigned_to" class="form-select @error('assigned_to') is-invalid @enderror"
                                    required>
                                    <option value="">Select Division Head</option>
                                    @foreach ($divisionHeads as $id => $name)
                                        <option value="{{ $id }}" @selected(old('assigned_to') == $id)>
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
                                <input type="date" name="due_date"
                                    class="form-control @error('due_date') is-invalid @enderror"
                                    value="{{ old('due_date') }}" required>
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Status</label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="pending" @selected(old('status') == 'pending')>Pending</option>
                                    <option value="in_progress" @selected(old('status') == 'in_progress')>In Progress</option>
                                    <option value="completed" @selected(old('status') == 'completed')>Completed</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if ($parentTasks->isNotEmpty())
                                <div class="mb-3">
                                    <label class="form-label">Parent Task</label>
                                    <select name="parent_task_id"
                                        class="form-select @error('parent_task_id') is-invalid @enderror">
                                        <option value="">No Parent Task</option>
                                        @foreach ($parentTasks as $id => $name)
                                            <option value="{{ $id }}" @selected(old('parent_task_id') == $id)>
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

    <!-- Project Files Modal -->
    <div class="modal fade" id="projectFilesModal" tabindex="-1" aria-labelledby="projectFilesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="projectFilesModalLabel">Select Drawing File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="modal_files_container" class="files-grid">
                        <!-- Files will be rendered here -->
                    </div>
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

            .modal-dialog-scrollable .modal-content {
                max-height: 85vh;
            }

            .modal-file-preview {
                position: relative;
                width: 100%;
                height: 250px;
                border-radius: 12px;
                overflow: hidden;
                border: 2px solid #dee2e6;
                background-color: #f8f9fa;
                margin-bottom: 1rem;
                cursor: pointer;
                transition: all 0.3s ease;
                display: flex;
                flex-direction: column;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            .modal-file-preview:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
                border-color: #0d6efd;
            }

            .modal-file-preview.selected {
                border: 3px solid #0d6efd;
                box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
                transform: translateY(-2px);
            }

            .modal-file-preview .image-container {
                flex: 1;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
                background: #fff;
            }

            .modal-file-preview img {
                max-width: 100%;
                max-height: 100%;
                object-fit: contain;
                display: block;
            }

            .modal-file-preview .file-overlay {
                background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
                color: white;
                padding: 12px 16px;
                font-size: 13px;
                font-weight: 500;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                text-align: center;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
            }

            .modal-file-preview.selected .file-overlay {
                background: linear-gradient(135deg, #198754 0%, #146c43 100%);
            }

            /* CSS Grid Layout for Files */
            .files-grid {
                display: grid;
                gap: 1rem;
                padding: 1rem;
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }

            /* Responsive grid adjustments */
            @media (min-width: 1400px) {
                .files-grid {
                    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
                }
            }

            @media (min-width: 1200px) and (max-width: 1399.98px) {
                .files-grid {
                    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                }
            }

            @media (min-width: 992px) and (max-width: 1199.98px) {
                .files-grid {
                    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
                }
            }

            @media (min-width: 768px) and (max-width: 991.98px) {
                .files-grid {
                    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                }
            }

            @media (max-width: 767.98px) {
                .files-grid {
                    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                    gap: 0.75rem;
                    padding: 0.75rem;
                }

                .modal-file-preview {
                    height: 180px;
                }
            }

            /* Force block layout for grid items */
            .modal-files-grid {
                display: flex;
                flex-wrap: wrap;
                margin: -0.75rem;
            }

            .modal-files-grid>div {
                padding: 0.75rem;
                display: block;
                float: none;
            }

            /* Grid layout improvements */
            @media (min-width: 1400px) {
                .modal-files-grid .col-xl-2 {
                    flex: 0 0 auto;
                    width: 16.666667%;
                    /* 6 columns */
                }
            }

            @media (min-width: 1200px) and (max-width: 1399.98px) {
                .modal-files-grid .col-lg-3 {
                    flex: 0 0 auto;
                    width: 20%;
                    /* 5 columns */
                }
            }

            @media (min-width: 992px) and (max-width: 1199.98px) {
                .modal-files-grid .col-lg-3 {
                    flex: 0 0 auto;
                    width: 25%;
                    /* 4 columns */
                }
            }

            @media (min-width: 768px) and (max-width: 991.98px) {
                .modal-files-grid .col-md-4 {
                    flex: 0 0 auto;
                    width: 33.333333%;
                    /* 3 columns */
                }
            }

            @media (max-width: 767.98px) {
                .modal-files-grid .col-6 {
                    flex: 0 0 auto;
                    width: 50%;
                    /* 2 columns */
                }

                .modal-file-preview {
                    height: 200px;
                }
            }

            /* Loading state */
            .modal-file-preview img.lazy {
                opacity: 0;
                transition: opacity 0.3s ease-in;
            }

            .modal-file-preview img.lazy.loaded {
                opacity: 1;
            }

            /* Empty state */
            .no-files-message {
                text-align: center;
                padding: 3rem 1rem;
                color: #6c757d;
            }

            .no-files-message i {
                font-size: 3rem;
                margin-bottom: 1rem;
                opacity: 0.5;
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
                const filesPreview = document.getElementById('project_files_preview');
                const fileCount = document.getElementById('fileCount');

                let currentFiles = [];
                let selectedFile = null;

                function renderProjectFiles(files) {
                    currentFiles = files || [];

                    if (!files || files.length === 0) {
                        filesPreview.classList.add('d-none');
                        selectDrawingBtn.disabled = true;
                        return;
                    }

                    filesPreview.classList.remove('d-none');
                    selectDrawingBtn.disabled = false;
                    fileCount.textContent = files.length;
                }

                function renderModalFiles() {
                    modalFilesContainer.innerHTML = '';
                    modalFilesContainer.className = 'files-grid';
                    selectedFile = null;
                    confirmDrawingBtn.disabled = true;

                    // Filter image files
                    const imageFiles = currentFiles.filter(file => isImageFile(file));

                    if (imageFiles.length === 0) {
                        const noFilesDiv = document.createElement('div');
                        noFilesDiv.className = 'no-files-message';
                        noFilesDiv.style.gridColumn = '1 / -1'; // Span all columns
                        noFilesDiv.innerHTML = `
                            <i class="fas fa-images"></i>
                            <h5>No Image Files Found</h5>
                            <p>This project doesn't contain any image files that can be used as drawing references.</p>
                        `;
                        modalFilesContainer.appendChild(noFilesDiv);
                        return;
                    }

                    console.log('Rendering', imageFiles.length, 'image files'); // Debug log

                    imageFiles.forEach((file, index) => {
                        const preview = document.createElement('div');
                        preview.className = 'modal-file-preview';
                        preview.dataset.file = file;

                        const imageContainer = document.createElement('div');
                        imageContainer.className = 'image-container';

                        const img = document.createElement('img');
                        img.src = `/storage/${file}`;
                        img.alt = 'Drawing file';
                        img.loading = 'lazy';

                        // Add error handling for broken images
                        img.onerror = function() {
                            imageContainer.innerHTML = `
                                <div class="text-center text-muted p-3">
                                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                    <div>Image not found</div>
                                </div>
                            `;
                        };

                        imageContainer.appendChild(img);
                        preview.appendChild(imageContainer);

                        const overlay = document.createElement('div');
                        overlay.className = 'file-overlay';
                        overlay.textContent = getFileName(file);
                        preview.appendChild(overlay);

                        // Add click handler
                        preview.addEventListener('click', () => {
                            // Remove previous selection
                            document.querySelectorAll('.modal-file-preview.selected').forEach(el => {
                                el.classList.remove('selected');
                            });

                            // Add selection to clicked item
                            preview.classList.add('selected');
                            selectedFile = file;
                            confirmDrawingBtn.disabled = false;
                        });

                        modalFilesContainer.appendChild(preview);
                    });

                    console.log('Modal container HTML:', modalFilesContainer.innerHTML); // Debug log
                }

                function isImageFile(filename) {
                    const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'];
                    const ext = filename.split('.').pop().toLowerCase();
                    return imageExtensions.includes(ext);
                }

                function getFileName(path) {
                    return path.split('/').pop();
                }

                // Handle project selection change
                projectSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const files = selectedOption.dataset.files ? JSON.parse(selectedOption.dataset.files) : [];
                    renderProjectFiles(files);
                    selectDrawingBtn.disabled = !files.length;
                    drawingPreview.value = '';
                    selectedDrawing.value = '';
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

                // Initial render if project is pre-selected
                if (projectSelect.value) {
                    const selectedOption = projectSelect.options[projectSelect.selectedIndex];
                    const files = selectedOption.dataset.files ? JSON.parse(selectedOption.dataset.files) : [];
                    renderProjectFiles(files);
                    selectDrawingBtn.disabled = !files.length;
                }
            });
        </script>
    @endpush
@endsection
