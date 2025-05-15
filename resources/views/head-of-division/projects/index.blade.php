@extends('layouts.head-of-division')

@section('title', 'Daftar Proyek Saya')

@section('content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Daftar Proyek Saya</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('head-of-division.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Proyek</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Search & Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('head-of-division.projects.index') }}" method="GET" class="row g-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama proyek...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                            <option value="ongoing" @selected(request('status') == 'ongoing')>Sedang Berjalan</option>
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

        <!-- Projects Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Nama Proyek</th>
                                <th>Deskripsi</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Status</th>
                                <th>Project Manager</th>
                                <th>Tugas Saya</th>
                                <th>Aksi</th> <!-- Add new column -->
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($projects as $project)
                                <tr>
                                    <td>{{ $project->name }}</td>
                                    <td>{{ Str::limit($project->description, 50) }}</td>
                                    <td>{{ $project->start_date->format('d M Y') }}</td>
                                    <td>{{ $project->end_date?->format('d M Y') ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $project->getStatusBadgeClass() }}">
                                            {{ match ($project->status) {
                                                'pending' => 'Pending',
                                                'ongoing' => 'Sedang Berjalan',
                                                'completed' => 'Selesai',
                                            } }}
                                        </span>
                                    </td>
                                    <td>{{ $project->manager->name }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $project->tasks->count() }} Tugas
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('head-of-division.projects.show', $project) }}"
                                                class="btn btn-sm btn-info" title="Detail Proyek">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('head-of-division.tasks.index', ['project_id' => $project->id]) }}"
                                                class="btn btn-sm btn-primary" title="Lihat Tugas">
                                                <i class="fas fa-tasks"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">Tidak ada proyek yang ditemukan</div>
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

    @push('styles')
        <style>
            .badge {
                padding: 0.5em 0.75em;
            }

            .table> :not(caption)>*>* {
                padding: 1rem 0.75rem;
            }

            /* New styles */
            .btn-group .btn {
                margin: 0 2px;
            }

            .btn-group .btn i {
                width: 16px;
                text-align: center;
            }

            .btn-sm {
                padding: 0.25rem 0.5rem;
            }
        </style>
    @endpush
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize tooltips
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
                tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                });
            });
        </script>
    @endpush
@endsection
