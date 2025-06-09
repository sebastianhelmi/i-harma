<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - @yield('title', 'Delivery')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/sass/delivery.scss'])
    @stack('styles')
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
                <span class="company-name">Project Management</span>
            </div>

            <ul class="list-unstyled components">
                <li class="{{ request()->routeIs('delivery.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('delivery.dashboard') }}">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('delivery.plans*') ? 'active' : '' }}">
                    <a href="{{ route('delivery.plans.index') }}">
                        <i class="fas fa-truck"></i>
                        <span>Rencana Pengiriman</span>
                    </a>
                </li>
                {{--
                <li class="{{ request()->routeIs('delivery.shipments*') ? 'active' : '' }}">
                    <a href="{{ route('delivery.shipments.index') }}">
                        <i class="fas fa-shipping-fast"></i>
                        <span>Pengiriman</span>
                    </a>
                </li> --}}

                {{-- <li class="{{ request()->routeIs('delivery.history*') ? 'active' : '' }}">
                    <a href="{{ route('delivery.history.index') }}">
                        <i class="fas fa-history"></i>
                        <span>Riwayat Pengiriman</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('delivery.reports*') ? 'active' : '' }}">
                    <a href="{{ route('delivery.reports.index') }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Laporan</span>
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
                        <!-- Notifications -->
                        <div class="dropdown notifications me-3">
                            <button class="btn position-relative" data-bs-toggle="dropdown">
                                <i class="fas fa-bell"></i>
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    3
                                </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end notification-menu">
                                <h6 class="dropdown-header">Notifikasi</h6>
                                <div class="notification-item">
                                    <i class="fas fa-truck text-primary"></i>
                                    <div class="notification-content">
                                        <p class="mb-0">Ada pengiriman baru yang perlu diproses</p>
                                        <small class="text-muted">5 menit yang lalu</small>
                                    </div>
                                </div>
                                <div class="notification-item">
                                    <i class="fas fa-exclamation-circle text-warning"></i>
                                    <div class="notification-content">
                                        <p class="mb-0">Pengiriman #123 mendekati deadline</p>
                                        <small class="text-muted">1 jam yang lalu</small>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-center" href="#">Lihat semua notifikasi</a>
                            </div>
                        </div>

                        <!-- User Profile -->
                        <div class="dropdown profile">
                            <button class="btn d-flex align-items-center" data-bs-toggle="dropdown">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}"
                                    class="rounded-circle me-2" alt="Profile">
                                <div class="d-none d-md-block text-start">
                                    <div class="fw-medium">{{ auth()->user()->name }}</div>
                                    <small class="text-muted">Delivery Staff</small>
                                </div>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('delivery.profile') }}">
                                    <i class="fas fa-user me-2"></i> Profil Saya
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar toggle
            const sidebar = document.getElementById('sidebar');
            const wrapper = document.querySelector('.wrapper');

            document.getElementById('sidebarCollapse').addEventListener('click', function() {
                sidebar.classList.toggle('active');
                wrapper.classList.toggle('sidebar-collapsed');
            });

            // Handle responsive sidebar on window resize
            function handleResize() {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('active');
                    wrapper.classList.add('sidebar-collapsed');
                } else {
                    sidebar.classList.add('active');
                    wrapper.classList.remove('sidebar-collapsed');
                }
            }

            window.addEventListener('resize', handleResize);
            handleResize(); // Initial check
        });
        // Sidebar toggle
        document.getElementById('sidebarCollapse').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
    </script>
</body>

</html>