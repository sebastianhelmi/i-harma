@php
use App\Models\Packing;
@endphp

@props(['plan'])

@if($plan->status === 'packing')
<div class="card mt-4">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Data Packing</h5>
            <a href="{{ route('delivery.plans.packings.create', $plan) }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Packing
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>No. Packing</th>
                        <th>Jenis</th>
                        <th>Kategori</th>
                        <th>Dimensi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plan->packings as $packing)
                    <tr>
                        <td>{{ $packing->packing_number }}</td>
                        <td>{{ Packing::getTypes()[$packing->packing_type] }}</td>
                        <td>
                            <span @class([ 'badge' , 'bg-danger'=> $packing->packing_category === 'dangerous',
                                'bg-warning' => $packing->packing_category === 'fragile',
                                'bg-info' => $packing->packing_category === 'liquid',
                                'bg-secondary' => $packing->packing_category === 'normal',
                                'bg-primary' => $packing->packing_category === 'heavy',
                                ])>
                                {{ Packing::getCategories()[$packing->packing_category] }}
                            </span>
                        </td>
                        <td>{{ $packing->packing_dimensions }}</td>
                        <td>
                            <form action="{{ route('delivery.plans.packings.destroy', [$plan, $packing]) }}"
                                method="POST" class="d-inline"
                                onsubmit="return confirm('Yakin ingin menghapus data packing ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-3">
                            <i class="fas fa-box me-2"></i>Belum ada data packing
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif