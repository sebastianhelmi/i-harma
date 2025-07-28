@extends('layouts.delivery')
@section('title', 'Detail Rencana Pengiriman')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Main Information -->
            <div class="col-md-8">
                <x-delivery.plan-details :plan="$plan" :haveDeliveryNote="$haveDeliveryNote" />

                <!-- Action Buttons -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Aksi Rencana Pengiriman</h5>
                    </div>
                    <div class="card-body d-flex gap-2">
                        @if ($plan->status === 'ready' && $haveDeliveryNote)
                            <form action="{{ route('delivery.plans.update-status', $plan) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="delivering">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-truck me-1"></i>Mulai Pengiriman
                                </button>
                            </form>
                        @elseif($plan->status === 'delivering')
                            <a href="{{ route('delivery.plans.confirm.form', $plan) }}" class="btn btn-success">
                                <i class="fas fa-check-circle me-1"></i>Konfirmasi Penerimaan Barang
                            </a>
                        @endif

                        @if ($plan->canBeUpdated())
                            <a href="{{ route('delivery.plans.edit', $plan) }}" class="btn btn-info">
                                <i class="fas fa-edit me-1"></i>Edit Rencana
                            </a>
                        @endif

                        @if ($plan->canBeCancelled())
                            <button type="button" class="btn btn-danger" onclick="confirmCancel('{{ $plan->id }}')">
                                <i class="fas fa-times-circle me-1"></i>Batalkan Rencana
                            </button>
                        @endif
                    </div>
                </div>

                <x-delivery.packing-list :plan="$plan" />
            </div>

            <!-- Add this after the Packing section -->
            <x-delivery.draft-items :plan="$plan" />

            <!-- Add Item Modal -->
            <div class="modal fade" id="addItemModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('delivery.plans.items.store', $plan) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah Item</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Item Name -->
                                <div class="mb-3">
                                    <label class="form-label required">Nama Item</label>
                                    <input type="text" name="item_name"
                                        class="form-control @error('item_name') is-invalid @enderror"
                                        value="{{ old('item_name') }}" required>
                                    @error('item_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <!-- Quantity -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Jumlah</label>
                                            <input type="number" name="quantity"
                                                class="form-control @error('quantity') is-invalid @enderror"
                                                value="{{ old('quantity') }}" min="1" required>
                                            @error('quantity')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Unit -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Satuan</label>
                                            <input type="text" name="unit"
                                                class="form-control @error('unit') is-invalid @enderror"
                                                value="{{ old('unit') }}" required>
                                            @error('unit')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Notes -->
                                <div class="mb-3">
                                    <label class="form-label">Keterangan</label>
                                    <textarea name="item_notes" class="form-control @error('item_notes') is-invalid @enderror" rows="2">{{ old('item_notes') }}</textarea>
                                    @error('item_notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Is Consigned -->
                                <div class="form-check mb-0">
                                    <input type="checkbox" name="is_consigned" class="form-check-input" value="1"
                                        {{ old('is_consigned') ? 'checked' : '' }}>
                                    <label class="form-check-label">Item Titipan</label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar Information -->
            <div class="col-md-4 mt-4">
                <!-- Audit Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Informasi</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted">Dibuat Oleh</label>
                            <p class="mb-0">{{ $plan->creator->name }}</p>
                            <small class="text-muted">{{ $plan->created_at->format('d M Y H:i') }}</small>
                        </div>
                        @if ($plan->updated_by)
                            <div class="mb-0">
                                <label class="form-label text-muted">Diubah Oleh</label>
                                <p class="mb-0">{{ $plan->updater->name }}</p>
                                <small class="text-muted">{{ $plan->updated_at->format('d M Y H:i') }}</small>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Related Documents -->
                @if ($plan->deliveryNotes->isNotEmpty())
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Surat Jalan</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                @foreach ($plan->deliveryNotes as $note)
                                    <a href="#" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">{{ $note->delivery_note_number }}</h6>
                                            <small>{{ $note->departure_date->format('d/m/Y') }}</small>
                                        </div>
                                        <p class="mb-1">{{ $note->expedition }}</p>
                                        <small>{{ $note->vehicle_license_plate }} ({{ $note->vehicle_type }})</small>
                                    </a>
                                    <a href="{{ route('delivery.notes.print', $note) }}" class="btn btn-sm btn-primary"
                                        target="_blank">
                                        <i class="fas fa-print me-1"></i>Cetak
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Add Item Modal -->
    @if ($plan->canBeUpdated())
        <div class="modal fade" id="addItemModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('delivery.plans.items.store', $plan) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Item</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label required">Nama Item</label>
                                <input type="text" name="item_name" class="form-control" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Jumlah</label>
                                        <input type="number" name="quantity" class="form-control" min="1"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Satuan</label>
                                        <input type="text" name="unit" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea name="item_notes" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="mb-0">
                                <div class="form-check">
                                    <input type="checkbox" name="is_consigned" class="form-check-input" value="1">
                                    <label class="form-check-label">Item Titipan</label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Hidden Delete Forms -->
        <form id="delete-form" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>

        
    @endif

    @push('scripts')
        <script>
            function confirmCancel(planId) {
                Swal.fire({
                    title: 'Batalkan Rencana?',
                    text: "Rencana pengiriman akan dibatalkan",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Batalkan',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('delete-form');
                        form.action = `{{ url('delivery/plans') }}/${planId}`;
                        form.submit();
                    }
                });
            }

            
        </script>
    @endpush

    @push('styles')
        <style>
            .card {
                box-shadow: 0 0 10px rgba(0, 0, 0, .1);
            }

            .required::after {
                content: " *";
                color: red;
            }
        </style>
    @endpush
@endsection
