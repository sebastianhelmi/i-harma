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