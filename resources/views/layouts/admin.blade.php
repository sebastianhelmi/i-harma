<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - @yield('title', 'Admin Panel')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/sass/admin.scss'])
    @stack('styles')
</head>

<body class="bg-light">
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
                <span class="brand-text">Admin Panel</span>
            </div>

            <ul class="nav-menu">
                <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.index') }}">
                        <i class="fas fa-users"></i>
                        <span>Manajemen User</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                    <a href="{{ route('admin.item-categories.index') }}">
                        <i class="fas fa-folder"></i>
                        <span>Kategori Barang</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('admin.inventory*') ? 'active' : '' }}">
                    <a href="{{ route('admin.inventory.index') }}">
                        <i class="fas fa-box"></i>
                        <span>Inventori</span>
                    </a>
                </li>
                {{--
                <li class="{{ request()->routeIs('admin.spb*') ? 'active' : '' }}">
                    <a href="{{ route('admin.spb.index') }}">
                        <i class="fas fa-file-alt"></i>
                        <span>SPB</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.logs*') ? 'active' : '' }}">
                    <a href="{{ route('admin.logs.index') }}">
                        <i class="fas fa-history"></i>
                        <span>Logs</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                    <a href="{{ route('admin.settings.index') }}">
                        <i class="fas fa-cog"></i>
                        <span>Pengaturan</span>
                    </a>
                </li> --}}
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Topbar -->
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
                                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                                    3
                                </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end notification-menu">
                                <h6 class="dropdown-header">Notifikasi</h6>
                                <div class="notification-item">
                                    <i class="fas fa-user-plus text-primary"></i>
                                    <div class="notification-content">
                                        <p class="mb-0">User baru terdaftar</p>
                                        <small class="text-muted">5 menit yang lalu</small>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-center" href="#">Lihat Semua</a>
                            </div>
                        </div>

                        <!-- User Profile -->
                        <div class="dropdown profile">
                            <button class="btn d-flex align-items-center" data-bs-toggle="dropdown">
                                <img src="{{ auth()->user()->avatar ?? asset('images/default-avatar.png') }}"
                                    class="rounded-circle me-2" alt="Profile" width="32" height="32">
                                <div class="d-none d-md-block">
                                    <div class="fw-medium">{{ auth()->user()->name }}</div>
                                    <small class="text-muted">Administrator</small>
                                </div>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                {{-- {{ route('admin.profile') }} --}}
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

            <!-- Page Header -->
            <header class="page-header">
                <div class="container-fluid">
                    <div class="page-header-content">
                        <div class="page-title">
                            <h1>@yield('page-title')</h1>
                            @yield('page-subtitle')
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="content">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="footer">
                <div class="container-fluid text-center">
                    <span class="text-muted">© {{ date('Y') }} Internal Tools Admin Panel</span>
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
