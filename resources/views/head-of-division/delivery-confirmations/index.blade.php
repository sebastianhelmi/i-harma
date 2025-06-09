@extends('layouts.head-of-division')

@section('title', 'Konfirmasi Pengiriman')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Daftar Pengiriman yang Membutuhkan Konfirmasi</h5>
        </div>
        <div class="card-body">
            @if($plans->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <p class="text-muted">Tidak ada pengiriman yang perlu dikonfirmasi</p>
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No. Rencana</th>
                            <th>Tujuan</th>
                            <th>Tanggal Kirim</th>
                            <th>Ekspedisi</th>
                            <th>No. Kendaraan</th>
                            <th>Status</th>
                            <th>Dibuat Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($plans as $plan)
                        <tr>
                            <td>{{ $plan->plan_number }}</td>
                            <td>{{ $plan->destination }}</td>
                            <td>{{ optional($plan->deliveryNotes->first())->departure_date?->format('d/m/Y') ?? '-' }}
                            </td>
                            <td>{{ optional($plan->deliveryNotes->first())->expedition ?? '-' }}</td>
                            <td>{{ optional($plan->deliveryNotes->first())->vehicle_license_plate ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $plan->getStatusBadgeClass() }}">
                                    {{ $plan->getStatusLabel() }}
                                </span>
                            </td>
                            <td>{{ $plan->creator->name }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('head-of-division.delivery-confirmations.show', $plan) }}"
                                        class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-success"
                                        onclick="confirmDelivery({{ $plan->id }})">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="rejectDelivery({{ $plan->id }})">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $plans->links() }}
            </div>
            @endif
        </div>
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