@extends('layouts.purchasing')

@section('title', 'Buat Purchase Order')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Buat Purchase Order</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('purchasing.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('purchasing.pos.index') }}">Purchase Orders</a></li>
                    <li class="breadcrumb-item active">Buat PO</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('purchasing.pos.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('purchasing.pos.store') }}" method="POST" x-data="poForm">
        @csrf
        <input type="hidden" name="spb_id" value="{{ $spb->id }}">

        <div class="row">
            <div class="col-md-8">
                <!-- SPB Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Informasi SPB</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <dl class="row mb-0">
                                    <dt class="col-sm-4">No. SPB</dt>
                                    <dd class="col-sm-8">{{ $spb->spb_number }}</dd>

                                    <dt class="col-sm-4">Tanggal SPB</dt>
                                    <dd class="col-sm-8">{{ $spb->spb_date->format('d/m/Y') }}</dd>

                                    <dt class="col-sm-4">Proyek</dt>
                                    <dd class="col-sm-8">{{ $spb->project->name }}</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl class="row mb-0">
                                    <dt class="col-sm-4">Task</dt>
                                    <dd class="col-sm-8">{{ $spb->task->name }}</dd>

                                    <dt class="col-sm-4">Diminta Oleh</dt>
                                    <dd class="col-sm-8">{{ $spb->requester->name }}</dd>

                                    <dt class="col-sm-4">Kategori</dt>
                                    <dd class="col-sm-8">{{ $spb->category_entry === 'site' ? 'Site' : 'Workshop' }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Detail Item</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nama Item</th>
                                        <th>Satuan</th>
                                        <th class="text-center">Jumlah</th>
                                        <th>Harga Satuan</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($spb->category_entry === 'site')
                                        @foreach($spb->siteItems as $item)
                                            <tr>
                                                <td>{{ $item->item_name }}</td>
                                                <td>{{ $item->unit }}</td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td>
                                                    <input type="hidden"
                                                           name="items[{{ $loop->index }}][id]"
                                                           value="{{ $item->id }}">
                                                    <input type="hidden"
                                                           name="items[{{ $loop->index }}][type]"
                                                           value="site">
                                                    <input type="number"
                                                           class="form-control"
                                                           name="items[{{ $loop->index }}][unit_price]"
                                                           x-model="items[{{ $loop->index }}].unitPrice"
                                                           @input="calculateTotal"
                                                           required>
                                                </td>
                                                <td x-text="formatCurrency(items[{{ $loop->index }}].total)"></td>
                                            </tr>
                                        @endforeach
                                    @else
                                        @foreach($spb->workshopItems as $item)
                                            <tr>
                                                <td>{{ $item->explanation_items }}</td>
                                                <td>{{ $item->unit }}</td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td>
                                                    <input type="hidden"
                                                           name="items[{{ $loop->index }}][id]"
                                                           value="{{ $item->id }}">
                                                    <input type="hidden"
                                                           name="items[{{ $loop->index }}][type]"
                                                           value="workshop">
                                                    <input type="number"
                                                           class="form-control"
                                                           name="items[{{ $loop->index }}][unit_price]"
                                                           x-model="items[{{ $loop->index }}].unitPrice"
                                                           @input="calculateTotal"
                                                           required>
                                                </td>
                                                <td x-text="formatCurrency(items[{{ $loop->index }}].total)"></td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-end fw-bold">Total:</td>
                                        <td x-text="formatCurrency(total)" class="fw-bold"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- PO Details -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Detail PO</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label required">Supplier</label>
                            <select name="supplier_id"
                                    class="form-select @error('supplier_id') is-invalid @enderror"
                                    x-model="supplierId"
                                    @change="updateCompanyName"
                                    required>
                                <option value="">Pilih Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                            data-company="{{ $supplier->company_name }}">
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Nama Perusahaan</label>
                            <input type="text"
                                   name="company_name"
                                   class="form-control @error('company_name') is-invalid @enderror"
                                   x-model="companyName"
                                   required>
                            @error('company_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Tanggal Order</label>
                            <input type="date"
                                   name="order_date"
                                   class="form-control @error('order_date') is-invalid @enderror"
                                   value="{{ date('Y-m-d') }}"
                                   required>
                            @error('order_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Estimasi Tanggal Penggunaan</label>
                            <input type="date"
                                   name="estimated_usage_date"
                                   class="form-control @error('estimated_usage_date') is-invalid @enderror"
                                   required>
                            @error('estimated_usage_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea name="remarks"
                                      class="form-control @error('remarks') is-invalid @enderror"
                                      rows="3"></textarea>
                            @error('remarks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i>Buat Purchase Order
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('poForm', () => ({
        supplierId: '',
        companyName: '',
        items: [
            @if($spb->category_entry === 'site')
                @foreach($spb->siteItems as $item)
                    {
                        quantity: {{ $item->quantity }},
                        unitPrice: '',
                        total: 0
                    },
                @endforeach
            @else
                @foreach($spb->workshopItems as $item)
                    {
                        quantity: {{ $item->quantity }},
                        unitPrice: '',
                        total: 0
                    },
                @endforeach
            @endif
        ],
        total: 0,

        updateCompanyName() {
            const option = this.$el.querySelector(`option[value="${this.supplierId}"]`);
            this.companyName = option ? option.dataset.company : '';
        },

        calculateTotal() {
            this.items.forEach(item => {
                item.total = item.quantity * (item.unitPrice || 0);
            });
            this.total = this.items.reduce((sum, item) => sum + item.total, 0);
        },

        formatCurrency(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(value);
        }
    }));
});
</script>
@endpush
