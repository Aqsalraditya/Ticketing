<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Secure Ticketing') - SMK Wikrama Bogor</title>

    {{-- Prevent sidebar transition flash on page load --}}
    <script>
        (function() {
            if (window.innerWidth >= 992 && localStorage.getItem('sidebarCollapsed') === 'true') {
                document.documentElement.classList.add('sidebar-collapsed-on-load');
            }
        })();
    </script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 280px;
            --topbar-height: 60px;
            --sidebar-bg: #1674FF; /* Diubah menjadi biru cerah sesuai gambar terbaru */
            --sidebar-hover: rgba(255, 255, 255, 0.1);
            --sidebar-active: rgba(255, 255, 255, 0.15);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f7f6;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ============================================ */
        /* SIDEBAR STYLES */
        /* ============================================ */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            color: #fff;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, width 0.3s ease;
            overflow: hidden;
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        /* Transition fix on load */
        html.sidebar-collapsed-on-load .sidebar {
            transform: translateX(-100%);
            transition: none;
        }

        html.sidebar-collapsed-on-load .main-wrapper {
            margin-left: 0;
            transition: none;
        }

        html.sidebar-ready .sidebar,
        html.sidebar-ready .main-wrapper {
            transition: transform 0.3s ease, margin-left 0.3s ease;
        }

        .sidebar-header {
            padding: 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .sidebar-header .logo {
            font-size: 1rem;
            font-weight: 800;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: 0.5px;
        }

        .sidebar-body {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 0.5rem 0 2rem 0;
        }

        .sidebar-footer {
            flex-shrink: 0;
            background-color: var(--sidebar-bg);
            position: relative;
            z-index: 99;
        }

        /* Custom Scrollbar for Sidebar */
        .sidebar-body::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar-body::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-body::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .sidebar-body::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        /* Nav Section */
        .nav-section {
            padding: 1.25rem 1.25rem 0.5rem;
            font-size: 0.65rem;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.4);
            font-weight: 700;
            letter-spacing: 1.2px;
        }

        .nav-item {
            margin: 2px 0.75rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.6rem 0.85rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 10px;
            font-size: 0.85rem;
            transition: all 0.2s;
        }

        .nav-link:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .nav-link.active {
            background: var(--sidebar-active);
            color: #fff;
            font-weight: 600;
        }

        /* WARNA ANTI-BENTROK UNTUK LAB (PASTEL) */
        .text-lab-vuln { color: #fecaca !important; }
        .text-lab-secure { color: #bbf7d0 !important; }
        .text-lab-info { color: #bae6fd !important; }
        .text-lab-warn { color: #fef08a !important; }

        .nav-collapse {
            list-style: none;
            padding: 0;
            margin-left: 1rem;
            border-left: 1px solid rgba(255, 255, 255, 0.1);
        }

        .nav-collapse .nav-link {
            padding-left: 1.25rem;
            font-size: 0.8rem;
        }

        /* Dropdown Arrow */
        .dropdown-toggle::after {
            transition: transform 0.2s;
            content: '\F282';
            font-family: 'bootstrap-icons';
            position: absolute;
            right: 1.5rem;
            font-size: 0.7rem;
            opacity: 0.7;
            border: none;
        }

        .dropdown-toggle[aria-expanded="true"]::after {
            transform: rotate(180deg);
        }

        /* ============================================ */
        /* MAIN AREA & UTILITIES */
        /* ============================================ */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease;
        }

        .main-wrapper.expanded {
            margin-left: 0;
        }

        .topbar {
            height: var(--topbar-height);
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .breadcrumb-wrapper {
            display: flex;
            align-items: center;
        }

        .breadcrumb {
            font-size: 0.875rem;
            margin-bottom: 0;
        }

        pre code {
            font-size: 0.85rem;
        }

        .badge-vulnerable { background-color: #dc3545; }
        .badge-secure { background-color: #198754; }

        .table code {
            background-color: rgba(0, 0, 0, 0.05);
            padding: 0.125rem 0.25rem;
            border-radius: 0.25rem;
        }

        .alert {
            border-left-width: 4px;
        }

        .alert-danger {
            animation: pulse-border 2s infinite;
        }

        @keyframes pulse-border {
            0%, 100% { border-left-color: #dc3545; }
            50% { border-left-color: #ff6b6b; }
        }

        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.show {
            display: block;
            opacity: 1;
        }

        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-wrapper { margin-left: 0 !important; }
        }
    </style>
    @stack('styles')
</head>

<body>
    {{-- SIDEBAR --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ url('/') }}" class="logo"><i class="bi bi-shield-lock-fill text-info"></i> SECURE TICKETING</a>
            <button class="btn text-white d-lg-none p-0" id="sidebarClose"><i class="bi bi-x-lg"></i></button>
        </div>

        <div class="sidebar-body">
            <ul class="sidebar-nav">
                {{-- UTAMA --}}
                <li class="nav-section">Utama</li>
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tickets.index') }}" class="nav-link {{ request()->routeIs('tickets.*') ? 'active' : '' }}">
                        <i class="bi bi-ticket-perforated me-2"></i> Tiket Support
                    </a>
                </li>

                {{-- ADMIN AREA --}}
                @canany(['access-admin', 'view-reports'])
                    <li class="nav-section">Management</li>
                    <li class="nav-item">
                        <a href="#adminMenu" class="nav-link dropdown-toggle {{ request()->is('admin*') || request()->is('reports*') ? '' : 'collapsed' }}"
                            data-bs-toggle="collapse" aria-expanded="{{ request()->is('admin*') || request()->is('reports*') ? 'true' : 'false' }}">
                            <i class="bi bi-shield-lock me-2 text-lab-vuln"></i> Admin & Staff
                        </a>
                        <ul class="collapse nav-collapse {{ request()->is('admin*') || request()->is('reports*') ? 'show' : '' }}" id="adminMenu">
                            @can('access-admin')
                                <li><a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard Admin</a></li>
                                <li><a href="{{ route('admin.users') }}" class="nav-link">Kelola User</a></li>
                                <li><a href="{{ route('admin.tickets') }}" class="nav-link">Semua Tiket</a></li>
                            @endcan
                            @can('view-reports')
                                <li><a href="{{ route('admin.reports') }}" class="nav-link text-lab-info">Statistik & Laporan</a></li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                {{-- BLADE DEMO --}}
                <li class="nav-section">Blade Demo</li>
                <li class="nav-item">
                    <a href="#bladeMenu" class="nav-link dropdown-toggle {{ request()->is('demo-blade*') || request()->routeIs('error-handling-demo') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" aria-expanded="{{ request()->is('demo-blade*') || request()->routeIs('error-handling-demo') ? 'true' : 'false' }}">
                        <i class="bi bi-code-slash me-2 text-lab-secure"></i> Blade Templating
                    </a>
                    <ul class="collapse nav-collapse {{ request()->is('demo-blade*') || request()->routeIs('error-handling-demo') ? 'show' : '' }}" id="bladeMenu">
                        <li><a href="{{ route('demo-blade.index') }}" class="nav-link">Overview</a></li>
                        <li><a href="{{ route('demo-blade.directives') }}" class="nav-link">Directives</a></li>
                        <li><a href="{{ route('demo-blade.components') }}" class="nav-link">Components</a></li>
                        <li><a href="{{ route('demo-blade.includes') }}" class="nav-link">Includes</a></li>
                        <li><a href="{{ route('demo-blade.stacks') }}" class="nav-link">Stacks & Push</a></li>
                        <li><a href="{{ route('error-handling-demo') }}" class="nav-link text-lab-warn">Error Handling</a></li>
                    </ul>
                </li>

                {{-- SECURITY LABS --}}
                <li class="nav-section">Lab Penetrasi</li>

                {{-- BAC/IDOR LAB --}}
                <li class="nav-item">
                    <a href="#bacLabMenu" class="nav-link dropdown-toggle {{ request()->is('bac-lab*') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" aria-expanded="{{ request()->is('bac-lab*') ? 'true' : 'false' }}">
                        <i class="bi bi-door-open me-2 text-lab-warn"></i> BAC/IDOR Lab
                    </a>
                    <ul class="collapse nav-collapse {{ request()->is('bac-lab*') ? 'show' : '' }}" id="bacLabMenu">
                        <li><a href="{{ route('bac-lab.home') }}" class="nav-link">Overview</a></li>
                        <li><a href="{{ route('bac-lab.comparison') }}" class="nav-link">Comparison</a></li>
                        @auth
                            <li><a href="{{ route('bac-lab.vulnerable.tickets.index') }}" class="nav-link text-lab-vuln">Vulnerable (IDOR) Tickets</a></li>
                            <li><a href="{{ route('bac-lab.secure.tickets.index') }}" class="nav-link text-lab-secure">Secure (Policy) Tickets</a></li>
                        @else
                            <li><a href="{{ route('bac-lab.vulnerable.login') }}" class="nav-link text-lab-vuln">Vulnerable (IDOR) Login</a></li>
                            <li><a href="{{ route('bac-lab.secure.login') }}" class="nav-link text-lab-secure">Secure (Policy) Login</a></li>
                        @endauth
                    </ul>
                </li>

                {{-- SQLi --}}
                <li class="nav-item">
                    <a href="#sqliMenu" class="nav-link dropdown-toggle {{ request()->is('sqli-lab*') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" aria-expanded="{{ request()->is('sqli-lab*') ? 'true' : 'false' }}">
                        <i class="bi bi-database-fill-exclamation me-2"></i> SQLi Lab
                    </a>
                    <ul class="collapse nav-collapse {{ request()->is('sqli-lab*') ? 'show' : '' }}" id="sqliMenu">
                        <li><a href="{{ route('sqli-lab.index') }}" class="nav-link">Overview</a></li>
                        <li><a href="{{ route('sqli-lab.how-it-works') }}" class="nav-link text-lab-info">How it Works</a></li>
                        <li><a href="{{ route('sqli-lab.vulnerable-search') }}" class="nav-link text-lab-vuln">Vuln Search</a></li>
                        <li><a href="{{ route('sqli-lab.vulnerable-login') }}" class="nav-link text-lab-vuln">Login Bypass</a></li>
                        <li><a href="{{ route('sqli-lab.blind-sqli') }}" class="nav-link text-lab-vuln">Blind SQLi</a></li>
                        <li><a href="{{ route('sqli-lab.secure-search') }}" class="nav-link text-lab-secure">Secure Search</a></li>
                        <li><a href="{{ route('sqli-lab.cheatsheet') }}" class="nav-link">Cheatsheet</a></li>
                    </ul>
                </li>

                {{-- XSS --}}
                <li class="nav-item">
                    <a href="#xssMenu" class="nav-link dropdown-toggle {{ request()->is('xss-lab*') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" aria-expanded="{{ request()->is('xss-lab*') ? 'true' : 'false' }}">
                        <i class="bi bi-braces-asterisk me-2"></i> XSS Lab
                    </a>
                    <ul class="collapse nav-collapse {{ request()->is('xss-lab*') ? 'show' : '' }}" id="xssMenu">
                        <li><a href="{{ route('xss-lab.index') }}" class="nav-link">Overview</a></li>
                        <li><a href="{{ route('xss-lab.reflected.vulnerable') }}" class="nav-link text-lab-vuln">Reflected (Vuln)</a></li>
                        <li><a href="{{ route('xss-lab.stored.vulnerable') }}" class="nav-link text-lab-vuln">Stored (Vuln)</a></li>
                        <li><a href="{{ route('xss-lab.dom.vulnerable') }}" class="nav-link text-lab-vuln">DOM (Vuln)</a></li>
                        <li><a href="{{ route('xss-lab.reflected.secure') }}" class="nav-link text-lab-secure">Secure Mode</a></li>
                    </ul>
                </li>

                {{-- CSRF & Validation --}}
                <li class="nav-item">
                    <a href="#csrfMenu" class="nav-link dropdown-toggle {{ request()->is('csrf-lab*') || request()->is('validation-lab*') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" aria-expanded="{{ request()->is('csrf-lab*') || request()->is('validation-lab*') ? 'true' : 'false' }}">
                        <i class="bi bi-shield-shaded me-2"></i> CSRF & Validation
                    </a>
                    <ul class="collapse nav-collapse {{ request()->is('csrf-lab*') || request()->is('validation-lab*') ? 'show' : '' }}" id="csrfMenu">
                        <li><a href="{{ route('csrf-lab.index') }}" class="nav-link">CSRF Overview</a></li>
                        <li><a href="{{ route('csrf-lab.how-it-works') }}" class="nav-link text-lab-info">CSRF logic</a></li>
                        <li><a href="{{ route('csrf-lab.attack-demo') }}" class="nav-link text-lab-vuln">CSRF Attack</a></li>
                        <li><a href="{{ route('csrf-lab.protection-demo') }}" class="nav-link text-lab-secure">CSRF Protect</a></li>
                        <li><a href="{{ route('csrf-lab.ajax-demo') }}" class="nav-link text-lab-warn">AJAX CSRF</a></li>
                        <li><a href="{{ route('validation-lab.index') }}" class="nav-link">Validation Lab</a></li>
                        <li><a href="{{ route('validation-lab.vulnerable') }}" class="nav-link text-lab-vuln">Vuln Form</a></li>
                        <li><a href="{{ route('validation-lab.secure') }}" class="nav-link text-lab-secure">Secure Form</a></li>
                    </ul>
                </li>

                {{-- AUTH & OTORISASI LABS --}}
                <li class="nav-section">Auth & Otorisasi</li>
                <li class="nav-item">
                    <a href="#authLabFull" class="nav-link dropdown-toggle {{ request()->is('auth-lab*') || request()->is('authorization-lab*') || request()->is('vulnerable*') || request()->routeIs('login') || request()->routeIs('register') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" aria-expanded="{{ request()->is('auth-lab*') || request()->is('authorization-lab*') || request()->is('vulnerable*') || request()->routeIs('login') || request()->routeIs('register') ? 'true' : 'false' }}">
                        <i class="bi bi-person-lock me-2"></i> Lab Otorisasi
                    </a>
                    <ul class="collapse nav-collapse {{ request()->is('auth-lab*') || request()->is('authorization-lab*') || request()->is('vulnerable*') || request()->routeIs('login') || request()->routeIs('register') ? 'show' : '' }}" id="authLabFull">
                        <li><a href="{{ route('auth-lab.index') }}" class="nav-link">Auth Overview</a></li>
                        <li><a href="{{ route('auth-lab.comparison') }}" class="nav-link">Secure vs Vuln</a></li>
                        <li><a href="{{ route('authorization-lab.index') }}" class="nav-link">Otorisasi (RBAC)</a></li>
                        <li><a href="{{ route('authorization-lab.login') }}" class="nav-link text-lab-info">Test Login (RBAC)</a></li>
                        <li><a href="{{ route('authorization-lab.implementation') }}" class="nav-link text-lab-info">Implementation</a></li>

                        {{-- Added from Demo Code --}}
                        <li><a href="{{ route('login') }}" class="nav-link text-lab-secure">Login (Secure)</a></li>
                        <li><a href="{{ route('register') }}" class="nav-link text-lab-secure">Register (Secure)</a></li>
                        <li><a href="{{ route('vulnerable.login') }}" class="nav-link text-lab-vuln">Login (Vuln)</a></li>
                        <li><a href="{{ route('vulnerable.register') }}" class="nav-link text-lab-vuln">Register (Vuln)</a></li>

                        <li><a href="{{ route('vulnerable.show-users') }}" class="nav-link text-lab-warn">Show DB Users</a></li>
                        <li><a href="{{ route('vulnerable.brute-force-stats') }}" class="nav-link">Brute Force Stats</a></li>
                    </ul>
                </li>

                {{-- TOOLS --}}
                <li class="nav-section">Keamanan Tools</li>
                <li class="nav-item">
                    <a href="#toolsMenu" class="nav-link dropdown-toggle {{ request()->is('security-testing*') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" aria-expanded="{{ request()->is('security-testing*') ? 'true' : 'false' }}">
                        <i class="bi bi-tools me-2 text-lab-info"></i> Security Testing
                    </a>
                    <ul class="collapse nav-collapse {{ request()->is('security-testing*') ? 'show' : '' }}" id="toolsMenu">
                        <li><a href="{{ route('security-testing.index') }}" class="nav-link">Dashboard Test</a></li>
                        <li><a href="{{ route('security-testing.xss') }}" class="nav-link">XSS Scanner</a></li>
                        <li><a href="{{ route('security-testing.csrf') }}" class="nav-link">CSRF Tester</a></li>
                        <li><a href="{{ route('security-testing.headers') }}" class="nav-link">Headers Check</a></li>
                        <li><a href="{{ route('security-testing.audit') }}" class="nav-link text-lab-secure">Audit Checklist</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="sidebar-footer py-3 border-top border-white border-opacity-10 text-center">
            <small class="opacity-50">Bootcamp Secure Coding v1.0</small>
        </div>
    </aside>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="main-wrapper" id="mainWrapper">
        {{-- TOPBAR --}}
        <header class="topbar">
            <div class="topbar-left d-flex align-items-center">
                <button class="btn btn-light border-0 shadow-sm me-3" id="sidebarToggle"><i class="bi bi-list fs-5"></i></button>

                {{-- Breadcrumb Injection --}}
                <div class="breadcrumb-wrapper d-none d-md-block">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none"><i class="bi bi-house text-primary"></i></a></li>
                            @hasSection('breadcrumb')
                                @yield('breadcrumb')
                            @else
                                <li class="breadcrumb-item active" aria-current="page">@yield('title', 'Dashboard')</li>
                            @endif
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="topbar-right d-flex align-items-center gap-2">
                {{-- SQLi Lab Quick Actions --}}
                @if (request()->routeIs('sqli-lab.*'))
                    <a href="{{ route('sqli-lab.seed') }}" class="btn btn-sm btn-outline-primary rounded-pill"><i class="bi bi-plus-circle"></i> Seed</a>
                    <a href="{{ route('sqli-lab.reset') }}" class="btn btn-sm btn-outline-secondary rounded-pill"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
                @endif

                {{-- Dynamic Auth Dropdowns --}}
                @auth
                    {{-- Secure Auth User --}}
                    <div class="dropdown">
                        <button class="btn btn-white border shadow-sm dropdown-toggle rounded-pill px-3 py-1" data-bs-toggle="dropdown">
                            <i class="bi bi-shield-check text-success me-1"></i> {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                            <li class="px-3 py-2 small text-muted border-bottom">Role: <b class="text-primary">{{ ucfirst(Auth::user()->role) }}</b></li>
                            <li><a class="dropdown-item py-2" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger py-2"><i class="bi bi-box-arrow-right me-2"></i> Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endauth

                @if (session('vulnerable_user'))
                    {{-- Vulnerable Auth User --}}
                    <div class="dropdown">
                        <button class="btn btn-danger shadow-sm dropdown-toggle rounded-pill px-3 py-1" data-bs-toggle="dropdown">
                            <i class="bi bi-exclamation-triangle me-1"></i> {{ session('vulnerable_user')->name ?? 'Vuln User' }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                            <li><a class="dropdown-item py-2" href="{{ route('vulnerable.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Vuln Dashboard</a></li>
                            <li>
                                <form method="POST" action="{{ route('vulnerable.logout') }}">
                                    @csrf
                                    <button class="dropdown-item text-danger py-2"><i class="bi bi-box-arrow-right me-2"></i> Keluar (Vuln)</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endif

                @guest
                    @if (!session('vulnerable_user'))
                        {{-- Guest Menu --}}
                        <div class="dropdown">
                            <button class="btn btn-white border shadow-sm dropdown-toggle rounded-pill px-3 py-1" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle text-secondary me-1"></i> Guest
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                                <li><h6 class="dropdown-header"><i class="bi bi-shield-check"></i> Secure Auth</h6></li>
                                <li><a class="dropdown-item" href="{{ route('login') }}">Login</a></li>
                                <li><a class="dropdown-item" href="{{ route('register') }}">Register</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header"><i class="bi bi-exclamation-triangle"></i> Vulnerable Auth</h6></li>
                                <li><a class="dropdown-item text-danger" href="{{ route('vulnerable.login') }}">Login (Vuln)</a></li>
                                <li><a class="dropdown-item text-danger" href="{{ route('vulnerable.register') }}">Register (Vuln)</a></li>
                            </ul>
                        </div>
                    @endif
                @endguest
            </div>
        </header>

        {{-- MAIN CONTENT --}}
        <main class="main-content p-4">
            {{-- Global Flash Messages --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error') || session('danger'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') ?? session('danger') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('info'))
                <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-info-circle me-2"></i> {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger shadow-sm">
                    <i class="bi bi-exclamation-triangle"></i> <strong>Terjadi kesalahan:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Dynamic Page Content --}}
            @yield('content')
        </main>

        <footer class="py-3 px-4 bg-white border-top text-center">
            <p class="mb-0 text-muted small">&copy; {{ date('Y') }} SMK Wikrama Bogor - Secure Coding Ticketing</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Advanced Sidebar Toggle Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainWrapper = document.getElementById('mainWrapper');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarClose = document.getElementById('sidebarClose');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            // Handle transition flash prevention
            requestAnimationFrame(function() {
                requestAnimationFrame(function() {
                    document.documentElement.classList.remove('sidebar-collapsed-on-load');
                    document.documentElement.classList.add('sidebar-ready');
                });
            });

            const toggle = () => {
                if (window.innerWidth >= 992) {
                    sidebar.classList.toggle('collapsed');
                    mainWrapper.classList.toggle('expanded');
                    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
                } else {
                    sidebar.classList.toggle('show');
                    sidebarOverlay.classList.toggle('show');
                }
            };

            sidebarToggle.addEventListener('click', toggle);

            const closeSidebar = () => {
                if (window.innerWidth < 992) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                }
            };

            sidebarClose.addEventListener('click', closeSidebar);
            sidebarOverlay.addEventListener('click', closeSidebar);

            // Responsive auto-adjust
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                    if (localStorage.getItem('sidebarCollapsed') === 'true') {
                        sidebar.classList.add('collapsed');
                        mainWrapper.classList.add('expanded');
                    }
                } else {
                    sidebar.classList.remove('collapsed');
                    mainWrapper.classList.remove('expanded');
                }
            });
        });
    </script>

    {{-- Global CSRF Setup untuk AJAX --}}
    <script>
        window.csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        window.secureFetch = function(url, options = {}) {
            options.headers = {
                ...options.headers,
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json',
            };
            return fetch(url, options);
        };
    </script>

    @stack('scripts')
</body>

</html>