<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Construction Project Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="login-page">
    <div class="container-fluid">
        <div class="row min-vh-100">
            <!-- Left Section - Illustration -->
            <div class="col-lg-6 d-none d-lg-flex bg-primary illustration-section">
                <div class="illustration-content text-white p-5">
                    <i class="fas fa-building-shield illustration-icon"></i>
                    <h2 class="mt-4">Project Management System</h2>
                </div>
            </div>

            <!-- Right Section - Login Form -->
            <div class="col-lg-6 d-flex align-items-center">
                <div class="login-form-wrapper w-100 px-4 px-lg-5">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Company Logo" class="logo mb-4">
                        <h1 class="welcome-text">Selamat Datang Kembali</h1>
                        <p class="text-muted">Masuk ke akun Anda untuk mengelola proyek</p>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="login-form">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Ingat saya</label>
                            </div>
                            {{-- <a href="{{ route('password.request') }}" class="forgot-password">Lupa password?</a> --}}
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">Login</button>
                        <p class="text-center text-muted">
                            Belum punya akun? <a href="#" class="text-primary">Hubungi Admin</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
</body>

</html>
