<form method="GET" action="{{ route('delivery.plans.items.create', $plan) }}" class="mb-4">
    <div class="row g-3">
        <div class="col-md-5">
            <label for="project_id" class="form-label">Filter Proyek</label>
            <select name="project_id" id="project_id" class="form-select">
                <option value="">Semua Proyek</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                        {{ $project->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-5">
            <label for="task_id" class="form-label">Filter Tugas</label>
            <select name="task_id" id="task_id" class="form-select">
                <option value="">Semua Tugas</option>
                @foreach ($tasks as $task)
                    <option value="{{ $task->id }}" {{ request('task_id') == $task->id ? 'selected' : '' }}>
                        {{ $task->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </div>
</form>