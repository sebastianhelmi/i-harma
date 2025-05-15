@extends('layouts.purchasing')

@section('title', 'Buat Purchase Order')

@section('content')
<div class="container-fluid" x-data="poForm">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Buat Purchase Order</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('purchasing.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('purchasing.spbs.index') }}">SPB</a></li>
                    <li class="breadcrumb-item active">Buat PO</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Warning if no items to order -->
    @if(!$hasItemsToOrder)
    <div class="alert alert-warning d-flex justify-content-between align-items-center">
        <div>
            <i class="fas fa-info-circle me-2"></i>
            Semua item tersedia di inventory. Tidak perlu membuat PO.
        </div>
        <form id="markNotRequiredForm" action="{{ route('purchasing.spbs.mark-not-required', $spb) }}" method="POST"
            class="d-inline">
            @csrf
            @method('PATCH')
            <button type="button" class="btn btn-success btn-sm" @click="confirmMarkNotRequired">
                <i class="fas fa-check me-1"></i>
                Tandai Tidak Perlu PO
            </button>
        </form>
    </div>
    @endif

    <!-- SPB Info -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Informasi SPB</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-sm">
                        <tr>
                            <td width="150">Nomor SPB</td>
                            <td>: {{ $spb->spb_number }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal SPB</td>
                            <td>: {{ $spb->spb_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td>Proyek</td>
                            <td>: {{ $spb->project->name }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-sm">
                        <tr>
                            <td width="150">Diminta Oleh</td>
                            <td>: {{ $spb->requester->name }}</td>
                        </tr>
                        <tr>
                            <td>Kategori</td>
                            <td>: {{ $spb->category_entry === 'site' ? 'Site' : 'Workshop' }}</td>
                        </tr>
                        <tr>
                            <td>Kategori Item</td>
                            <td>: {{ $spb->itemCategory->name }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- PO Form -->
    <form id="poForm" action="{{ route('purchasing.pos.store') }}" method="POST">
        @csrf
        <input type="hidden" name="spb_id" value="{{ $spb->id }}">

        <div class="row">
            <!-- PO Details -->
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Detail Purchase Order</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="supplier_id" class="form-label required">Supplier</label>
                                <select class="form-select @error('supplier_id') is-invalid @enderror"
                                    name="supplier_id" x-model="supplierId" required>
                                    <option value="">Pilih Supplier</option>
                                    @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="order_date" class="form-label required">Tanggal Order</label>
                                <input type="date" class="form-control @error('order_date') is-invalid @enderror"
                                    name="order_date" value="{{ old('order_date', date('Y-m-d')) }}" required>
                                @error('order_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="estimated_usage_date" class="form-label required">Perkiraan
                                    Penggunaan</label>
                                <input type="date"
                                    class="form-control @error('estimated_usage_date') is-invalid @enderror"
                                    name="estimated_usage_date" value="{{ old('estimated_usage_date') }}"
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                @error('estimated_usage_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="remarks" class="form-label">Catatan</label>
                                <textarea class="form-control @error('remarks') is-invalid @enderror" name="remarks"
                                    rows="2">{{ old('remarks') }}</textarea>
                                @error('remarks')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Daftar Item</h5>
                        <div class="badge-group">
                            <span class="badge bg-success">Tersedia di Inventory</span>
                            <span class="badge bg-warning">Stok Kurang</span>
                            <span class="badge bg-danger">Tidak Tersedia</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th>Nama Item</th>
                                        <th>Stok Inventory</th>
                                        <th>Jumlah Diminta</th>
                                        <th>Jumlah PO</th>
                                        <th>Satuan</th>
                                        <th>Harga Referensi</th>
                                        <th>Harga PO</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($spb->category_entry === 'site')
                                    @include('purchasing.pos._site_items_table')
                                    @else
                                    @include('purchasing.pos._workshop_items_table')
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="7" class="text-end">Total PO:</th>
                                        <th class="text-end" x-text="formatPrice(total)"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('purchasing.spbs.index') }}" class="btn btn-secondary">
                <i class="fas fa-times me-1"></i>Batal
            </a>
            @if($hasItemsToOrder)
            <button type="button" class="btn btn-primary" :disabled="!isValid" @click="confirmSubmit">
                <i class="fas fa-save me-1"></i>Buat PO
            </button>
            @endif
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .required:after {
        content: " *";
        color: red;
    }

    .badge-group .badge {
        margin-left: 0.5rem;
    }

    .table> :not(caption)>*>* {
        padding: 0.75rem;
    }

    .input-group-sm>.form-control {
        padding: 0.25rem 0.5rem;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
    Alpine.data('poForm', () => ({
        supplierId: '',
        items: {
            @if($spb->category_entry === 'site')
                @foreach($spb->siteItems as $item)
                    @if(!$item->available)
                        {{ $item->id }}: {
                            quantity: {{ $item->needed_quantity }},
                            unit_price: 0,
                            reference_price: {{ $item->inventory_unit_price ?? 0 }},
                            total: 0
                        },
                    @endif
                @endforeach
            @else
                @foreach($spb->workshopItems as $item)
                    @if(!$item->available)
                        {{ $item->id }}: {
                            quantity: {{ $item->needed_quantity }},
                            unit_price: 0,
                            reference_price: {{ $item->inventory_unit_price ?? 0 }},
                            total: 0
                        },
                    @endif
                @endforeach
            @endif
        },
        total: 0,

        calculateTotal() {
            this.total = 0;
            for (let id in this.items) {
                this.items[id].total = this.items[id].quantity * (parseFloat(this.items[id].unit_price) || 0);
                this.total += this.items[id].total;
            }
        },

        formatPrice(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(value || 0);
        },

        get isValid() {
            return this.supplierId &&
                this.total > 0 &&
                Object.values(this.items).every(item => parseFloat(item.unit_price) > 0);
        },

        confirmMarkNotRequired() {
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Yakin ingin menandai SPB ini tidak memerlukan PO?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Tandai',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('markNotRequiredForm');
                    const formData = new FormData(form);

                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire({
                            icon: data.icon,
                            title: data.title,
                            text: data.text
                        }).then(() => {
                            if (data.success && data.redirectTo) {
                                window.location.href = data.redirectTo;
                            }
                        });
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat memproses permintaan.'
                        });
                    });
                }
            });
        },
        confirmSubmit() {
            if (!this.isValid) return;

            Swal.fire({
                title: 'Konfirmasi',
                text: 'Yakin ingin membuat PO ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Buat PO',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('poForm');
                    const formData = new FormData(form);

                    // Submit with fetch
                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire({
                            icon: data.icon,
                            title: data.title,
                            text: data.text
                        }).then(() => {
                            if (data.success && data.redirectTo) {
                                window.location.href = data.redirectTo;
                            }
                        });
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat memproses permintaan.'
                        });
                    });
                }
            });
        }
    }));
});
</script>
@endpush