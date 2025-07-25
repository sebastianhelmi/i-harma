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

                <a href="{{ route('inventory.reports.index') }}"
                    class="nav-item {{ request()->routeIs('inventory.reports.index') ? 'active' : '' }}">
                    <i class="icon" data-lucide="file-bar-chart"></i>
                    <span>Reports</span>
                </a>

                {{-- <a href="{{ route('inventory.settings') }}"
                    class="nav-item {{ request()->routeIs('inventory.settings') ? 'active' : '' }}">
                    <i class="icon" data-lucide="settings"></i>
                    <span>Settings</span>
                </a> --}}
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
                    <button class="notification-btn" data-bs-toggle="dropdown">
                        <i data-lucide="bell"></i>
                        <span class="badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" style="min-width: 350px; max-width: 400px;">
                        <h6 class="dropdown-header">Notifikasi</h6>
                        @php
                            $poNotifications = auth()
                                ->user()
                                ->unreadNotifications->where(
                                    'type',
                                    \App\Notifications\NewPoCreatedNotification::class,
                                );
                        @endphp
                        @forelse($poNotifications as $notification)
                            <a href="{{ route('notifications.read', [
                                'id' => $notification->id,
                                'redirect' => route('inventory.received-goods.create', $notification->data['po_id']),
                            ]) }}"
                                class="dropdown-item d-flex align-items-start" style="white-space: normal;">
                                <div>
                                    <div><strong>PO #{{ $notification->data['po_number'] }}</strong> siap diproses
                                    </div>
                                    <div class="small text-muted">
                                        Proyek: {{ $notification->data['project_name'] }}<br>
                                        Dibuat oleh: {{ $notification->data['created_by'] ?? '-' }}<br>
                                        <span>{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="dropdown-item text-muted">Tidak ada notifikasi baru</div>
                        @endforelse
                    </div>

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
