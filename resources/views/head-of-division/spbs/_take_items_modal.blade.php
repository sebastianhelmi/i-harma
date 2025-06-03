<div class="modal fade" id="takeItemsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Pengambilan Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Anda akan mengambil barang untuk SPB <strong id="spbNumber"></strong></p>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Pastikan jumlah dan kondisi barang sesuai sebelum mengkonfirmasi pengambilan.
                </div>

                <div class="table-responsive mt-3">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th width="100">Jumlah</th>
                                <th width="100">Satuan</th>
                                <th width="120">Status Stok</th>
                            </tr>
                        </thead>
                        <tbody id="itemsList"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="confirmTakeBtn">
                    <i class="fas fa-check me-1"></i>Konfirmasi Pengambilan
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let takeItemsModal;

document.addEventListener('DOMContentLoaded', function() {
    takeItemsModal = new bootstrap.Modal(document.getElementById('takeItemsModal'));
});

async function confirmTakeItems(spbId, spbNumber) {
    try {
        // Fetch items data
        const response = await fetch(`{{ url('head-of-division/spbs') }}/${spbId}/items`);
        const data = await response.json();

        if (!data.success) {
            throw new Error(data.message);
        }

        // Update modal content
        document.getElementById('spbNumber').textContent = spbNumber;

        const itemsList = document.getElementById('itemsList');
        itemsList.innerHTML = data.items.map(item => `
            <tr>
                <td>${item.name}</td>
                <td class="text-center">${item.quantity}</td>
                <td class="text-center">${item.unit}</td>
                <td class="text-center">
                    <span class="badge bg-success">Tersedia</span>
                </td>
            </tr>
        `).join('');

        // Setup confirm button
        const confirmBtn = document.getElementById('confirmTakeBtn');
        confirmBtn.onclick = () => submitTakeItems(spbId);

        // Show modal
        takeItemsModal.show();

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message
        });
    }
}

function submitTakeItems(spbId) {
    Swal.fire({
        title: 'Konfirmasi Pengambilan',
        text: 'Pastikan semua barang sudah sesuai. Lanjutkan?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Ambil',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ url('head-of-division/spbs') }}/${spbId}/take-items`;
            form.innerHTML = `@csrf @method('PATCH')`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush
