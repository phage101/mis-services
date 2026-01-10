<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>

    <!-- CSS -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
</head>

<body>
    <!-- Background Elements -->
    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>

    @auth
        <header class="glass-header">
            <div class="container flex-center" style="justify-content: space-between;">
                <a href="{{ url('/dashboard') }}"
                    style="color: white; text-decoration: none; font-size: 1.5rem; font-weight: 700;">
                    MIS<span style="color: var(--color-secondary)">Service</span>
                </a>

                <nav>
                    <ul class="nav-links">
                        <li><a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a></li>
                    </ul>
                </nav>

                <div class="user-menu">
                    <span class="text-muted">{{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-link" style="padding: 0;">Logout</button>
                    </form>
                </div>
            </div>
        </header>
    @endauth

    <main class="container" style="margin-top: 2rem;">
        @yield('content')
    </main>

    @guest
        <footer style="margin-top: auto; padding: 2rem; text-align: center; color: var(--text-muted);">
            &copy; {{ date('Y') }} MIS Services. All rights reserved.
        </footer>
    @endguest
</body>

</html>