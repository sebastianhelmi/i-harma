<div class="modal fade" id="completeWorkshopTaskModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="completeWorkshopTaskForm" action="{{ route('head-of-division.tasks.complete', $task->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="modal-header">
                    <h5 class="modal-title">Input Manual Hasil Produksi Workshop</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-warning mb-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Peringatan:</strong> Input barang dilakukan secara manual.
                        Pastikan data yang dimasukkan sesuai dengan hasil produksi yang sebenarnya.
                    </div>

                    <div class="mb-3">
                        <button type="button" class="btn btn-outline-primary" onclick="addProductionItem()">
                            <i class="fas fa-plus me-1"></i>Tambah Item Produksi
                        </button>
                    </div>

                    <div id="productionItems">
                        <!-- Dynamic items will be added here -->
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Simpan Hasil Produksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<template id="productionItemTemplate">
    <div class="card mb-3 production-item">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Barang</label>
                    <input type="text"
                           class="form-control"
                           name="outputs[INDEX][item_name]"
                           placeholder="Masukkan nama barang"
                           required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jumlah</label>
                    <input type="number"
                           class="form-control"
                           name="outputs[INDEX][quantity_produced]"
                           min="1"
                           placeholder="Jumlah"
                           required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Satuan</label>
                    <input type="text"
                           class="form-control"
                           name="outputs[INDEX][unit]"
                           placeholder="Satuan"
                           required>
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan Produksi</label>
                    <textarea class="form-control"
                              name="outputs[INDEX][notes]"
                              rows="2"
                              placeholder="Catatan tambahan (opsional)"></textarea>
                </div>
                <div class="col-12">
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeProductionItem(this)">
                        <i class="fas fa-trash me-1"></i>Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

@push('scripts')
<script>
let itemIndex = 0;

function addProductionItem() {
    const template = document.getElementById('productionItemTemplate').innerHTML;
    const newItem = template.replace(/INDEX/g, itemIndex++);
    document.getElementById('productionItems').insertAdjacentHTML('beforeend', newItem);
}

function removeProductionItem(button) {
    button.closest('.production-item').remove();
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('completeWorkshopTaskForm');

    // Add initial item
    addProductionItem();

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Validate form
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Show confirmation dialog
            Swal.fire({
                title: 'Konfirmasi Input Produksi',
                text: 'Pastikan data hasil produksi sudah benar. Data yang disimpan tidak dapat diubah.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Periksa Kembali',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    }
});
</script>
@endpush
