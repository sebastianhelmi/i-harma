@extends('layouts.head-of-division')

@section('title', 'Detail Pengiriman')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Pengiriman {{ $plan->plan_number }}</h5>
                <div>
                    <a href="{{ route('head-of-division.delivery-confirmations.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                    <button type="button" class="btn btn-success" onclick="confirmDelivery({{ $plan->id }})">
                        <i class="fas fa-check me-1"></i>Setujui
                    </button>
                    <button type="button" class="btn btn-danger" onclick="rejectDelivery({{ $plan->id }})">
                        <i class="fas fa-times me-1"></i>Tolak
                    </button>
                </div>
            </div>
        </div>

        <!-- Delivery Plan Details -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Informasi Pengiriman</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td width="35%">Nomor Rencana</td>
                            <td>: {{ $plan->plan_number }}</td>
                        </tr>
                        <tr>
                            <td>Tujuan</td>
                            <td>: {{ $plan->destination }}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>: <span class="badge bg-{{ $plan->getStatusBadgeClass() }}">{{ $plan->getStatusLabel()
                                    }}</span></td>
                        </tr>
                        <tr>
                            <td>Dibuat Oleh</td>
                            <td>: {{ $plan->creator->name }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Delivery Note Details -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Detail Surat Jalan</h6>
                </div>
                <div class="card-body">
                    @if($note = $plan->deliveryNotes->first())
                    <table class="table table-sm">
                        <tr>
                            <td width="35%">Nomor Surat Jalan</td>
                            <td>: {{ $note->delivery_note_number }}</td>
                        </tr>
                        <tr>
                            <td>Ekspedisi</td>
                            <td>: {{ $note->expedition }}</td>
                        </tr>
                        <tr>
                            <td>Jenis Kendaraan</td>
                            <td>: {{ $note->vehicle_type }}</td>
                        </tr>
                        <tr>
                            <td>Nomor Polisi</td>
                            <td>: {{ $note->vehicle_license_plate }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal Kirim</td>
                            <td>: {{ $note->departure_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td>Estimasi Tiba</td>
                            <td>: {{ $note->estimated_arrival_date->format('d/m/Y') }}</td>
                        </tr>
                    </table>
                    @else
                    <p class="text-muted mb-0">Belum ada surat jalan</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Items List -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Daftar Barang</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                    <th>Packing</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($plan->draftItems as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->item_name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->unit }}</td>
                                    <td>{{ optional($item->packing)->packing_number ?? '-' }}</td>
                                    <td>{{ $item->item_notes }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents -->
        @if($note = $plan->deliveryNotes->first())
        @if($doc = $note->document)
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Dokumentasi</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach(['stnk_photo' => 'STNK',
                        'license_plate_photo' => 'Plat Nomor',
                        'vehicle_photo' => 'Kendaraan',
                        'driver_license_photo' => 'SIM Driver',
                        'driver_id_photo' => 'KTP Driver',
                        'loading_process_photo' => 'Proses Muat'] as $field => $label)
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $label }}</h6>
                                    <img src="{{ Storage::url($doc->$field) }}" class="img-fluid rounded"
                                        alt="{{ $label }}">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endif
    </div>
</div>

@push('scripts')
<script>
    function confirmDelivery(planId) {
    Swal.fire({
        title: 'Konfirmasi Pengiriman',
        text: 'Apakah Anda yakin ingin mengkonfirmasi pengiriman ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Konfirmasi',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ url('head-of-division/delivery-confirmations') }}/${planId}/approve`;
            form.innerHTML = `@csrf`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function rejectDelivery(planId) {
    Swal.fire({
        title: 'Tolak Pengiriman',
        text: 'Masukkan alasan penolakan:',
        input: 'text',
        inputAttributes: {
            required: true,
            placeholder: 'Alasan penolakan...'
        },
        showCancelButton: true,
        confirmButtonText: 'Tolak',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        inputValidator: (value) => {
            if (!value) {
                return 'Alasan penolakan harus diisi!';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ url('head-of-division/delivery-confirmations') }}/${planId}/reject`;
            form.innerHTML = `
                @csrf
                <input type="hidden" name="rejection_reason" value="${result.value}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush
@endsection