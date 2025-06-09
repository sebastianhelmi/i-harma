@extends('layouts.inventory')

@section('title', 'Detail Barang Keluar')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">Detail Transaksi Keluar</h5>
                </div>
                <div class="col-auto">
                    <a href="{{ route('inventory.outgoing.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <!-- Transaction Details -->
                <div class="col-md-6">
                    <h6 class="mb-3">Informasi Transaksi</h6>
                    <table class="table table-bordered">
                        <tr>
                            <th width="200">Tanggal</th>
                            <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Ditangani Oleh</th>
                            <td>{{ $transaction->handler->name }}</td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td>{{ $transaction->remarks ?? '-' }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Item Details -->
                <div class="col-md-6">
                    <h6 class="mb-3">Informasi Barang</h6>
                    <table class="table table-bordered">
                        <tr>
                            <th width="200">Nama Barang</th>
                            <td>{{ $transaction->inventory->item_name }}</td>
                        </tr>
                        <tr>
                            <th>Jumlah</th>
                            <td>{{ number_format($transaction->quantity) }} {{ $transaction->inventory->unit }}</td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>{{ $transaction->inventory->itemCategory->name }}</td>
                        </tr>
                    </table>
                </div>

                <!-- PO Details if exists -->
                @if($transaction->po)
                <div class="col-12">
                    <h6 class="mb-3">Informasi PO</h6>
                    <table class="table table-bordered">
                        <tr>
                            <th width="200">Nomor PO</th>
                            <td>{{ $transaction->po->po_number }}</td>
                        </tr>
                        <tr>
                            <th>Nomor SPB</th>
                            <td>{{ $transaction->po->spb->spb_number }}</td>
                        </tr>
                        <tr>
                            <th>Proyek</th>
                            <td>{{ $transaction->po->spb->project->name }}</td>
                        </tr>
                        <tr>
                            <th>Diminta Oleh</th>
                            <td>{{ $transaction->po->spb->requester->name }}</td>
                        </tr>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection