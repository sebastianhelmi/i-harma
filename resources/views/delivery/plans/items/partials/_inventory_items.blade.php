<div class="tab-pane fade show active" id="inventory">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nama Item</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Satuan</th>
                    <th>SPB Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inventoryItems as $item)
                    <tr>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ $item->itemCategory->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->unit }}</td>
                        <td>
                            @if ($item->siteSpbs->first())
                                <span class="badge bg-success">SPB Disetujui</span>
                            @else
                                <span class="badge bg-warning">Menunggu SPB</span>
                            @endif
                        </td>
                        <td>
                            @if ($item->siteSpbs->first())
                                <button type="button" class="btn btn-sm btn-primary"
                                    onclick="selectItem('inventory', {{ $item->id }}, '{{ $item->item_name }}', '{{ $item->unit }}', {{ $item->quantity }})">
                                    <i class="fas fa-plus"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada item tersedia</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>