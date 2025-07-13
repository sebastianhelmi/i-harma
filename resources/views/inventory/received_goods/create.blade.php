@extends('layouts.inventory')

@section('title', 'Terima Barang')

@section('content')
    @push('styles')
        <style>
            .missing-item {
                background-color: #fff3cd; /* Yellow for missing */
            }
            .fully-received {
                background-color: #d4edda; /* Green for fully received */
            }
            .partially-received {
                background-color: #f8d7da; /* Red for partially received */
            }
        </style>
    @endpush
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Terima Barang</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('inventory.received-goods.index') }}">Penerimaan Barang</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $po->po_number }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <form action="{{ route('inventory.received-goods.store', $po) }}" method="POST">
            @csrf

            <div class="row">
                <!-- PO Info -->
                <div class="col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">Nomor PO</label>
                                    <input type="text" class="form-control" value="{{ $po->po_number }}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Supplier</label>
                                    <input type="text" class="form-control" value="{{ $po->supplier->name }}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Tanggal Order</label>
                                    <input type="text" class="form-control"
                                        value="{{ $po->order_date->format('d/m/Y') }}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Total PO</label>
                                    <input type="text" class="form-control"
                                        value="Rp {{ number_format($po->total_amount, 0, ',', '.') }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nama Item</th>
                                            <th>Satuan</th>
                                            <th>Jumlah Order</th>
                                            <th>Sudah Diterima</th>
                                            <th>Sisa</th>
                                            <th>Jumlah Diterima Sekarang</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($itemsWithStatus as $itemId => $status)
                                            @php
                                                $item = $status['po_item'];
                                                $rowClass = '';
                                                if (!$status['exists']) {
                                                    $rowClass = 'missing-item';
                                                } elseif ($status['remaining_quantity'] <= 0) {
                                                    $rowClass = 'fully-received';
                                                } elseif ($status['already_received'] > 0) {
                                                    $rowClass = 'partially-received';
                                                }
                                            @endphp
                                            <tr class="{{ $rowClass }}">
                                                <td>{{ $item->item_name }}</td>
                                                <td>{{ $item->unit }}</td>
                                                <td>{{ number_format($item->quantity, 0) }}</td>
                                                <td>{{ number_format($status['already_received'], 0) }}</td>
                                                <td>{{ number_format($status['remaining_quantity'], 0) }}</td>
                                                <td>
                                                    @if ($status['exists'])
                                                        <input type="hidden"
                                                            name="items[{{ $itemId }}][inventory_id]"
                                                            value="{{ $status['inventory']->id }}">
                                                        <input type="number" class="form-control"
                                                            name="items[{{ $itemId }}][quantity_received]"
                                                            min="0" max="{{ $status['remaining_quantity'] }}"
                                                            value="{{ old("items.{$itemId}.quantity_received", $status['remaining_quantity']) }}"
                                                            @if ($status['remaining_quantity'] <= 0) readonly @endif
                                                            required>
                                                    @else
                                                        <button type="button" class="btn btn-warning btn-sm"
                                                            onclick="showAddItemModal('{{ $item->item_name }}', '{{ $item->unit }}', {{ $itemId }})">
                                                            <i class="fas fa-plus me-1"></i>Tambah ke Inventori
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Remarks -->
                <div class="col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <label for="remarks" class="form-label">Catatan</label>
                            <textarea class="form-control" name="remarks" rows="3">{{ old('remarks') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('inventory.received-goods.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-1"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Simpan Penerimaan
                </button>
            </div>
        </form>
    </div>

    <!-- Add Item Modal -->
    <div class="modal fade" id="addItemModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Item ke Inventori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addItemForm">
                        <input type="hidden" id="poItemId">
                        <div class="mb-3">
                            <label class="form-label">Nama Item</label>
                            <input type="text" class="form-control" id="itemName" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Satuan</label>
                            <input type="text" class="form-control" id="itemUnit" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select class="form-select" id="itemCategory" required>
                                <option value="">Pilih Kategori</option>
                                @foreach (App\Models\ItemCategory::all() as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Harga Satuan</label>
                            <input type="number" class="form-control" id="unitPrice" required min="0">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="saveItem()">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection




@push('scripts')
    <script>
        let modal;
        let currentPoItemId;

        document.addEventListener('DOMContentLoaded', function() {
            modal = new bootstrap.Modal(document.getElementById('addItemModal'));
        });

        function showAddItemModal(itemName, itemUnit, poItemId) {
            document.getElementById('itemName').value = itemName;
            document.getElementById('itemUnit').value = itemUnit;
            document.getElementById('poItemId').value = poItemId;
            currentPoItemId = poItemId;
            modal.show();
        }

        function saveItem() {
            const data = {
                item_name: document.getElementById('itemName').value,
                unit: document.getElementById('itemUnit').value,
                item_category_id: document.getElementById('itemCategory').value,
                unit_price: document.getElementById('unitPrice').value
            };

            fetch('{{ route('inventory.received-goods.store-item') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload page to reflect changes
                        window.location.reload();
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: error.message
                    });
                });
        }
    </script>
@endpush
