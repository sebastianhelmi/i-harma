<div class="modal fade" id="addItemModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="itemForm" method="POST" action="{{ route('admin.inventory.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Item <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="item_name" required
                                placeholder="Masukkan nama item">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select" name="item_category_id" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Stok Awal <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="initial_stock" required min="0"
                                value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Stok <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="quantity" required min="0"
                                value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Satuan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="unit" required
                                placeholder="pcs, kg, liter, dll">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Harga Satuan</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" name="unit_price" min="0"
                                    step="0.01" placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lokasi Penyimpanan</label>
                            <input type="text" class="form-control" name="location"
                                placeholder="Gudang A, Rak B, dll">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Deskripsi tambahan (opsional)"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Batal
                </button>
                <button type="submit" form="itemForm" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Simpan
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .modal-lg {
            max-width: 800px;
        }

        .form-label {
            font-weight: 500;
        }

        .input-group-text {
            background-color: #f8f9fa;
        }

        .invalid-feedback {
            font-size: 80%;
        }
    </style>
@endpush
