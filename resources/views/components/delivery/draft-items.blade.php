@props(['plan'])

@if($plan->status === 'packing')
<div class="card mt-4">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Item yang Akan Dikirim</h5>
            <a href="{{ route('delivery.plans.items.create', $plan) }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Item
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Nama Item</th>
                        <th>Jumlah</th>
                        <th>Satuan</th>
                        <th>Sumber</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plan->draftItems as $item)
                    <tr>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->unit }}</td>
                        <td>
                            @switch($item->source_type)
                            @case('inventory')
                            <span class="badge bg-primary">Inventori</span>
                            @break
                            @case('workshop_output')
                            <span class="badge bg-info">Workshop</span>
                            @break
                            @case('site_spb')
                            <span class="badge bg-warning">SPB Site</span>
                            @break
                            @default
                            <span class="badge bg-secondary">Manual</span>
                            @endswitch
                        </td>
                        <td>
                            @if($item->is_consigned)
                            <span class="badge bg-info">Titipan</span>
                            @else
                            <span class="badge bg-secondary">Regular</span>
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger"
                                onclick="confirmDeleteItem({{ $item->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-3">
                            <i class="fas fa-box me-2"></i>Belum ada item
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Hidden Delete Form -->
<form id="delete-item-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endif

@push('scripts')
<script>
    function confirmDeleteItem(itemId) {
    Swal.fire({
        title: 'Hapus Item?',
        text: "Item akan dihapus dari rencana pengiriman",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('delete-item-form');
            form.action = `{{ url('delivery/plans/items') }}/${itemId}`;
            form.submit();
        }
    });
}
</script>
@endpush