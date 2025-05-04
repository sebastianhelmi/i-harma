@extends('layouts.pm')

@section('title', 'Persetujuan SPB')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Persetujuan SPB</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('pm.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Persetujuan SPB</li>
                </ol>
            </nav>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- SPB Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No. SPB</th>
                            <th>Proyek</th>
                            <th>Diminta Oleh</th>
                            <th>Tanggal</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($spbs as $spb)
                            <tr>
                                <td>{{ $spb->spb_number }}</td>
                                <td>
                                    <a href="{{ route('pm.projects.show', $spb->project_id) }}">
                                        {{ $spb->project->name }}
                                    </a>
                                </td>
                                <td>{{ $spb->requester->name }}</td>
                                <td>{{ $spb->spb_date->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $spb->category_entry === 'site' ? 'Site' : 'Workshop' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $spb->getStatusBadgeClass() }}">
                                        {{ ucfirst($spb->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('pm.spb-approvals.show', $spb) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    Tidak ada SPB yang menunggu persetujuan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $spbs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
