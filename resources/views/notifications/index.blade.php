<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="fas fa-bell me-2"></i>{{ __('Notifikasi') }}
            </h2>
            @if($notifications->whereNull('read_at')->count() > 0)
            <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-secondary btn-sm">
                    <i class="fas fa-check-double me-1"></i>{{ __('Tandai Semua Sudah Dibaca') }}
                </button>
            </form>
            @endif
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        @forelse($notifications as $notification)
                        <div
                            class="mb-3 p-3 rounded {{ $notification->read_at ? 'bg-light' : 'bg-primary bg-opacity-10' }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="fw-semibold mb-2">
                                        <i class="fas fa-file-alt me-2 text-primary"></i>
                                        Laporan Divisi {{ $notification->data['division_name'] }}
                                    </h5>
                                    <div class="text-secondary small">
                                        <p class="mb-1">
                                            <i class="fas fa-project-diagram me-2"></i>
                                            Proyek: {{ $notification->data['project_name'] }}
                                        </p>
                                        <p class="mb-1">
                                            <i class="fas fa-tag me-2"></i>
                                            Tipe: {{ ucfirst($notification->data['report_type']) }}
                                        </p>
                                        <p class="mb-0">
                                            <i class="fas fa-calendar me-2"></i>
                                            Tanggal: {{
                                            \Carbon\Carbon::parse($notification->data['report_date'])->format('d/m/Y')
                                            }}
                                        </p>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    @if(!$notification->read_at)
                                    <form action="{{ route('notifications.mark-read', $notification->id) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-check me-1"></i>{{ __('Tandai Dibaca') }}
                                        </button>
                                    </form>
                                    @endif
                                    <a href="{{ route('project-manager.reports.show', $notification->data['report_id']) }}"
                                        class="btn btn-dark btn-sm">
                                        <i class="fas fa-eye me-1"></i>{{ __('Lihat') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4 text-secondary">
                            <i class="fas fa-bell-slash fa-2x mb-2"></i>
                            <p class="mb-0">{{ __('Tidak ada notifikasi') }}</p>
                        </div>
                        @endforelse

                        <div class="mt-4">
                            {{ $notifications->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script type="module">
        // Auto-hide success messages
        const success = "{{ session('success') }}";
        if (success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: success,
                timer: 2000,
                showConfirmButton: false
            });
        }
    </script>
    @endpush
</x-app-layout>