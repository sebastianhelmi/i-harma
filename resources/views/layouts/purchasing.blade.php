<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - @yield('title', 'Procurement Portal')</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/sass/purchasing.scss'])
    @stack('styles')
</head>

<body class="dark-theme">
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('images/logo-dark.png') }}" alt="Logo" class="logo">
                <span class="company-name">Procurement Portal</span>
            </div>

            <ul class="nav-menu">
                <li class="{{ request()->routeIs('purchasing.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('purchasing.dashboard') }}">
                        <i class="fas fa-box"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('purchasing.spb*') ? 'active' : '' }}">
                    <a href="{{ route('purchasing.spb') }}">
                        <i class="fas fa-file-alt"></i>
                        <span>SPB Masuk</span>
                        <span class="badge bg-accent">3</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('purchasing.po*') ? 'active' : '' }}">
                    <a href="{{ route('purchasing.po.index') }}">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Purchase Order</span>
                    </a>
                </li>
                {{--
                <li class="{{ request()->routeIs('purchasing.vendors*') ? 'active' : '' }}">
                    <a href="{{ route('purchasing.vendors.index') }}">
                        <i class="fas fa-building"></i>
                        <span>Vendor & Supplier</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('purchasing.reports*') ? 'active' : '' }}">
                    <a href="{{ route('purchasing.reports.index') }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Laporan</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('purchasing.settings*') ? 'active' : '' }}">
                    <a href="{{ route('purchasing.settings.index') }}">
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
                    <div class="topbar-left">
                        <button id="sidebarCollapse" class="btn">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>

                    <div class="topbar-right">
                        <!-- Notifications -->
                        <div class="dropdown notifications me-3">
                            <button class="btn position-relative" data-bs-toggle="dropdown">
                                <i class="fas fa-bell"></i>
                                <span
                                    class="badge bg-accent position-absolute top-0 start-100 translate-middle">5</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end notification-menu">
                                <h6 class="dropdown-header">Notifikasi</h6>
                                <div class="notification-item">
                                    <i class="fas fa-file-alt text-accent"></i>
                                    <div class="notification-content">
                                        <p class="mb-0">SPB baru dari Proyek A</p>
                                        <small>5 menit yang lalu</small>
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
                                <div class="d-none d-md-block text-start">
                                    <div class="fw-medium text-light">{{ auth()->user()->name }}</div>
                                    <small class="text-muted">Purchasing</small>
                                </div>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                {{-- {{ route('purchasing.profile') }} --}}
                                <a class="dropdown-item" href="">
                                    <i class="fas fa-user me-2"></i> Profil
                                </a>
                                {{-- {{ route('purchasing.settings.password') }} --}}
                                <a class="dropdown-item" href="">
                                    <i class="fas fa-key me-2"></i> Ganti Password
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
        </div>
    </div>

    <!-- Mobile Navigation -->
    <nav class="mobile-nav d-md-none">
        <a href="{{ route('purchasing.dashboard') }}"
            class="{{ request()->routeIs('purchasing.dashboard') ? 'active' : '' }}">
            <i class="fas fa-box"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('purchasing.spb') }}" class="{{ request()->routeIs('purchasing.spb*') ? 'active' : '' }}">
            <i class="fas fa-file-alt"></i>
            <span>SPB</span>
        </a>
        {{--
        <a href="{{ route('purchasing.po.index') }}"
            class="{{ request()->routeIs('purchasing.po*') ? 'active' : '' }}">
            <i class="fas fa-shopping-cart"></i>
            <span>PO</span>
        </a>
        <a href="{{ route('purchasing.profile') }}"
            class="{{ request()->routeIs('purchasing.profile') ? 'active' : '' }}">
            <i class="fas fa-user"></i>
            <span>Profil</span>
        </a>
        --}}
    </nav>
    @stack('scripts')
</body>

</html>
