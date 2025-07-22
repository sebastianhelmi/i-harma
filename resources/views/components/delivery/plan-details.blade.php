@props(['plan', 'haveDeliveryNote'])


<div class="card mb-4">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Detail Rencana Pengiriman</h5>
            <div class="btn-group">
                @if ($plan->canCreateDeliveryNote() && !$haveDeliveryNote)
                    <a href="{{ route('delivery.notes.create', $plan) }}" class="btn btn-success">
                        <i class="fas fa-file-alt me-2"></i>Buat Surat Jalan
                    </a>
                @endif
                <a href="{{ route('delivery.plans.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                @if ($plan->canBeUpdated())
                    <a href="{{ route('delivery.plans.edit', $plan) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Edit
                    </a>
                @endif
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label text-muted">Project</label>
                    <p class="mb-0">{{ $plan->project->name ?? '-' }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted">Nomor Rencana</label>
                    <p class="fw-bold mb-0">{{ $plan->plan_number }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted">Tujuan</label>
                    <p class="mb-0">{{ $plan->destination }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted">Status</label>
                    <br>
                    <span class="badge bg-{{ $plan->getStatusBadgeClass() }}">
                        {{ $plan->getStatusLabel() }}
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label text-muted">Tanggal Rencana</label>
                    <p class="mb-0">{{ $plan->planned_date->format('d F Y') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted">Kendaraan</label>
                    <p class="mb-0">
                        {{ $plan->vehicle_count }} {{ Str::ucfirst($plan->vehicle_type) }}
                    </p>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted">Catatan</label>
                    <p class="mb-0">{{ $plan->delivery_notes ?: '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Status Actions -->
        @if ($plan->status !== 'cancelled' && $plan->status !== 'completed')
            <hr>
            <div class="d-flex justify-content-end gap-2">
                @if ($plan->status === 'draft')
                    <form action="{{ route('delivery.plans.update-status', $plan) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="packing">
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-box me-2"></i>Mulai Packing
                        </button>
                    </form>
                @endif

                @if ($plan->status === 'packing')
                    <form action="{{ route('delivery.plans.update-status', $plan) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="ready">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check me-2"></i>Siap Kirim
                        </button>
                    </form>
                @endif

                {{-- @if ($plan->status === 'ready')
            <form action="{{ route('delivery.plans.update-status', $plan) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="completed">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check-double me-2"></i>Selesai
                </button>
            </form>
            @endif --}}

                @if ($plan->canBeCancelled())
                    <button type="button" class="btn btn-danger" onclick="confirmCancel({{ $plan->id }})">
                        <i class="fas fa-times me-2"></i>Batalkan
                    </button>
                @endif
            </div>
        @endif
    </div>
</div>
