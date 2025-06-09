<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - InventorySys</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/sass/app.scss', 'resources/sass/inventory.scss', 'resources/js/app.js'])
    @stack('styles')
</head>

<body>
    <div class="layout-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="sidebar-logo">
                <span class="sidebar-title">InventorySys</span>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('inventory.dashboard') }}"
                    class="nav-item {{ request()->routeIs('inventory.dashboard') ? 'active' : '' }}">
                    <i class="icon" data-lucide="layout-dashboard"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('inventory.items.index') }}"
                    class="nav-item {{ request()->routeIs('inventory.items.*') ? 'active' : '' }}">
                    <i class="icon" data-lucide="package"></i>
                    <span>Inventory Items</span>
                </a>

                <a href="{{ route('inventory.received-goods.index') }}"
                    class="nav-item {{ request()->routeIs('inventory.received-goods') ? 'active' : '' }}">
                    <i class="icon" data-lucide="download"></i>
                    <span>Incoming</span>
                </a>

                <a href="{{ route('inventory.outgoing.index') }}"
                    class="nav-item {{ request()->routeIs('inventory.outgoing.index') ? 'active' : '' }}">
                    <i class="icon" data-lucide="upload"></i>
                    <span>Outgoing</span>
                </a>

                <a href="{{ route('inventory.reports') }}"
                    class="nav-item {{ request()->routeIs('inventory.reports') ? 'active' : '' }}">
                    <i class="icon" data-lucide="file-bar-chart"></i>
                    <span>Reports</span>
                </a>

                <a href="{{ route('inventory.settings') }}"
                    class="nav-item {{ request()->routeIs('inventory.settings') ? 'active' : '' }}">
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
                    <input type="text" placeholder="Search items or codes...">
                </div>

                <div class="d-flex align-items-center gap-3">
                    <button class="notification-btn">
                        <i data-lucide="bell"></i>
                        <span class="badge">3</span>
                    </button>

                    <div class="user-menu dropdown">
                        <button class="user-btn dropdown-toggle" data-bs-toggle="dropdown">
                            <img src="https://ui-avatars.com/api/?name=iv" alt="Avatar" class="avatar">
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

            <!-- Main Content Area -->
            <main class="content">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="footer">
                <p class="mb-0">&copy; {{ date('Y') }} InventorySys. All rights reserved.</p>
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