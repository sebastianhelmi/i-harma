<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
                <span class="brand-text">{{ config('app.name') }}</span>
            </div>

            <ul class="nav-menu">
                @section('sidebar-menu')
                <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                @show
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navigation -->
            <nav class="topbar">
                <div class="container-fluid">
                    <button id="sidebarCollapse" class="btn">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="topbar-right">
                        <!-- Notifications -->
                        <div class="dropdown notifications me-3">
                            <button class="btn position-relative" data-bs-toggle="dropdown">
                                <i class="fas fa-bell"></i>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                <span
                                    class="badge position-absolute top-0 start-100 translate-middle bg-danger rounded-pill">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                                @endif
                            </button>
                            <div class="dropdown-menu dropdown-menu-end notification-menu">
                                <h6 class="dropdown-header">Notifikasi</h6>
                                <div class="notifications-list">
                                    @forelse(auth()->user()->notifications->take(5) as $notification)
                                    <div class="notification-item">
                                        <i class="fas fa-file-alt text-primary"></i>
                                        <div class="notification-content">
                                            <p class="mb-0">{{ $notification->data['message'] ?? 'Notification' }}</p>
                                            <small class="text-muted">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="p-3 text-center text-muted">
                                        Tidak ada notifikasi
                                    </div>
                                    @endforelse
                                </div>
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('notifications.index') }}" class="dropdown-item text-center">
                                    Lihat Semua Notifikasi
                                </a>
                            </div>
                        </div>

                        <!-- User Profile -->
                        <div class="dropdown profile">
                            <button class="btn d-flex align-items-center" data-bs-toggle="dropdown">
                                <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name) }}"
                                    class="rounded-circle me-2" alt="Profile" width="32" height="32">
                                <div class="d-none d-md-block">
                                    <div class="fw-medium">{{ auth()->user()->name }}</div>
                                    <small class="text-muted">{{ auth()->user()->role->name }}</small>
                                </div>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user me-2"></i> Profile
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

            <!-- Header Section -->
            @hasSection('header')
            <header class="page-header">
                <div class="container-fluid">
                    @yield('header')
                </div>
            </header>
            @endif

            <!-- Main Content Area -->
            <main class="content">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="footer">
                <div class="container-fluid text-center">
                    <span class="text-muted">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights
                        reserved.</span>
                </div>
            </footer>
        </div>
    </div>

    @stack('scripts')

    <!-- Flash Messages -->
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