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
                <!-- Inventory Items -->
                <div class="tab-pane fade show active" id="inventory">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Item</th>
                                    <th>Kategori</th>
                                    <th>Stok</th>
                                    <th>Satuan</th>
                                    <th>SPB Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inventoryItems as $item)
                                <tr>
                                    <td>{{ $item->item_name }}</td>
                                    <td>{{ $item->itemCategory->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->unit }}</td>
                                    <td>
                                        @php
                                        $siteSPB = App\Models\SiteSpb::where('item_name', $item->item_name)
                                        ->whereNull('delivery_plan_id')
                                        ->whereHas('spb', function($q) {
                                        $q->where('status', 'approved');
                                        })
                                        ->first();
                                        @endphp
                                        @if($siteSPB)
                                        <span class="badge bg-success">SPB Disetujui</span>
                                        @else
                                        <span class="badge bg-warning">Menunggu SPB</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($siteSPB)
                                        <button type="button" class="btn btn-sm btn-primary"
                                            onclick="selectItem('inventory', {{ $item->id }}, '{{ $item->item_name }}', '{{ $item->unit }}', {{ $item->quantity }})">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada item tersedia</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Workshop Outputs -->
                <div class="tab-pane fade" id="workshop">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                    <th>SPB</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($workshopOutputs as $output)
                                <tr>
                                    <td>{{ $output->workshopSpb->explanation_items }}</td>
                                    <td>{{ $output->quantity_produced }}</td>
                                    <td>{{ $output->workshopSpb->unit }}</td>
                                    <td>{{ $output->spb->spb_number }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary"
                                            onclick="selectItem('workshop_output', {{ $output->id }}, '{{ $output->workshopSpb->explanation_items }}', '{{ $output->workshopSpb->unit }}', {{ $output->quantity_produced }})">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada hasil workshop</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Site SPB Items -->
                <div class="tab-pane fade" id="spb">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                    <th>SPB</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($siteSpbItems as $item)
                                <tr>
                                    <td>{{ $item->item_name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->unit }}</td>
                                    <td>{{ $item->spb->spb_number }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary"
                                            onclick="selectItem('site_spb', {{ $item->id }}, '{{ $item->item_name }}', '{{ $item->unit }}', {{ $item->quantity }})">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada permintaan site</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Manual Input -->
                <div class="tab-pane fade" id="manual">
                    <form id="manualForm" action="{{ route('delivery.plans.items.store', $plan) }}" method="POST">
                        @csrf
                        <input type="hidden" name="source_type" value="manual">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label required">Nama Item</label>
                                <input type="text" name="item_name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">Jumlah</label>
                                <input type="number" name="quantity" class="form-control" min="1" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">Satuan</label>
                                <input type="text" name="unit" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Keterangan</label>
                                <textarea name="item_notes" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" name="is_consigned" class="form-check-input" value="1">
                                    <label class="form-check-label">Item Titipan</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
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
            if (quantity && quantity.max && parseInt(quantity.value) > parseInt(quantity.max)) {
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