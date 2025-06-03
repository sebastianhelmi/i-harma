@extends('layouts.head-of-division')

@section('title', 'Daftar SPB')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Daftar SPB</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('head-of-division.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">SPB</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('head-of-division.spbs.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Buat SPB
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Search & Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('head-of-division.spbs.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text"
                               class="form-control"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Cari nomor SPB atau proyek...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="project_id" class="form-select">
                        <option value="">Semua Proyek</option>
                        @foreach($projects as $id => $name)
                            <option value="{{ $id }}" @selected(request('project_id') == $id)>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                        <option value="approved" @selected(request('status') == 'approved')>Disetujui</option>
                        <option value="rejected" @selected(request('status') == 'rejected')>Ditolak</option>
                        <option value="completed" @selected(request('status') == 'completed')>Selesai</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary w-100">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- SPB Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Nomor SPB</th>
                            <th>Tanggal</th>
                            <th>Proyek</th>
                            <th>Tugas</th>
                            <th>Kategori Item</th>
                            <th>Jenis</th>
                            <th>Status</th>
                            <th>Status PO</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($spbs as $spb)
                            <tr>
                                <td>{{ $spb->spb_number }}</td>
                                <td>{{ $spb->spb_date->format('d M Y') }}</td>
                                <td>{{ $spb->project->name }}</td>
                                <td>{{ $spb->task->name }}</td>
                                <td>{{ $spb->itemCategory->name }}</td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $spb->category_entry === 'site' ? 'Site' : 'Workshop' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $spb->getStatusBadgeClass() }}">
                                        {{ match($spb->status) {
                                            'pending' => 'Pending',
                                            'approved' => 'Disetujui',
                                            'rejected' => 'Ditolak',
                                            'completed' => 'Selesai',
                                        } }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ match($spb->status_po) {
                                            'waiting' => 'Menunggu',
                                            'not_required' => 'Tidak Perlu PO',
                                            'pending' => 'Menunggu PO',
                                            'ordered' => 'Sudah PO',
                                            'completed' => 'PO Selesai',
                                        } }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('head-of-division.spbs.show', $spb) }}"
                                           class="btn btn-sm btn-info"
                                           title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if($spb->can_take_items)
                                            <button type="button"
                                                    class="btn btn-sm btn-success"
                                                    title="Ambil Barang"
                                                    onclick="confirmTakeItems('{{ $spb->id }}', '{{ $spb->spb_number }}')">
                                                <i class="fas fa-hand-holding"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="text-muted">Tidak ada SPB yang ditemukan</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $spbs->links() }}
            </div>
        </div>
    </div>
</div>


@push('styles')
<style>
    .badge {
        padding: 0.5em 0.75em;
    }
    .table > :not(caption) > * > * {
        padding: 1rem 0.75rem;
    }
</style>
@endpush
@endsection

<!-- Add this modal before the @push('scripts') -->
<div class="modal fade" id="takeItemsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Pengambilan Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Anda akan mengambil barang untuk SPB <strong id="spbNumber"></strong></p>

                <div class="table-responsive mt-3">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Satuan</th>
                            </tr>
                        </thead>
                        <tbody id="itemsList"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="confirmTakeBtn">
                    <i class="fas fa-check me-1"></i>Ambil Barang
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
                <td>${item.quantity}</td>
                <td>${item.unit}</td>
            </tr>
        `).join('');

        // Setup confirm button
        document.getElementById('confirmTakeBtn').onclick = () => submitTakeItems(spbId);

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
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `{{ url('head-of-division/spbs') }}/${spbId}/take-items`;
    form.innerHTML = `@csrf @method('PATCH')`;
    document.body.appendChild(form);
    form.submit();
}
</script>
@endpush
