@extends('layouts.purchasing')

@section('title', 'Edit Purchase Order')

@section('content')
<div class="container-fluid" x-data="poForm">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Edit Purchase Order</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('purchasing.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('purchasing.pos.index') }}">PO</a></li>
                    <li class="breadcrumb-item active">Edit {{ $po->po_number }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- PO Form -->
    <form id="poForm" action="{{ route('purchasing.pos.update', $po) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- PO Details -->
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Detail Purchase Order</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Nomor PO</label>
                                <input type="text" class="form-control" value="{{ $po->po_number }}" readonly>
                            </div>

                            <div class="col-md-3">
                                <label for="supplier_id" class="form-label required">Supplier</label>
                                <select class="form-select @error('supplier_id') is-invalid @enderror"
                                    name="supplier_id" x-model="supplierId" required>
                                    <option value="">Pilih Supplier</option>
                                    @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ $supplier->id == $po->supplier_id ?
                                        'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="order_date" class="form-label required">Tanggal Order</label>
                                <input type="date" class="form-control @error('order_date') is-invalid @enderror"
                                    name="order_date" value="{{ old('order_date', $po->order_date->format('Y-m-d')) }}"
                                    required>
                                @error('order_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="estimated_usage_date" class="form-label required">Perkiraan
                                    Penggunaan</label>
                                <input type="date"
                                    class="form-control @error('estimated_usage_date') is-invalid @enderror"
                                    name="estimated_usage_date"
                                    value="{{ old('estimated_usage_date', $po->estimated_usage_date->format('Y-m-d')) }}"
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                @error('estimated_usage_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="remarks" class="form-label">Catatan</label>
                                <textarea class="form-control @error('remarks') is-invalid @enderror" name="remarks"
                                    rows="2">{{ old('remarks', $po->remarks) }}</textarea>
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
                    <div class="card-header">
                        <h5 class="card-title mb-0">Daftar Item</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Item</th>
                                        <th>Jumlah</th>
                                        <th>Satuan</th>
                                        <th>Harga Satuan</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($po->items as $item)
                                    <tr>
                                        <td>{{ $item->item_name }}</td>
                                        <td class="text-center">{{ number_format($item->quantity) }}</td>
                                        <td class="text-center">{{ $item->unit }}</td>
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control text-end"
                                                    name="items[{{ $item->id }}][unit_price]"
                                                    x-model="items[{{ $item->id }}].unit_price" @input="calculateTotal"
                                                    min="0" value="{{ $item->unit_price }}">
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <strong x-text="formatPrice(items[{{ $item->id }}].total)"></strong>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-end">Total:</th>
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
            <a href="{{ route('purchasing.pos.show', $po) }}" class="btn btn-secondary">
                <i class="fas fa-times me-1"></i>Batal
            </a>
            <button type="button" class="btn btn-primary" :disabled="!isValid" @click="confirmSubmit">
                <i class="fas fa-save me-1"></i>Simpan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
    Alpine.data('poForm', () => ({
        supplierId: '{{ $po->supplier_id }}',
        items: {
            @foreach($po->items as $item)
                {{ $item->id }}: {
                    quantity: {{ $item->quantity }},
                    unit_price: {{ $item->unit_price }},
                    total: {{ $item->total_price }}
                },
            @endforeach
        },
        total: {{ $po->total_amount }},

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

        confirmSubmit() {
            if (!this.isValid) return;

            Swal.fire({
                title: 'Konfirmasi',
                text: 'Yakin ingin menyimpan perubahan PO ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('poForm');
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
        }
    }));
});
</script>
@endpush