<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <script>
        (function () {
            var theme = localStorage.getItem('booklog-theme') || 'dark';
            document.documentElement.setAttribute('data-bs-theme', theme);
        })();
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Book Log')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
        }

        .navbar {
            background-color: #1a1a1a !important;
            border-bottom: 1px solid #333;
        }

        .navbar-brand {
            color: #ffffff !important;
        }

        .nav-link {
            color: #b0b0b0 !important;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #ffffff !important;
        }

        .card {
            background-color: #1e1e1e !important;
            border: 1px solid #333;
        }

        .card-header {
            background-color: #252525 !important;
            border-bottom: 1px solid #333;
        }

        .form-control,
        .form-select {
            background-color: #2d2d2d;
            border: 1px solid #444;
            color: #e0e0e0;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #2d2d2d;
            border-color: #0d6efd;
            color: #e0e0e0;
        }

        .form-control::placeholder {
            color: #888;
        }

        .table {
            color: #e0e0e0;
        }

        .table {
            --bs-table-bg: #1e1e1e;
            --bs-table-border-color: #333;
            --bs-table-color: #e0e0e0;
        }

        .alert {
            background-color: #2d2d2d;
            border: 1px solid #444;
            color: #e0e0e0;
        }

        .alert-success {
            background-color: #1e3a1e;
            border-color: #2d4a2e;
            color: #90ee90;
        }

        .alert-danger {
            background-color: #3a1e1e;
            border-color: #4a2e2e;
            color: #ee9090;
        }

        .alert-warning {
            background-color: #3a341e;
            border-color: #4a442e;
            color: #e0d090;
        }

        .modal-content {
            background-color: #1e1e1e;
            border: 1px solid #333;
        }

        .modal-header {
            border-bottom: 1px solid #333;
        }

        .modal-footer {
            border-top: 1px solid #333;
        }

        .btn-close {
            filter: invert(1);
        }

        footer {
            background-color: #1a1a1a;
            border-top: 1px solid #333;
        }

        .text-muted {
            color: #888 !important;
        }

        .text-primary {
            color: #0d6efd !important;
        }

        .text-secondary {
            color: #6c757d !important;
        }

        .badge {
            color: #121212 !important;
        }

        .surface-box {
            background-color: #252525;
        }

        .surface-box-alt {
            background-color: #2d2d2d;
        }

        .input-addon {
            background-color: #2d2d2d;
            border-color: #444;
            color: #888;
        }

        .cover-ph {
            background-color: #252525;
        }


        [data-bs-theme="light"] {
            --bs-primary: #6D4C3D;
            --bs-primary-rgb: 109, 76, 61;
        }

        [data-bs-theme="light"] body {
            background-color: #F9F7F1;
            color: #2B2623;
        }

        [data-bs-theme="light"] .navbar {
            background-color: #F4F1EA !important;
            border-bottom: 1px solid #E2DDD1;
        }

        [data-bs-theme="light"] .navbar-brand {
            color: #2B2623 !important;
        }

        [data-bs-theme="light"] .nav-link {
            color: #6E675F !important;
        }

        [data-bs-theme="light"] .nav-link:hover,
        [data-bs-theme="light"] .nav-link.active {
            color: #2B2623 !important;
        }

        [data-bs-theme="light"] .card {
            background-color: #FDFBF7 !important;
            border-color: #E6E1D6;
        }

        [data-bs-theme="light"] .card-header {
            background-color: #F7F4EC !important;
            border-bottom-color: #E6E1D6;
        }

        [data-bs-theme="light"] .form-control,
        [data-bs-theme="light"] .form-select {
            background-color: #FFFFFF;
            border-color: #D8D2C5;
            color: #2B2623;
        }

        [data-bs-theme="light"] .form-control:focus,
        [data-bs-theme="light"] .form-select:focus {
            background-color: #FFFFFF;
            border-color: #6D4C3D;
            color: #2B2623;
        }

        [data-bs-theme="light"] .form-control::placeholder {
            color: #9A938A;
        }

        [data-bs-theme="light"] .table {
            --bs-table-bg: #FFFFFF;
            --bs-table-border-color: #E6E1D6;
            --bs-table-color: #2B2623;
            --bs-table-hover-bg: #F2EFE9;
        }

        [data-bs-theme="light"] .alert {
            background-color: #FBF8F2;
            border-color: #E2DDD1;
            color: #2B2623;
        }

        [data-bs-theme="light"] .alert-success {
            background-color: #EDF3EC;
            border-color: #CFE0D2;
            color: #3E6B48;
        }

        [data-bs-theme="light"] .alert-danger {
            background-color: #F7E9E7;
            border-color: #E8CFC9;
            color: #8C3B2E;
        }

        [data-bs-theme="light"] .alert-warning {
            background-color: #F5EEDF;
            border-color: #E4D9B8;
            color: #7A5C1E;
        }

        [data-bs-theme="light"] .modal-content {
            background-color: #FDFBF7;
            border-color: #E6E1D6;
        }

        [data-bs-theme="light"] .btn-close {
            filter: none;
        }

        [data-bs-theme="light"] footer {
            background-color: #F4F1EA;
            border-top-color: #E2DDD1;
        }

        [data-bs-theme="light"] .text-muted {
            color: #6E675F !important;
        }

        [data-bs-theme="light"] .text-primary {
            color: #6D4C3D !important;
        }

        [data-bs-theme="light"] .badge {
            color: #2B2623 !important;
        }

        [data-bs-theme="light"] .surface-box {
            background-color: #F7F4EC;
        }

        [data-bs-theme="light"] .surface-box-alt {
            background-color: #F2EFE9;
        }

        [data-bs-theme="light"] .input-addon {
            background-color: #FFFFFF;
            border-color: #D8D2C5;
            color: #9A938A;
        }

        [data-bs-theme="light"] .cover-ph {
            background-color: #F2EFE9;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg shadow">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('library.index') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                    class="bi bi-book-fill me-2" viewBox="0 0 16 16">
                    <path
                        d="M8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z" />
                </svg>
                Ryan's Library
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                @auth
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard.index') }}">
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('books.index') }}">
                                My Books
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('rentals.index') }}">
                                My Rentals
                            </a>
                        </li>
                        @if (auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    Admin
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('profile.edit') }}">
                                Profile
                            </a>
                        </li>
                        <li class="nav-item d-flex align-items-center">
                            <div class="form-check form-switch m-0 d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" id="themeSwitch" role="switch">
                                <label class="form-check-label ms-1" for="themeSwitch" id="themeLabel">Dark</label>
                            </div>
                        </li>
                    </ul>
                    <a href="{{ route('books.create') }}" class="btn btn-primary fw-bold me-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-plus-lg me-1" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z" />
                        </svg>
                        Add Book
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary">Log out</button>
                    </form>
                @else
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Log in</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                        <li class="nav-item d-flex align-items-center">
                            <div class="form-check form-switch m-0 d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" id="themeSwitch" role="switch">
                                <label class="form-check-label ms-1" for="themeSwitch" id="themeLabel">Dark</label>
                            </div>
                        </li>
                    </ul>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container py-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>

    <footer class="text-center py-3 mt-5">
        <div class="container">
            <p class="mb-0 text-muted">Book Log & Purchase Tracker</p>
            <p class="mb-0 text-muted">&copy; 2026 Ryan Bagas</p>

        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            const themeSwitch = document.getElementById('themeSwitch');
            const themeLabel = document.getElementById('themeLabel');
            if (!themeSwitch || !themeLabel) return;

            function currentTheme() {
                return document.documentElement.getAttribute('data-bs-theme') === 'light' ? 'light' : 'dark';
            }

            function sync() {
                const theme = currentTheme();
                themeSwitch.checked = theme === 'light';
                themeLabel.textContent = theme === 'light' ? 'Light' : 'Dark';
            }

            sync();
            themeSwitch.addEventListener('change', function () {
                const next = themeSwitch.checked ? 'light' : 'dark';
                document.documentElement.setAttribute('data-bs-theme', next);
                localStorage.setItem('booklog-theme', next);
                themeLabel.textContent = next === 'light' ? 'Light' : 'Dark';
            });
        })();
    </script>
    <script>
        document.querySelectorAll('input[type="password"]').forEach(function(input) {
            const wrapper = document.createElement('div');
            wrapper.className = 'position-relative';
            input.parentNode.insertBefore(wrapper, input);
            wrapper.appendChild(input);
            input.classList.add('pe-5');

            const toggle = document.createElement('button');
            toggle.type = 'button';
            toggle.className = 'btn btn-link position-absolute top-50 translate-middle-y end-0 text-muted';
            toggle.style.padding = '0 0.6rem';
            toggle.setAttribute('aria-label', 'Show password');
            toggle.innerHTML =
                '<i class="bi bi-eye"></i>';

            toggle.addEventListener('click', function() {
                const show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                toggle.innerHTML = show ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
                toggle.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
            });

            wrapper.appendChild(toggle);
        });
    </script>
    @stack('scripts')
</body>

</html>
