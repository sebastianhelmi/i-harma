<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Purchasing</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/sass/app.scss', 'resources/sass/purchasing.scss', 'resources/js/app.js'])
    @stack('styles')
</head>

<body>
    <div class="layout-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="sidebar-logo">
                <span class="sidebar-title">PurchasingSys</span>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('purchasing.dashboard') }}"
                    class="nav-item {{ request()->routeIs('purchasing.dashboard') ? 'active' : '' }}">
                    <i class="icon" data-lucide="layout-dashboard"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('purchasing.spbs.index') }}"
                    class="nav-item {{ request()->routeIs('purchasing.spbs.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>SPB</span>
                </a>
                <a href="{{ route('purchasing.pos.index') }}"
                    class="nav-item {{ request()->routeIs('purchasing.pos.*') ? 'active' : '' }}">
                    <i class="icon" data-lucide="shopping-cart"></i>
                    <span>Purchase Orders</span>
                </a>

                <a href="{{ route('purchasing.suppliers.index') }}"
                    class="nav-item {{ request()->routeIs('purchasing.suppliers.*') ? 'active' : '' }}">
                    <i class="icon" data-lucide="users"></i>
                    <span>Suppliers</span>
                </a>

                <a href="{{ route('purchasing.reports.index') }}"
                    class="nav-item {{ request()->routeIs('purchasing.reports.index') ? 'active' : '' }}">
                    <i class="icon" data-lucide="file-text"></i>
                    <span>Reports</span>
                </a>

                <a href="{{ route('purchasing.settings') }}"
                    class="nav-item {{ request()->routeIs('purchasing.settings') ? 'active' : '' }}">
                    <i class="icon" data-lucide="settings"></i>
                    <span>Settings</span>
                </a>
            </nav>
        </aside>

        <div class="main-content">
            <!-- Header -->
            <header class="header">
                <button id="sidebarCollapse" class="btn d-md-none">
                    <i data-lucide="menu"></i>
                </button>

                <div class="search-box">
                    <i class="icon" data-lucide="search"></i>
                    <input type="text" placeholder="Search orders, suppliers...">
                </div>

                <div class="d-flex align-items-center gap-3">
                    <button class="notification-btn">
                        <i data-lucide="bell"></i>
                        <span class="badge">2</span>
                    </button>

                    <div class="user-menu dropdown">
                        <button class="user-btn dropdown-toggle" data-bs-toggle="dropdown">
                            <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=PO' }}"
                                alt="Avatar" class="avatar">
                            <span class="name">{{ auth()->user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- Breadcrumb -->
            @hasSection('breadcrumb')
                <nav aria-label="breadcrumb" class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        @yield('breadcrumb')
                    </ol>
                </nav>
            @endif

            <!-- Main Content Area -->
            <main class="content">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="footer">
                <p class="mb-0">&copy; {{ date('Y') }} PurchasingSys. All rights reserved.</p>
            </footer>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
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
