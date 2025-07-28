<div class="tab-pane fade" id="workshop">
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
                @forelse($workshopOutputs as $output)
                    <tr>
                        <td>{{ $output->inventory->item_name ?? '-' }}</td>
                        <td>{{ $output->quantity_produced - $output->quantity_delivered }}</td>
                        <td>{{ $output->inventory?->unit ?? '-' }}</td>
                        <td>{{ $output->spb?->spb_number ?? '-' }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary"
                                onclick="selectItem('workshop_output', {{ $output->id }}, '{{ $output->inventory->item_name ?? '' }}', '{{ $output->inventory?->unit ?? '' }}', {{ $output->quantity_produced - $output->quantity_delivered }})">
                                <i class="fas fa-plus"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada hasil workshop</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
