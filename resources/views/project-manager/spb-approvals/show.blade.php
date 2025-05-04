@extends('layouts.pm')

@section('title', 'Detail SPB')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Detail SPB</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('pm.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pm.spb-approvals.index') }}">Persetujuan SPB</a></li>
                    <li class="breadcrumb-item active">{{ $spb->spb_number }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <button type="button"
                    class="btn btn-success"
                    data-bs-toggle="modal"
                    data-bs-target="#approveModal">
                <i class="fas fa-check me-2"></i>Setujui
            </button>
            <button type="button"
                    class="btn btn-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#rejectModal">
                <i class="fas fa-times me-2"></i>Tolak
            </button>
            <a href="{{ route('pm.spb-approvals.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Main Info -->
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi SPB</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="30%">Nomor SPB</td>
                            <td>: {{ $spb->spb_number }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal</td>
                            <td>: {{ $spb->spb_date->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td>Proyek</td>
                            <td>: {{ $spb->project->name }}</td>
                        </tr>
                        <tr>
                            <td>Tugas</td>
                            <td>: {{ $spb->task->name }}</td>
                        </tr>
                        <tr>
                            <td>Kategori</td>
                            <td>: {{ $spb->itemCategory->name }}</td>
                        </tr>
                        <tr>
                            <td>Jenis Entry</td>
                            <td>: {{ $spb->category_entry === 'site' ? 'Site' : 'Workshop' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Pemohon</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="30%">Nama</td>
                            <td>: {{ $spb->requester->name }}</td>
                        </tr>
                        <tr>
                            <td>Divisi</td>
                            <td>: {{ $spb->requester->division->name }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Items -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Detail Item</h5>
        </div>
        <div class="card-body">
            @if($spb->category_entry === 'site')
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Item</th>
                                <th>Jumlah</th>
                                <th>Satuan</th>
                                <th>Keterangan</th>
                                <th>Dokumen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($spb->siteItems as $item)
                                <tr>
                                    <td>{{ $item->item_name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->unit }}</td>
                                    <td>{{ $item->information }}</td>
                                    <td>
                                        @if($item->document_file)
                                            @foreach($item->document_file as $file)
                                                <a href="{{ Storage::url($file) }}"
                                                   target="_blank"
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-file-alt"></i>
                                                </a>
                                            @endforeach
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Keterangan Item</th>
                                <th>Jumlah</th>
                                <th>Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($spb->workshopItems as $item)
                                <tr>
                                    <td>{{ $item->explanation_items }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->unit }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    @if($spb->remarks)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Catatan</h5>
            </div>
            <div class="card-body">
                {{ $spb->remarks }}
            </div>
        </div>
    @endif
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('pm.spb-approvals.approve', $spb) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Persetujuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Anda yakin ingin menyetujui SPB ini?</p>
                    <div class="mb-3">
                        <label class="form-label">Catatan (opsional)</label>
                        <textarea name="remarks" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Setuju</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('pm.spb-approvals.reject', $spb) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Penolakan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Alasan Penolakan</label>
                        <textarea name="rejection_reason"
                                  class="form-control @error('rejection_reason') is-invalid @enderror"
                                  rows="3"
                                  required></textarea>
                        @error('rejection_reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
