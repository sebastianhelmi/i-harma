<div class="tab-pane fade" id="spb">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th>SPB</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siteSpbItems as $item)
                    <tr>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->unit }}</td>
                        <td>{{ $item->spb->spb_number }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary"
                                onclick="selectItem('site_spb', {{ $item->id }}, '{{ $item->item_name }}', '{{ $item->unit }}', {{ $item->quantity }})">
                                <i class="fas fa-plus"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada permintaan site</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>