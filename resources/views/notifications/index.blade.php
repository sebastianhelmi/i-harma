@extends('layouts.delivery') {{-- Atau layout lain yang sesuai --}}

@section('title', 'Notifikasi Saya')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Notifikasi Saya</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            @forelse($notifications as $notification)
                <a href="{{ route('notifications.read', [
                    'id' => $notification->id,
                    'redirect' => $notification->data['link'] ?? '#',
                ]) }}"
                   class="list-group-item list-group-item-action {{ $notification->read() ? '' : 'bg-light' }}">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">{{ $notification->data['message'] ?? 'Notifikasi Baru' }}</h5>
                        <small>{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                    <p class="mb-1">{{ $notification->data['description'] ?? '' }}</p>
                </a>
            @empty
                <p class="text-center">Tidak ada notifikasi.</p>
            @endforelse

            <div class="mt-3">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
