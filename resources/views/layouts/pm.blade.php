<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - @yield('title', 'Project Manager')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/sass/pm.scss'])
</head>

<body class="bg-light">
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
                <span class="company-name">Project Manager</span>
            </div>

            <ul class="list-unstyled components">
                <li class="{{ request()->routeIs('pm.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('pm.dashboard') }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('pm.projects*') ? 'active' : '' }}">
                    <a href="{{ route('pm.projects.index') }}">
                        <i class="fas fa-building"></i>
                        <span>Proyek</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('pm.tasks*') ? 'active' : '' }}">
                    <a href="{{ route('pm.tasks.index') }}">
                        <i class="fas fa-tasks"></i>
                        <span>Tugas</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('pm.spb-approvals*') ? 'active' : '' }}">
                    <a href="{{ route('pm.spb-approvals.index') }}">
                        <i class="fas fa-file-alt"></i>
                        <span>SPB</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('pm.riwayat*') ? 'active' : '' }}">
                    <a href="{{ route('pm.riwayat.index') }}">
                        <i class="fas fa-history"></i>
                        <span>Riwayat Pengadaan</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('pm.reports*') ? 'active' : '' }}">
                    <a href="{{ route('pm.reports.index') }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Laporan</span>
                    </a>
                </li>
                {{-- {{ request()->routeIs('pm.settings*') ? 'active' : '' }} --}}
                {{-- <li class=""> --}}
                {{-- {{ route('pm.settings.index') }} --}}
                {{-- <a href="">
                        <i class="fas fa-cog"></i>
                        <span>Pengaturan</span>
                    </a>
                </li> --}}
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
                        <div class="dropdown notifications me-3">
                            <button class="btn position-relative" data-bs-toggle="dropdown">
                                <i class="fas fa-bell"></i>
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" style="min-width: 350px; max-width: 400px;">
                                <h6 class="dropdown-header">Notifikasi</h6>
                                @php
                                    $pmNotifications = auth()
                                        ->user()
                                        ->unreadNotifications->whereIn(
                                            'type',
                                            [
                                                \App\Notifications\NewSpbApprovalNotification::class,
                                                \App\Notifications\DuplicateDeliveryPlanDateNotification::class,
                                            ]
                                        );
                                @endphp
                                @forelse($pmNotifications as $notification)
                                    @if ($notification->type === \App\Notifications\NewSpbApprovalNotification::class)
                                        <a href="{{ route('notifications.read', [
                                            'id' => $notification->id,
                                            'redirect' => route('pm.spb-approvals.show', $notification->data['spb_id']),
                                        ]) }}"
                                            class="dropdown-item d-flex align-items-start" style="white-space: normal;">
                                            <div>
                                                <div><strong>SPB #{{ $notification->data['spb_number'] }}</strong>
                                                    membutuhkan persetujuan</div>
                                                <div class="small text-muted">
                                                    Proyek: {{ $notification->data['project_name'] }}<br>
                                                    Oleh: {{ $notification->data['created_by'] }}<br>
                                                    <span>{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </a>
                                    @elseif ($notification->type === \App\Notifications\DuplicateDeliveryPlanDateNotification::class)
                                        <a href="{{ route('notifications.read', [
                                            'id' => $notification->id,
                                            'redirect' => route('delivery.plans.show', $notification->data['new_plan_id']),
                                        ]) }}"
                                            class="dropdown-item d-flex align-items-start" style="white-space: normal;">
                                            <div>
                                                <div><strong>{{ $notification->data['message'] }}</strong></div>
                                                <div class="small text-muted">
                                                    <span>{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </a>
                                    @endif
                                @empty
                                    <div class="dropdown-item text-muted">Tidak ada notifikasi baru</div>
                                @endforelse
                            </div>
                        </div>

                        <div class="dropdown profile">
                            <button class="btn d-flex align-items-center" data-bs-toggle="dropdown">
                                <img src="https://ui-avatars.com/api/?name=pm" class="rounded-circle me-2"
                                    alt="Profile">
                                <span class="d-none d-md-block">{{ auth()->user()->name }}</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                {{-- {{ route('pm.profile') }} --}}
                                <a class="dropdown-item" href="">
                                    <i class="fas fa-user me-2"></i> Profil
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
            <main class="py-4 px-4">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="footer mt-auto py-3 bg-white">
                <div class="container text-center">
                    <span class="text-muted">© {{ date('Y') }} {{ config('app.name') }}. All rights
                        reserved.</span>
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
