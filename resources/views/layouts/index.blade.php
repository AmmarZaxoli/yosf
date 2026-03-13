<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم')</title>

    <!-- Bootstrap 5 RTL CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts - Tajawal -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    @livewireStyles

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #4361ee;
            --primary-dark: #3a0ca3;
            --secondary-color: #3f37c9;
            --accent-color: #4cc9f0;
            --success-color: #06d6a0;
            --warning-color: #ffb703;
            --danger-color: #ef476f;
            --dark-color: #2b2d42;
            --light-color: #f31212;
            --gray-color: #6c757d;
            --border-color: #dee2e6;

            /* Light Mode */
            --bg-body: #386afe20;
            --bg-navbar: rgba(255, 255, 255, 0.97);
            --bg-panel: rgba(255, 255, 255, 0.97);
            --bg-card: none;
            --text-primary: #2b2d42;
            --text-secondary: #6c757d;
            --border-clr: rgba(0, 0, 0, 0.07);
            --hover-bg: rgba(43, 81, 248, 0.222);
            --input-bg: rgba(0, 0, 0, 0.04);
            --nav-height: 80px;
            --border-radius: 16px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.08);
        }

        /* ===== DARK MODE ===== */
        body.dark-mode {
            --bg-body: #0f111758;
            --bg-navbar: rgba(22, 24, 35, 0.97);
            --bg-panel: rgba(22, 24, 35, 0.97);
            --bg-card: rgba(22, 24, 35, 0.97);
            --text-primary: #ffffff;
            --text-secondary: #dfdddd;
            --border-clr: rgba(255, 0, 0, 0.07);
            --hover-bg: rgba(67, 97, 238, 0.15);
            --input-bg: rgba(255, 255, 255, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.4);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background: var(--bg-body);
            min-height: 100vh;
            color: var(--text-primary);
            transition: background 0.4s ease, color 0.4s ease;
        }

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 20px;
        }

        /* ===== MAIN NAV CONTAINER ===== */
        .main-nav-container {
            position: fixed;
            top: 3px;
            left: 20px;
            right: 20px;
            z-index: 1000;
            animation: slideDown 0.5s ease-out;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .main-navbar {
            background: var(--bg-navbar);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 12px 25px;
            box-shadow: var(--shadow-xl);
            border: 1px solid var(--border-clr);
            transition: background 0.4s ease, border-color 0.4s ease;
        }

        .main-nav-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Logo */
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            padding: 8px 20px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            font-weight: 700;
            font-size: 20px;
            transition: var(--transition);
        }

        .logo:hover {
            transform: scale(1.05);
            box-shadow: var(--shadow-lg);
        }

        .logo i {
            font-size: 24px;
        }

        /* Nav Menu */
        .main-nav-menu {
            display: flex;
            align-items: center;
            gap: 5px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .main-nav-item {
            position: relative;
        }

        .main-nav-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            border-radius: 12px;
            transition: var(--transition);
            cursor: pointer;
            border: none;
            background: none;
            white-space: nowrap;
        }

        .main-nav-link i {
            font-size: 18px;
            transition: var(--transition);
        }

        .main-nav-link:hover {
            color: var(--primary-color);
            background: var(--hover-bg);
        }

        .main-nav-link.active {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            box-shadow: var(--shadow-md);
        }

        .main-nav-link.active i {
            color: white;
        }

        /* Nav Actions */
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* Search */
        .search-box {
            position: relative;
        }

        .search-input {
            padding: 10px 40px 10px 15px;
            border: none;
            border-radius: 12px;
            background: var(--input-bg);
            color: var(--text-primary);
            font-size: 16px;
            font-family: 'Tajawal', sans-serif;
            width: 250px;
            transition: var(--transition);
            border: 2px solid transparent;
        }

        .search-input::placeholder {
            color: var(--text-secondary);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            background: var(--bg-card);
            width: 250px;
            box-shadow: var(--shadow-md);
        }

        .search-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 14px;
        }

        /* Notification */
        .notification-btn {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--input-bg);
            cursor: pointer;
            transition: var(--transition);
            border: none;
        }

        .notification-btn:hover {
            background: var(--hover-bg);
            transform: scale(1.05);
        }

        .notification-btn i {
            font-size: 18px;
            color: var(--text-secondary);
        }

        .notification-badge {
            position: absolute;
            top: 8px;
            left: 8px;
            width: 8px;
            height: 8px;
            background: var(--danger-color);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.5);
                opacity: 0.7;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* ===== DARK MODE TOGGLE BUTTON ===== */
        .dark-toggle {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--input-bg);
            cursor: pointer;
            transition: var(--transition);
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .dark-toggle:hover {
            background: var(--hover-bg);
            border-color: var(--primary-color);
            transform: scale(1.05);
        }

        .dark-toggle .icon-sun,
        .dark-toggle .icon-moon {
            position: absolute;
            font-size: 18px;
            transition: var(--transition);
        }

        /* Light mode: show moon, hide sun */
        .dark-toggle .icon-sun {
            opacity: 0;
            transform: rotate(90deg) scale(0.5);
            color: var(--warning-color);
        }

        .dark-toggle .icon-moon {
            opacity: 1;
            transform: rotate(0deg) scale(1);
            color: var(--text-secondary);
        }

        /* Dark mode: show sun, hide moon */
        body.dark-mode .dark-toggle .icon-sun {
            opacity: 1;
            transform: rotate(0deg) scale(1);
        }

        body.dark-mode .dark-toggle .icon-moon {
            opacity: 0;
            transform: rotate(-90deg) scale(0.5);
        }

        /* User Profile */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 5px 5px 5px 15px;
            border-radius: 12px;
            background: var(--input-bg);
            cursor: pointer;
            transition: var(--transition);
            border: 2px solid transparent;
        }

        .user-profile:hover {
            background: var(--hover-bg);
            border-color: var(--primary-color);
        }

        .avatar {
            width: 35px;
            height: 35px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
            box-shadow: var(--shadow-md);
        }

        .user-info {
            display: flex;
            flex-direction: column;
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            font-size: 13px;
            color: var(--text-primary);
        }

        .user-role {
            font-size: 11px;
            color: var(--text-secondary);
        }

        /* ===== SIDE PANEL ===== */
        .side-panel {
            position: fixed;
            top: 120px;
            right: 20px;
            width: 320px;
            height: calc(100vh - 140px);
            background: var(--bg-panel);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: var(--shadow-xl);
            border: 1px solid var(--border-clr);
            z-index: 999;
            transform: translateX(400px);
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .side-panel.open {
            transform: translateX(0);
            opacity: 1;
            visibility: visible;
        }

        .side-panel-header {
            padding: 20px;
            border-bottom: 2px solid var(--border-clr);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .side-panel-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-color);
        }

        .side-panel-title i {
            font-size: 22px;
        }

        .close-panel {
            width: 35px;
            height: 35px;
            border-radius: 10px;
            background: rgba(239, 71, 111, 0.1);
            border: none;
            color: var(--danger-color);
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-panel:hover {
            background: var(--danger-color);
            color: white;
            transform: rotate(90deg);
        }

        .side-panel-content {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }

        /* Child Links */
        .child-links-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .child-link {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 20px;
            background: var(--hover-bg);
            color: var(--text-primary);
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            border-radius: 12px;
            transition: var(--transition);
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .child-link::before {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            transform: scaleY(0);
            transition: var(--transition);
        }

        .child-link:hover::before {
            transform: scaleY(1);
        }

        .child-link i {
            font-size: 18px;
            width: 24px;
            color: var(--primary-color);
            transition: var(--transition);
        }

        .child-link:hover {
            background: rgba(67, 97, 238, 0.12);
            transform: translateX(-5px);
            border-color: var(--primary-color);
            box-shadow: var(--shadow-md);
        }

        .child-link:hover i {
            transform: scale(1.1);
            color: var(--secondary-color);
        }

        .child-link.active {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            box-shadow: var(--shadow-md);
        }

        .child-link.active::before {
            transform: scaleY(1);
            background: white;
        }

        .child-link.active i {
            color: white;
        }

        .child-link span {
            flex: 1;
        }

        .child-link .badge {
            padding: 4px 8px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 11px;
            font-weight: 600;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-top: 22px;
            margin-right: 20px;
            margin-left: 20px;
            padding: 20px;
            min-height: calc(100vh - 140px);
            transition: var(--transition);
        }

        .main-content.panel-open {
            margin-right: 360px;
        }

        /* Page Header */
        .page-header {
            margin-bottom: 25px;
            animation: fadeInUp 0.5s ease-out;
            text-align: right;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-title {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .breadcrumb {
            display: flex;
            gap: 8px;
            color: var(--text-secondary);
            font-size: 16px;
        }

        .breadcrumb a {
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .breadcrumb a:hover {
            opacity: 0.8;
        }

        /* Content Card */
        .content-card {
            background: var(--bg-card);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            animation: fadeInUp 0.6s ease-out;
            transition: background 0.4s ease, border-color 0.4s ease;
        }

        /* Mobile Toggle */
        .mobile-menu-toggle {
            display: none;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--input-bg);
            border: none;
            cursor: pointer;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .mobile-menu-toggle span {
            display: block;
            width: 20px;
            height: 2px;
            background: var(--text-primary);
            transition: var(--transition);
        }

        /* Overlay */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 998;
            opacity: 0;
            transition: var(--transition);
        }

        .overlay.show {
            opacity: 1;
        }

        /* Tooltip */
        [data-tooltip] {
            position: relative;
            cursor: pointer;
        }

        [data-tooltip]:before {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            padding: 6px 12px;
            background: var(--dark-color);
            color: white;
            font-size: 12px;
            border-radius: 8px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
            pointer-events: none;
            margin-bottom: 8px;
            z-index: 1001;
        }

        [data-tooltip]:hover:before {
            opacity: 1;
            visibility: visible;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 992px) {
            .main-nav-content {
                flex-wrap: wrap;
                gap: 15px;
            }

            .main-nav-menu {
                order: 3;
                width: 100%;
                justify-content: center;
                flex-wrap: wrap;
            }

            .search-input {
                width: 150px;
            }

            .search-input:focus {
                width: 200px;
            }

            .user-info {
                display: none;
            }

            .side-panel {
                width: 300px;
            }

            .main-content.panel-open {
                margin-right: 320px;
            }
        }

        @media (max-width: 768px) {
            .main-nav-container {
                top: 10px;
                left: 10px;
                right: 10px;
            }

            .main-navbar {
                padding: 10px 15px;
            }

            .main-nav-content {
                position: relative;
            }

            .mobile-menu-toggle {
                display: flex;
            }

            .main-nav-menu {
                position: absolute;
                top: 100%;
                right: 0;
                left: 0;
                background: var(--bg-navbar);
                border-radius: 12px;
                padding: 15px;
                margin-top: 10px;
                flex-direction: column;
                align-items: stretch;
                box-shadow: var(--shadow-lg);
                display: none;
            }

            .main-nav-menu.show {
                display: flex;
            }

            .main-nav-link {
                justify-content: center;
            }

            .nav-actions {
                gap: 8px;
            }

            .search-box {
                display: none;
            }

            .side-panel {
                top: 100px;
                right: 10px;
                left: 10px;
                width: auto;
                height: calc(100vh - 110px);
                transform: translateY(100vh);
            }

            .side-panel.open {
                transform: translateY(0);
            }

            .overlay {
                display: block;
            }

            .main-content {
                margin-top: 100px;
                margin-right: 10px;
                margin-left: 10px;
                padding: 15px;
            }

            .main-content.panel-open {
                margin-right: 10px;
            }

            .page-title {
                font-size: 24px;
            }

            .content-card {
                padding: 20px;
            }

            .child-link {
                padding: 12px 15px;
            }
        }

        @media (max-width: 480px) {
            .logo span {
                display: none;
            }

            .logo {
                padding: 8px 12px;
            }

            .user-profile {
                padding: 5px;
            }

            .page-title {
                font-size: 20px;
            }

            .breadcrumb {
                font-size: 12px;
            }
        }
    </style>
</head>

<body>
    @php
        $isOrdersActive = request()->routeIs('create.Invoices', 'Invoices.show', 'Invoices.track', 'Invoices.Sell');
        $isDriversActive = request()->routeIs('drivers.create', 'drivers.insert');
        $isAccountActive = request()->routeIs('accounts.create', 'accounts.insert');
        $isPayingActive = request()->routeIs('paying.create', 'paying.pay', 'paying.returnpay');
    @endphp

    <!-- Overlay -->
    <div class="overlay" id="overlay" onclick="closeSidePanel()"></div>

    <!-- Main Navigation -->
    <div class="main-nav-container">
        <nav class="main-navbar">
            <div class="main-nav-content">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="logo">
                    <i class="fas fa-chart-pie"></i>
                    <span>لوحة التحكم</span>
                </a>

                <!-- Mobile Toggle -->
                <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                    <span></span><span></span><span></span>
                </button>

                <!-- Main Nav Menu -->
                <ul class="main-nav-menu" id="mainNavMenu">
                    <li class="main-nav-item">
                        <a href="{{ route('create.Invoices') }}"
                            class="main-nav-link {{ $isOrdersActive ? 'active' : '' }}" id="ordersMainBtn">
                            <i class="fas fa-shopping-bag"></i>
                            <span>إدارة الطلبات</span>
                            <i class="fas fa-chevron-down"
                                style="font-size:12px;margin-right:5px;transition:transform 0.3s;" id="ordersArrow"></i>
                        </a>
                    </li>
                    <li class="main-nav-item">
                        <a href="{{ route('drivers.create') }}"
                            class="main-nav-link {{ $isDriversActive ? 'active' : '' }}" id="driversMainBtn">
                            <i class="fas fa-truck"></i>
                            <span>إدارة السواق</span>
                            <i class="fas fa-chevron-down"
                                style="font-size:12px;margin-right:5px;transition:transform 0.3s;"
                                id="driversArrow"></i>
                        </a>
                    </li>
                    <li class="main-nav-item">
                        <a href="{{ route('paying.create') }}"
                            class="main-nav-link {{ $isPayingActive ? 'active' : '' }}" id="payingMainBtn">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>إدارة المدفوعات</span>
                            <i class="fas fa-chevron-down"
                                style="font-size:12px;margin-right:5px;transition:transform 0.3s;" id="payingArrow"></i>
                        </a>
                    </li>
                    <li class="main-nav-item">
                        <a href="{{ route('accounts.create') }}"
                            class="main-nav-link {{ $isAccountActive ? 'active' : '' }}" id="accountMainBtn">
                            <i class="fas fa-user"></i>
                            <span>إدارة الحسابات</span>
                            <i class="fas fa-chevron-down"
                                style="font-size:12px;margin-right:5px;transition:transform 0.3s;"
                                id="accountArrow"></i>
                        </a>
                    </li>
                    <li class="main-nav-item">
                        <a href="#" class="main-nav-link">
                            <i class="fas fa-chart-line"></i>
                            <span>التقارير</span>
                        </a>
                    </li>
                    <li class="main-nav-item">
                        <a href="#" class="main-nav-link">
                            <i class="fas fa-cog"></i>
                            <span>الإعدادات</span>
                        </a>
                    </li>
                </ul>

                <!-- Right Actions -->
                <div class="nav-actions">
                    <!-- Search -->
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="search-input" placeholder="بحث...">
                    </div>

                    <!-- Dark Mode Toggle -->
                    <button class="dark-toggle" onclick="toggleDarkMode()" data-tooltip="الوضع الليلي"
                        id="darkToggleBtn">
                        <i class="fas fa-sun icon-sun"></i>
                        <i class="fas fa-moon icon-moon"></i>
                    </button>

                    <!-- Notifications -->
                    <div class="notification-btn" data-tooltip="الإشعارات">
                        <i class="far fa-bell"></i>
                        <span class="notification-badge"></span>
                    </div>

                    <!-- User Profile -->
                    <div class="user-profile" onclick="toggleUserDropdown()">
                        <div class="user-info">
                            <span class="user-name">{{ Auth::user()->name ?? 'مستخدم' }}</span>
                            <span class="user-role">مدير النظام</span>
                        </div>
                        <div class="avatar">
                            {{ substr(Auth::user()->name ?? 'م', 0, 1) }}
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <!-- Side Panel -->
    <div class="side-panel" id="sidePanel">
        <div class="side-panel-header">
            <div class="side-panel-title" id="panelTitle">
                <i class="fas" id="panelIcon"></i>
                <span id="panelTitleText">إدارة الطلبات</span>
            </div>
            <button class="close-panel" onclick="closeSidePanel()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="side-panel-content" id="panelContent">
            <!-- Content will be loaded dynamically -->
        </div>
    </div>

    <!-- Hidden templates for panel content -->
    <div style="display: none;">
        <!-- Orders Panel Content Template -->
        <div id="ordersPanelTemplate">
            <div class="child-links-list">
                <a href="{{ route('create.Invoices') }}"
                    class="child-link {{ request()->routeIs('create.Invoices') ? 'active' : '' }}">
                    <i class="fas fa-plus-circle"></i>
                    <span>إنشاء الطلبات</span>
                </a>

                <a href="{{ route('Invoices.show') }}"
                    class="child-link {{ request()->routeIs('Invoices.show') ? 'active' : '' }}">
                    <i class="fas fa-eye"></i>
                    <span>عرض الطلبيات</span>
                    <span class="badge">24</span>
                </a>

                <a href="{{ route('Invoices.track') }}"
                    class="child-link {{ request()->routeIs('Invoices.track') ? 'active' : '' }}">
                    <i class="fas fa-box"></i>
                    <span>تجهيز</span>
                    <span class="badge">12</span>
                </a>

                <a href="{{ route('Invoices.Sell') }}"
                    class="child-link {{ request()->routeIs('Invoices.Sell') ? 'active' : '' }}">
                    <i class="fas fa-check-circle"></i>
                    <span>تم البيع</span>
                    <span class="badge">8</span>
                </a>
            </div>
        </div>

        <!-- Drivers Panel Content Template -->
        <div id="driversPanelTemplate">
            <div class="child-links-list">
                <a href="{{ route('drivers.create') }}"
                    class="child-link {{ request()->routeIs('drivers.create') ? 'active' : '' }}">
                    <i class="fas fa-user-plus"></i>
                    <span>إضافة سائق جديد</span>
                </a>
    
            </div>
        </div>
        <!-- Paying Panel Content Template (FIXED: Added missing template) -->
        <div id="payingPanelTemplate">
    <div class="child-links-list">

        <a href="{{ route('paying.create') }}"
            class="child-link {{ request()->routeIs('paying.create') ? 'active' : '' }}">
            <i class="fas fa-plus-circle"></i>
            <span>دفع الديون</span>
        </a>

        <a href="{{ route('paying.returnpay') }}"
            class="child-link {{ request()->routeIs('paying.returnpay') ? 'active' : '' }}">
            <i class="fas fa-undo"></i>
            <span>ارجاع الفواتير</span>
        </a>

    </div>
</div>

        <!-- Accounts Panel Content Template -->
        <div id="accountsPanelTemplate">
            <div class="child-links-list">
                <a href="{{ route('accounts.create') }}"
                    class="child-link {{ request()->routeIs('accounts.create') ? 'active' : '' }}">
                    <i class="fas fa-user-plus"></i>
                    <span>إضافة حساب جديد</span>
                </a>
                
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <div class="content-card">
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts

    <script>
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            const isDark = document.body.classList.contains('dark-mode');
            localStorage.setItem('darkMode', isDark ? '1' : '0');
            const btn = document.getElementById('darkToggleBtn');
            if (btn) {
                btn.setAttribute('data-tooltip', isDark ? 'الوضع النهاري' : 'الوضع الليلي');
            }
        }

        (function () {
            if (localStorage.getItem('darkMode') === '1') {
                document.body.classList.add('dark-mode');
                const btn = document.getElementById('darkToggleBtn');
                if (btn) btn.setAttribute('data-tooltip', 'الوضع النهاري');
            }
        })();

        /* ===== SIDE PANEL WITH DYNAMIC CONTENT ===== */
        let currentPanelType = 'orders';

        function isMobile() { return window.innerWidth <= 768; }

        // Update panel based on current route
        function updatePanelFromRoute() {
            const isOrdersActive = {{ $isOrdersActive ? 'true' : 'false' }};
            const isDriversActive = {{ $isDriversActive ? 'true' : 'false' }};
            const isAccountActive = {{ $isAccountActive ? 'true' : 'false' }};
            const isPayingActive = {{ $isPayingActive ? 'true' : 'false' }};

            if (isOrdersActive) {
                updatePanelContent('orders');
                openPanelWithoutNavigation('orders');
            } else if (isDriversActive) {
                updatePanelContent('drivers');
                openPanelWithoutNavigation('drivers');
            } else if (isAccountActive) {
                updatePanelContent('accounts');
                openPanelWithoutNavigation('accounts');
            } else if (isPayingActive) {
                updatePanelContent('paying');
                openPanelWithoutNavigation('paying');
            } else {
                closeSidePanel();
            }
        }

        function openPanelWithoutNavigation(panelType) {
            const panel = document.getElementById('sidePanel');
            const main = document.getElementById('mainContent');
            const overlay = document.getElementById('overlay');

            // Get all arrows and buttons
            const ordersArrow = document.getElementById('ordersArrow');
            const ordersBtn = document.getElementById('ordersMainBtn');
            const driversArrow = document.getElementById('driversArrow');
            const driversBtn = document.getElementById('driversMainBtn');
            const accountArrow = document.getElementById('accountArrow');
            const accountBtn = document.getElementById('accountMainBtn');

            if (!panel || !main || !overlay) return;

            // Reset all arrows and buttons first
            if (ordersArrow) ordersArrow.style.transform = 'rotate(0deg)';
            if (ordersBtn) ordersBtn.classList.remove('active');
            if (driversArrow) driversArrow.style.transform = 'rotate(0deg)';
            if (driversBtn) driversBtn.classList.remove('active');
            if (accountArrow) accountArrow.style.transform = 'rotate(0deg)';
            if (accountBtn) accountBtn.classList.remove('active');

            // Update the selected panel
            if (panelType === 'orders') {
                if (ordersArrow) ordersArrow.style.transform = 'rotate(180deg)';
                if (ordersBtn) ordersBtn.classList.add('active');
            } else if (panelType === 'drivers') {
                if (driversArrow) driversArrow.style.transform = 'rotate(180deg)';
                if (driversBtn) driversBtn.classList.add('active');
            } else if (panelType === 'accounts') {
                if (accountArrow) accountArrow.style.transform = 'rotate(180deg)';
                if (accountBtn) accountBtn.classList.add('active');
            }

            // Open panel
            panel.classList.add('open');
            if (!isMobile()) main.classList.add('panel-open');
            overlay.classList.add('show');

            currentPanelType = panelType;
        }

        function updatePanelContent(panelType) {
            const panelTitle = document.getElementById('panelTitleText');
            const panelIcon = document.getElementById('panelIcon');
            const panelContent = document.getElementById('panelContent');

            if (!panelTitle || !panelIcon || !panelContent) return;

            if (panelType === 'orders') {
                panelTitle.textContent = 'إدارة الطلبات';
                panelIcon.className = 'fas fa-shopping-bag';
                const ordersTemplate = document.getElementById('ordersPanelTemplate');
                if (ordersTemplate) {
                    panelContent.innerHTML = ordersTemplate.innerHTML;
                }
            } else if (panelType === 'paying') {
                panelTitle.textContent = 'إدارة المدفوعات';
                panelIcon.className = 'fas fa-money-bill-wave';
                const payingTemplate = document.getElementById('payingPanelTemplate');
                if (payingTemplate) {
                    panelContent.innerHTML = payingTemplate.innerHTML;
                }
            } else if (panelType === 'drivers') {
                panelTitle.textContent = 'إدارة السواق';
                panelIcon.className = 'fas fa-truck';
                const driversTemplate = document.getElementById('driversPanelTemplate');
                if (driversTemplate) {
                    panelContent.innerHTML = driversTemplate.innerHTML;
                }
            } else if (panelType === 'accounts') {
                panelTitle.textContent = 'إدارة الحسابات';
                panelIcon.className = 'fas fa-user';
                const accountsTemplate = document.getElementById('accountsPanelTemplate');
                if (accountsTemplate) {
                    panelContent.innerHTML = accountsTemplate.innerHTML;
                }
            }
        }

        function closeSidePanel() {
            const panel = document.getElementById('sidePanel');
            const main = document.getElementById('mainContent');
            const overlay = document.getElementById('overlay');

            // Get all arrows and buttons
            const ordersArrow = document.getElementById('ordersArrow');
            const ordersBtn = document.getElementById('ordersMainBtn');
            const driversArrow = document.getElementById('driversArrow');
            const driversBtn = document.getElementById('driversMainBtn');
            const accountArrow = document.getElementById('accountArrow');
            const accountBtn = document.getElementById('accountMainBtn');

            if (!panel || !main || !overlay) return;

            panel.classList.remove('open');
            main.classList.remove('panel-open');
            overlay.classList.remove('show');

            // Reset all arrows and buttons
            if (ordersArrow) ordersArrow.style.transform = 'rotate(0deg)';
            if (ordersBtn) ordersBtn.classList.remove('active');
            if (driversArrow) driversArrow.style.transform = 'rotate(0deg)';
            if (driversBtn) driversBtn.classList.remove('active');
            if (accountArrow) accountArrow.style.transform = 'rotate(0deg)';
            if (accountBtn) accountBtn.classList.remove('active');
        }

        // Handle clicks on child links
        document.addEventListener('click', function (e) {
            const childLink = e.target.closest('.child-link');
            if (childLink) {
                e.preventDefault();
                const href = childLink.getAttribute('href');
                if (href && href !== '#') {
                    if (isMobile()) {
                        closeSidePanel();
                    }
                    window.location.href = href;
                }
            }
        });

        function toggleMobileMenu() {
            const menu = document.getElementById('mainNavMenu');
            if (menu) {
                menu.classList.toggle('show');
            }
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function (e) {
            const navMenu = document.getElementById('mainNavMenu');
            const toggleBtn = document.querySelector('.mobile-menu-toggle');

            if (window.innerWidth <= 768 && navMenu && toggleBtn) {
                if (!navMenu.contains(e.target) && !toggleBtn.contains(e.target)) {
                    navMenu.classList.remove('show');
                }
            }
        });

        // Handle resize
        window.addEventListener('resize', function () {
            const panel = document.getElementById('sidePanel');
            const main = document.getElementById('mainContent');

            if (!panel || !main) return;

            if (window.innerWidth > 768) {
                const menu = document.getElementById('mainNavMenu');
                if (menu) menu.classList.remove('show');

                if (panel.classList.contains('open')) {
                    main.classList.add('panel-open');
                }
            } else {
                main.classList.remove('panel-open');
            }
        });

        /* ===== USER DROPDOWN ===== */
        function toggleUserDropdown() {
            const existing = document.querySelector('.user-dropdown');
            if (existing) {
                existing.remove();
                return;
            }

            const dropdown = document.createElement('div');
            dropdown.className = 'user-dropdown';
            dropdown.style.cssText = `
                position: fixed; top: 80px; left: 40px;
                background: var(--bg-card);
                border-radius: 16px; padding: 10px;
                box-shadow: var(--shadow-xl); z-index: 1001;
                min-width: 220px;
                border: 1px solid var(--border-clr);
                animation: fadeInUp 0.3s ease-out;
                color: var(--text-primary);
            `;

            const userName = "{{ Auth::user()->name ?? 'مستخدم' }}";
            const userEmail = "{{ Auth::user()->email ?? 'user@example.com' }}";

            dropdown.innerHTML = `
                <div style="padding:15px;border-bottom:1px solid var(--border-clr);">
                    <div style="font-weight:600;color:var(--text-primary);">${userName}</div>
                    <div style="font-size:12px;color:var(--text-secondary);">${userEmail}</div>
                </div>
                <a href="#" style="display:flex;align-items:center;gap:12px;padding:12px 15px;text-decoration:none;color:var(--text-primary);border-radius:12px;transition:all 0.3s;" 
                   onmouseover="this.style.background='var(--hover-bg)'" 
                   onmouseout="this.style.background='transparent'">
                    <i class="fas fa-user" style="width:20px;color:var(--primary-color);"></i><span>الملف الشخصي</span>
                </a>
                <a href="#" style="display:flex;align-items:center;gap:12px;padding:12px 15px;text-decoration:none;color:var(--text-primary);border-radius:12px;transition:all 0.3s;" 
                   onmouseover="this.style.background='var(--hover-bg)'" 
                   onmouseout="this.style.background='transparent'">
                    <i class="fas fa-cog" style="width:20px;color:var(--primary-color);"></i><span>الإعدادات</span>
                </a>
                <hr style="margin:8px 0;border:none;border-top:1px solid var(--border-clr);">
                <a href="{{ route('logout') }}" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   style="display:flex;align-items:center;gap:12px;padding:12px 15px;text-decoration:none;color:var(--danger-color);border-radius:12px;transition:all 0.3s;" 
                   onmouseover="this.style.background='rgba(239,71,111,0.08)'" 
                   onmouseout="this.style.background='transparent'">
                    <i class="fas fa-sign-out-alt" style="width:20px;"></i><span>تسجيل الخروج</span>
                </a>
            `;

            document.body.appendChild(dropdown);

            if (!document.getElementById('logout-form')) {
                const logoutForm = document.createElement('form');
                logoutForm.id = 'logout-form';
                logoutForm.action = '{{ route('logout') }}';
                logoutForm.method = 'POST';
                logoutForm.style.display = 'none';
                logoutForm.innerHTML = '@csrf';
                document.body.appendChild(logoutForm);
            }

            setTimeout(() => {
                const closeDropdown = function (e) {
                    if (!dropdown.contains(e.target) && !e.target.closest('.user-profile')) {
                        dropdown.remove();
                        document.removeEventListener('click', closeDropdown);
                    }
                };
                document.addEventListener('click', closeDropdown);
            }, 100);
        }

        /* ===== NOTIFICATIONS ===== */
        document.querySelector('.notification-btn')?.addEventListener('click', function () {
            Swal.fire({
                title: 'الإشعارات',
                html: `
                    <div style="text-align:right;max-height:300px;overflow-y:auto;">
                        <div style="display:flex;align-items:center;gap:15px;padding:15px;border-bottom:1px solid #eee;">
                            <i class="fas fa-user" style="color:#4361ee;"></i>
                            <div><strong>مستخدم جديد</strong><p style="font-size:12px;color:#666;margin:5px 0 0;">قام بالتسجيل في النظام</p><small style="color:#999;">منذ 5 دقائق</small></div>
                        </div>
                        <div style="display:flex;align-items:center;gap:15px;padding:15px;border-bottom:1px solid #eee;">
                            <i class="fas fa-shopping-cart" style="color:#06d6a0;"></i>
                            <div><strong>طلب جديد</strong><p style="font-size:12px;color:#666;margin:5px 0 0;">تم إنشاء طلب جديد #12345</p><small style="color:#999;">منذ 10 دقائق</small></div>
                        </div>
                        <div style="display:flex;align-items:center;gap:15px;padding:15px;">
                            <i class="fas fa-exclamation-triangle" style="color:#ffb703;"></i>
                            <div><strong>تنبيه المخزون</strong><p style="font-size:12px;color:#666;margin:5px 0 0;">منتج XYZ على وشك النفاد</p><small style="color:#999;">منذ 15 دقيقة</small></div>
                        </div>
                    </div>`,
                showConfirmButton: false,
                showCloseButton: true,
                background: 'white'
            });
        });

        /* ===== LIVEWIRE FLASH ===== */
        if (typeof Livewire !== 'undefined') {
            Livewire.on('flash', data => {
                Swal.fire({
                    icon: data.type || 'info',
                    title: data.message || 'تم بنجاح',
                    timer: 5000,
                    showConfirmButton: false,
                    position: 'top-start',
                    toast: true,
                    timerProgressBar: true,
                    showCloseButton: true
                });
            });
        }

        /* ===== SEARCH ===== */
        const searchInput = document.querySelector('.search-input');
        if (searchInput) {
            searchInput.addEventListener('keyup', function (e) {
                if (e.key === 'Enter' && this.value.trim() !== '') {
                    Swal.fire({
                        title: 'بحث',
                        text: `جاري البحث عن: ${this.value}`,
                        icon: 'info',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });
        }

        /* ===== KEYBOARD SHORTCUTS ===== */
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                const panel = document.getElementById('sidePanel');
                if (panel && panel.classList.contains('open')) {
                    closeSidePanel();
                }

                const menu = document.getElementById('mainNavMenu');
                if (menu && menu.classList.contains('show')) {
                    menu.classList.remove('show');
                }
            }
            if (e.ctrlKey && e.key === 'k') {
                e.preventDefault();
                document.querySelector('.search-input')?.focus();
            }
        });

        // Initialize panel on load
        document.addEventListener('DOMContentLoaded', function () {
            updatePanelFromRoute();
        });
    </script>
</body>

</html>