@extends('layouts.delivery')

@section('title', 'Tambah Item Pengiriman')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Tambah Item Pengiriman</h5>
                    <a href="{{ route('delivery.plans.show', $plan) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                @include('delivery.plans.items.partials._filters')

                <ul class="nav nav-tabs mb-4" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#inventory">
                            <i class="fas fa-box me-2"></i>Dari Inventori
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#workshop">
                            <i class="fas fa-industry me-2"></i>Hasil Workshop
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#spb">
                            <i class="fas fa-file-alt me-2"></i>SPB Site
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#manual">
                            <i class="fas fa-plus me-2"></i>Manual
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    @include('delivery.plans.items.partials._inventory_items')
                    @include('delivery.plans.items.partials._workshop_outputs')
                    @include('delivery.plans.items.partials._site_spb_items')
                    @include('delivery.plans.items.partials._manual_input')
                </div>
            </div>
        </div>
    </div>

    <!-- Select Item Modal -->
    <div class="modal fade" id="selectItemModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('delivery.plans.items.store', $plan) }}" method="POST">
                    @csrf
                    <input type="hidden" name="source_type">
                    <input type="hidden" name="source_id">
                    <input type="hidden" name="inventory_id">

                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Item</label>
                            <input type="text" name="item_name" class="form-control" readonly>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label required">Jumlah</label>
                                    <input type="number" name="quantity" class="form-control" min="1" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Satuan</label>
                                    <input type="text" name="unit" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="item_notes" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="form-check mb-0">
                            <input type="checkbox" name="is_consigned" class="form-check-input" value="1">
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

    @push('scripts')
        <script>
            function selectItem(sourceType, sourceId, itemName, unit, maxQuantity) {
                const modal = new bootstrap.Modal(document.getElementById('selectItemModal'));
                const form = modal._element.querySelector('form');

                form.querySelector('[name="source_type"]').value = sourceType;
                form.querySelector('[name="source_id"]').value = sourceId;
                form.querySelector('[name="item_name"]').value = itemName;
                form.querySelector('[name="unit"]').value = unit;

                const quantityInput = form.querySelector('[name="quantity"]');
                quantityInput.max = maxQuantity;
                quantityInput.value = '';

                if (sourceType === 'inventory') {
                    form.querySelector('[name="inventory_id"]').value = sourceId;
                }

                modal.show();
            }

            document.addEventListener('DOMContentLoaded', function() {
                const forms = document.querySelectorAll('form');
                forms.forEach(form => {
                    form.addEventListener('submit', function(e) {
                        const quantity = this.querySelector('[name="quantity"]');
                        if (quantity && quantity.max && parseInt(quantity.value) > parseInt(quantity
                                .max)) {
                            e.preventDefault();
                            alert('Jumlah tidak boleh melebihi stok tersedia');
                        }
                    });
                });
            });
        </script>
    @endpush

    @push('styles')
        <style>
            .required::after {
                content: " *";
                color: red;
            }
        </style>
    @endpush
@endsection
