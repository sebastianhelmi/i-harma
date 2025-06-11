@extends('layouts.head-of-division')

@section('title', 'Detail Laporan')

@section('content')
<div class="container-fluid">
    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Detail Laporan {{ $report->report_number }}</h5>
            <div>
                <a href="{{ route('head-of-division.reports.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
                @if(!$report->acknowledged_by)
                <a href="{{ route('head-of-division.reports.edit', $report) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-1"></i>Edit
                </a>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Report Info -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Informasi Laporan</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td width="35%">Nomor Laporan</td>
                            <td>: {{ $report->report_number }}</td>
                        </tr>
                        <tr>
                            <td>Project</td>
                            <td>: {{ $report->project->name }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal</td>
                            <td>: {{ $report->report_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td>Tipe</td>
                            <td>: {{ Str::ucfirst($report->report_type) }}</td>
                        </tr>
                        <tr>
                            <td>Dibuat Oleh</td>
                            <td>: {{ $report->creator->name }}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>:
                                @if($report->acknowledged_by)
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>Dikonfirmasi
                                </span>
                                @else
                                <span class="badge bg-warning">
                                    <i class="fas fa-clock me-1"></i>Pending
                                </span>
                                @endif
                            </td>
                        </tr>
                        @if($report->acknowledged_by)
                        <tr>
                            <td>Dikonfirmasi Oleh</td>
                            <td>: {{ $report->acknowledger->name }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal Konfirmasi</td>
                            <td>: {{ $report->acknowledged_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <!-- Report Content -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Isi Laporan</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Progress Summary</label>
                        <p class="mb-0">{{ $report->progress_summary }}</p>
                    </div>

                    @if($report->challenges)
                    <div class="mb-3">
                        <label class="form-label text-muted">Kendala</label>
                        <p class="mb-0">{{ $report->challenges }}</p>
                    </div>
                    @endif

                    @if($report->next_plan)
                    <div class="mb-3">
                        <label class="form-label text-muted">Rencana Selanjutnya</label>
                        <p class="mb-0">{{ $report->next_plan }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tasks Progress -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Progress Task</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Task</th>
                                    <th>Project</th>
                                    <th>Status</th>
                                    <th>Progress Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($report->tasks as $task)
                                <tr>
                                    <td>{{ $task->name }}</td>
                                    <td>{{ $task->project->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $task->getStatusBadgeClass() }}">
                                            {{ $task->getStatusLabel() }}
                                        </span>
                                    </td>
                                    <td>{{ $task->pivot->progress_notes }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada task</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attachments -->
        @if(!empty($report->attachments))
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Lampiran</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($report->attachments as $attachment)
                        <div class="col-md-4">
                            <div class="card">
                                <img src="{{ Storage::url($attachment['path']) }}" class="card-img-top"
                                    alt="{{ $attachment['name'] }}">
                                <div class="card-body">
                                    <p class="card-text">{{ $attachment['name'] }}</p>
                                    <a href="{{ Storage::url($attachment['path']) }}" class="btn btn-sm btn-primary"
                                        download>
                                        <i class="fas fa-download me-1"></i>Download
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection