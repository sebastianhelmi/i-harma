@extends('layouts.head-of-division')

@section('title', 'Buat Laporan Baru')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Buat Laporan Baru</h5>
                <a href="{{ route('head-of-division.reports.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('head-of-division.reports.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <!-- Project Selection -->
                    <div class="col-md-6">
                        <label class="form-label required">Project</label>
                        <select name="project_id" class="form-select @error('project_id') is-invalid @enderror"
                            required>
                            <option value="">Pilih Project</option>
                            @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id')==$project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('project_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Report Type -->
                    <div class="col-md-3">
                        <label class="form-label required">Tipe Laporan</label>
                        <select name="report_type" class="form-select @error('report_type') is-invalid @enderror"
                            required>
                            <option value="daily" {{ old('report_type')=='daily' ? 'selected' : '' }}>Harian</option>
                            <option value="weekly" {{ old('report_type')=='weekly' ? 'selected' : '' }}>Mingguan
                            </option>
                            <option value="monthly" {{ old('report_type')=='monthly' ? 'selected' : '' }}>Bulanan
                            </option>
                        </select>
                        @error('report_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Report Date -->
                    <div class="col-md-3">
                        <label class="form-label required">Tanggal Laporan</label>
                        <input type="date" name="report_date"
                            class="form-control @error('report_date') is-invalid @enderror"
                            value="{{ old('report_date', date('Y-m-d')) }}" required>
                        @error('report_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Progress Summary -->
                    <div class="col-12">
                        <label class="form-label required">Progress Summary</label>
                        <textarea name="progress_summary" rows="4"
                            class="form-control @error('progress_summary') is-invalid @enderror"
                            required>{{ old('progress_summary') }}</textarea>
                        @error('progress_summary')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tasks Progress -->
                    <div class="col-12">
                        <label class="form-label required">Progress Task</label>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="40px">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="selectAll">
                                            </div>
                                        </th>
                                        <th>Task</th>
                                        <th>Project</th>
                                        <th>Status</th>
                                        <th>Progress Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tasks as $task)
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input type="checkbox" name="tasks[{{ $task->id }}][id]"
                                                    value="{{ $task->id }}" class="form-check-input task-checkbox" {{
                                                    in_array($task->id, old('tasks', [])) ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>{{ $task->name }}</td>
                                        <td>{{ $task->project->name }}</td>
                                        <td>
                                            <span class="badge bg-{{ $task->getStatusBadgeClass() }}">
                                                {{ $task->getStatusLabel() }}
                                            </span>
                                        </td>
                                        <td>
                                            <textarea name="tasks[{{ $task->id }}][progress]" rows="2"
                                                class="form-control task-progress"
                                                disabled>{{ old("tasks.{$task->id}.progress") }}</textarea>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada task aktif</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @error('tasks')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Challenges -->
                    <div class="col-md-6">
                        <label class="form-label">Kendala</label>
                        <textarea name="challenges" rows="3"
                            class="form-control @error('challenges') is-invalid @enderror">{{ old('challenges') }}</textarea>
                        @error('challenges')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Next Plan -->
                    <div class="col-md-6">
                        <label class="form-label">Rencana Selanjutnya</label>
                        <textarea name="next_plan" rows="3"
                            class="form-control @error('next_plan') is-invalid @enderror">{{ old('next_plan') }}</textarea>
                        @error('next_plan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Attachments -->
                    <div class="col-12">
                        <label class="form-label">Lampiran</label>
                        <input type="file" name="attachments[]"
                            class="form-control @error('attachments.*') is-invalid @enderror" multiple accept="image/*">
                        <small class="text-muted">Max. 5MB per file</small>
                        @error('attachments.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Simpan Laporan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Handle select all checkbox
    const selectAll = document.getElementById('selectAll');
    const taskCheckboxes = document.querySelectorAll('.task-checkbox');
    const taskProgress = document.querySelectorAll('.task-progress');

    selectAll.addEventListener('change', function() {
        taskCheckboxes.forEach((checkbox, index) => {
            checkbox.checked = this.checked;
            taskProgress[index].disabled = !this.checked;
        });
    });

    // Handle individual checkboxes
    taskCheckboxes.forEach((checkbox, index) => {
        checkbox.addEventListener('change', function() {
            taskProgress[index].disabled = !this.checked;

            // Update select all checkbox
            const allChecked = Array.from(taskCheckboxes).every(cb => cb.checked);
            const allUnchecked = Array.from(taskCheckboxes).every(cb => !cb.checked);
            selectAll.checked = allChecked;
            selectAll.indeterminate = !allChecked && !allUnchecked;
        });

        // Set initial state
        taskProgress[index].disabled = !checkbox.checked;
    });
});
</script>
@endpush
@endsection