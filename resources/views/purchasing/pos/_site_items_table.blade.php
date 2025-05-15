@foreach($spb->siteItems as $item)
<tr class="{{ $item->available ? 'table-success' : ($item->inventory_qty > 0 ? 'table-warning' : 'table-danger') }}">
    <td>{{ $item->item_name }}</td>
    <td class="text-center">{{ number_format($item->inventory_qty) }}</td>
    <td class="text-center">{{ number_format($item->quantity) }}</td>
    <td class="text-center">
        @if(!$item->available)
        <strong>{{ number_format($item->needed_quantity) }}</strong>
        @else
        <span class="text-muted">-</span>
        @endif
    </td>
    <td class="text-center">{{ $item->unit }}</td>
    <td class="text-end">
        @if($item->inventory_unit_price)
        <small class="text-muted">Rp {{ number_format($item->inventory_unit_price) }}</small>
        @else
        <span class="text-muted">-</span>
        @endif
    </td>
    <td>
        @if(!$item->available)
        <div class="input-group input-group-sm">
            <span class="input-group-text">Rp</span>
            <input type="number" class="form-control text-end" name="items[{{ $item->id }}][unit_price]"
                x-model="items[{{ $item->id }}].unit_price" @input="calculateTotal" min="0"
                :placeholder="items[{{ $item->id }}].reference_price">
        </div>
        <input type="hidden" name="items[{{ $item->id }}][type]" value="site">
        @else
        <span class="text-muted">-</span>
        @endif
    </td>
    <td class="text-end">
        @if(!$item->available)
        <strong x-text="formatPrice(items[{{ $item->id }}].total)"></strong>
        @else
        <span class="text-muted">-</span>
        @endif
    </td>
</tr>
@endforeach