<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - @yield('title', 'Kepala Divisi')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/sass/head-of-division.scss'])
    @stack('styles')
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
                <span class="company-name">Head of Division</span>
            </div>

            <ul class="list-unstyled components">
                <li class="{{ request()->routeIs('head-of-division.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('head-of-division.dashboard') }}">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('head-of-division.projects*') ? 'active' : '' }}">
                    <a href="{{ route('head-of-division.projects.index') }}">
                        <i class="fas fa-folder"></i>
                        <span>Proyek</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('head-of-division.tasks*') ? 'active' : '' }}">
                    <a href="{{ route('head-of-division.tasks.index') }}">
                        <i class="fas fa-tasks"></i>
                        <span>Tugas</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('head-of-division.reports*') ? 'active' : '' }}">
                    <a href="{{ route('head-of-division.reports.index') }}" class="reports-link">
                        {{-- {{ request()->routeIs('kadiv.reports*') ? 'active' : '' }} --}}
                        {{-- {{ route('kadiv.reports.index') }} --}}
                        <i class="fas fa-chart-line"></i>
                        <span>Laporan</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('head-of-division.spbs*') ? 'active' : '' }}">
                    <a href="{{ route('head-of-division.spbs.index') }}">
                        <i class="fas fa-file-alt"></i>
                        <span>SPB</span>
                    </a>
                </li>
                @if (auth()->user()->division_id === 3) {{-- Civil Division ID --}}
                    <li class="{{ request()->routeIs('head-of-division.delivery-confirmations*') ? 'active' : '' }}">
                        <a href="{{ route('head-of-division.delivery-confirmations.index') }}">
                            <i class="fas fa-truck"></i>
                            <span>Konfirmasi Pengiriman</span>
                            @php
                                $pendingCount = \App\Models\DeliveryPlan::where('status', 'shipping')->count();
                            @endphp
                            @if ($pendingCount > 0)
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $pendingCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                @endif
                <li class="">
                    <a href="">
                        <i class="fas fa-cog"></i>
                        <span>Pengaturan</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div class="content">
            <!-- Top Navigation -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
                <div class="container-fluid">
                    <button id="sidebarCollapse" class="btn">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="navbar-title">
                        @yield('page-title', 'Dashboard')
                    </div>

                    <div class="d-flex align-items-center">
                        <!-- Notifications -->
                        <div class="dropdown notifications me-3">
                            <button class="btn position-relative" data-bs-toggle="dropdown">
                                <i class="fas fa-bell"></i>
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end notification-menu"
                                style="min-width: 350px; max-width: 400px;">
                                <h6 class="dropdown-header">Notifikasi</h6>
                                @php
                                    $spbApprovedNotifications = auth()
                                        ->user()
                                        ->unreadNotifications->where(
                                            'type',
                                            \App\Notifications\SpbApprovedNotification::class,
                                        );
                                    $taskAssignedNotifications = auth()
                                        ->user()
                                        ->unreadNotifications->where(
                                            'type',
                                            \App\Notifications\NewTaskAssignedNotification::class,
                                        );
                                @endphp
                                @foreach ($taskAssignedNotifications as $notification)
                                    <a href="{{ route('notifications.read', [
                                        'id' => $notification->id,
                                        'redirect' => route('head-of-division.tasks.show', $notification->data['task_id']),
                                    ]) }}"
                                        class="dropdown-item d-flex align-items-start" style="white-space: normal;">
                                        <div>
                                            <div><strong>Tugas Baru:</strong> {{ $notification->data['task_name'] }}
                                            </div>
                                            <div class="small text-muted">
                                                Proyek: {{ $notification->data['project_name'] }}<br>
                                                Dari: {{ $notification->data['assigned_by'] ?? '-' }}<br>
                                                Deadline:
                                                {{ $notification->data['due_date'] ? \Carbon\Carbon::parse($notification->data['due_date'])->format('d M Y') : '-' }}<br>
                                                <span>{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                                @foreach ($spbApprovedNotifications as $notification)
                                    <a href="{{ route('notifications.read', [
                                        'id' => $notification->id,
                                        'redirect' => route('head-of-division.spbs.show', $notification->data['spb_id']),
                                    ]) }}"
                                        class="dropdown-item d-flex align-items-start" style="white-space: normal;">
                                        <div>
                                            <div><strong>SPB #{{ $notification->data['spb_number'] }}</strong> telah
                                                disetujui</div>
                                            <div class="small text-muted">
                                                Proyek: {{ $notification->data['project_name'] }}<br>
                                                Disetujui oleh: {{ $notification->data['approved_by'] ?? '-' }}<br>
                                                <span>{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                                @if ($taskAssignedNotifications->isEmpty() && $spbApprovedNotifications->isEmpty())
                                    <div class="dropdown-item text-muted">Tidak ada notifikasi baru</div>
                                @endif
                            </div>
                        </div>

                        <!-- User Profile -->
                        <div class="dropdown profile">
                            <button class="btn d-flex align-items-center" data-bs-toggle="dropdown">
                                <img src="https://ui-avatars.com/api/?name=div class=" rounded-circle me-2"
                                    alt="Profile">
                                <div class="d-none d-md-block text-start">
                                    <div class="fw-medium">{{ auth()->user()->name }}</div>
                                    <small class="text-muted">Kepala Divisi</small>
                                </div>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                {{-- {{ route('kadiv.profile') }} --}}
                                <a class="dropdown-item" href="">
                                    <i class="fas fa-user me-2"></i> Profil Saya
                                </a>
                                {{-- {{ route('kadiv.settings.index') }} --}}
                                <a class="dropdown-item" href="">
                                    <i class="fas fa-cog me-2"></i> Pengaturan
                                </a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="main-content">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="footer">
                <div class="container text-center">
                    <span class="text-muted">© {{ date('Y') }} PT Manajemen Proyek. All rights reserved.</span>
                </div>
            </footer>
        </div>
    </div>

    @stack('scripts')
    @if (session('success'))
        <script type="module">
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @endif

    @if (session('error'))
        <script type="module">
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}"
            });
        </script>
    @endif
</body>

</html>
