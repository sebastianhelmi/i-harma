@extends('layouts.pm')

@section('title', 'Detail Laporan Divisi')
@section('page-title', 'Detail Laporan Divisi')

@section('content')
    <div class="container-fluid">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Detail Laporan Divisi</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered mb-4">
                    <tr>
                        <th width="200">Nomor Laporan</th>
                        <td>{{ $report->report_number }}</td>
                    </tr>
                    <tr>
                        <th>Proyek</th>
                        <td>{{ $report->project->name }}</td>
                    </tr>
                    <tr>
                        <th>Divisi</th>
                        <td>{{ $report->division->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>{{ $report->report_date->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>Tipe</th>
                        <td>{{ ucfirst($report->report_type) }}</td>
                    </tr>
                    <tr>
                        <th>Dibuat oleh</th>
                        <td>{{ $report->creator->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Progress Summary</th>
                        <td>{{ $report->progress_summary }}</td>
                    </tr>
                    <tr>
                        <th>Kendala</th>
                        <td>{{ $report->challenges ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Rencana Selanjutnya</th>
                        <td>{{ $report->next_plan ?? '-' }}</td>
                    </tr>
                </table>
                <h6 class="mt-4">Progress Task</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Task</th>
                            <th>Status</th>
                            <th>Progress Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($report->tasks as $task)
                            <tr>
                                <td>{{ $task->name }}</td>
                                <td><span
                                        class="badge bg-{{ $task->getStatusBadgeClass() }}">{{ $task->getStatusLabel() }}</span>
                                </td>
                                <td>{{ $task->pivot->progress_notes ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if ($report->attachments && count($report->attachments))
                    <h6 class="mt-4">Lampiran</h6>
                    <ul>
                        @foreach ($report->attachments as $file)
                            <li><a href="{{ Storage::url($file['path']) }}" target="_blank">{{ $file['name'] }}</a></li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
@endsection
