<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'لوحة التحكم')</title>

    <!-- Bootstrap RTL CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400;500;600;700&display=swap">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    @livewireStyles
</head>

<body>
    <div id="sidebarBackdrop" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon">
                <i class="bi bi-grid-3x3-gap-fill"></i>
            </div>
            <span class="brand-text">لوحة التحكم</span>
        </div>

        <nav class="sidebar-nav">
            <div class="section-title">لوحة التحكم</div>

            <div class="nav-item-wrapper">
                <button class="nav-btn" onclick="toggleSub('sub-student', this)">
                    <i class="bi-bag-check nav-icon"></i>
                    <span class="nav-label">إدارة الطلبات</span>
                    <i
                        class="bi bi-chevron-down nav-arrow {{ request()->routeIs('create.Invoices*', 'Invoices.show*', 'Invoices.Sell*', 'Invoices.track*') ? 'open' : '' }}"></i>
                </button>

                <div class="subnav {{ request()->routeIs('create.Invoices*', 'Invoices.show*', 'Invoices.Sell*', 'Invoices.track*') ? 'open' : '' }}"
                    id="sub-student">
                    <a class="sub-btn {{ request()->routeIs('create.Invoices') ? 'active' : '' }}"
                        href="{{ route('create.Invoices') }}">
                        <span class="nav-label">إنشاء الطلبات</span>
                    </a>
                    <a class="sub-btn {{ request()->routeIs('Invoices.show') ? 'active' : '' }}"
                        href="{{ route('Invoices.show') }}">
                        <span class="nav-label">عرض الطلبيات</span>
                    </a>
                    {{-- <a class="sub-btn {{ request()->routeIs('Invoices.Sell') ? 'active' : '' }}"
                        href="{{ route('Invoices.Sell') }}">
                        <span class="nav-label">تجهيز</span>
                    </a>
                    <a class="sub-btn {{ request()->routeIs('Invoices.track') ? 'active' : '' }}"
                        href="{{ route('Invoices.track') }}">
                        <span class="nav-label">تم البيع</span>
                    </a> --}}
                </div>
            </div>

            <div class="nav-item-wrapper">
                <a class="nav-btn {{ request()->routeIs('drivers.create') ? 'active' : '' }}"
                    href="{{ route('drivers.create') }}">
                    <span class="nav-icon"><i class="bi-truck"></i></span>
                    <span class="nav-label">إدارة السائقين</span>
                </a>
            </div>

            <div class="nav-item-wrapper">
                <a class="nav-btn {{ request()->routeIs('accounts.create') ? 'active' : '' }}"
                    href="{{ route('accounts.create') }}">
                    <span class="nav-icon"><i class="bi bi-people"></i></span>
                    <span class="nav-label">إدارة المستخدمين</span>
                </a>
            </div>
            <div class="nav-item-wrapper">
                <a class="nav-btn {{ request()->routeIs('paying.create') ? 'active' : '' }}"
                    href="{{ route('paying.create') }}">
                    <span class="nav-icon"><i class="bi bi-cash-coin"></i></span>
                    <span class="nav-label"> دفع الديون</span>
                </a>
            </div>
            <div class="nav-item-wrapper">
                <a class="nav-btn {{ request()->routeIs('paying.returnpay') ? 'active' : '' }}"
                    href="{{ route('paying.returnpay') }}">
                    <span class="nav-icon"><i class="bi bi-arrow-counterclockwise"></i></span>
                    <span class="nav-label">ارجاع الفواتير</span>
                </a>
            </div>


        </nav>

        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar">
                    {{ Auth::check() ? mb_substr(Auth::user()->name, 0, 1) : 'G' }}
                </div>
                <div class="user-info">
                    <div class="user-name">
                        {{ Auth::check() ? Auth::user()->name : 'Guest' }}
                    </div>
                    <div class="user-role">
                        {{ Auth::check() ? (Auth::user()->role == 'admin' ? 'مسؤول' : 'مستخدم') : 'System User' }}
                    </div>
                </div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" id="logoutForm" class="logout-form">
            @csrf
            <button type="button" id="logoutBtn" class="logout-btn-glass">
                <div class="btn-content">
                    <i class="bi bi-box-arrow-right btn-icon"></i>
                    <span>تسجيل الخروج</span>
                    <div class="btn-shine"></div>
                </div>
            </button>
        </form>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="main-wrapper" id="mainWrapper">
        <!-- Top Navigation -->
        <nav class="topnav">
            <div class="topnav-right">
                <button class="toggle-sidebar-btn" onclick="toggleSidebar()" type="button">
                    <i class="bi bi-layout-sidebar-reverse"></i>
                </button>

                <div class="breadcrumb-area">
                    <div class="page-title" id="pageTitle">@yield('page_title', 'الرئيسية')</div>
                    <div class="page-sub">@yield('page_subtitle', 'لوحة التحكم الرئيسية')</div>
                </div>
            </div>

            <div class="topnav-left">
                <button class="theme-toggle" onclick="toggleTheme()" id="themeToggle" type="button">
                    <i class="bi bi-sun-fill toggle-icon" id="themeIcon"></i>
                    <span id="themeLabel">وضع النهار</span>
                </button>

                <div class="notif-wrapper">
                    <button class="notif-btn" onclick="toggleNotif()" id="notifBtn" type="button">
                        <i class="bi bi-bell-fill"></i>
                        <span class="notif-badge"></span>
                    </button>

                    <div class="notif-dropdown" id="notifDropdown">
                        <div class="notif-header">
                            <span>الإشعارات</span>
                            <span class="notif-mark-read">تحديد الكل كمقروء</span>
                        </div>

                        <div class="notif-item">
                            <div class="notif-dot notif-success">
                                <i class="bi bi-person-plus-fill"></i>
                            </div>
                            <div>
                                <div class="notif-text">تم إضافة مستخدم جديد بنجاح</div>
                                <div class="notif-time">منذ 5 دقائق</div>
                            </div>
                        </div>

                        <div class="notif-item">
                            <div class="notif-dot notif-warning">
                                <i class="bi bi-cart-fill"></i>
                            </div>
                            <div>
                                <div class="notif-text">طلب جديد #ORD-2024-089</div>
                                <div class="notif-time">منذ 18 دقيقة</div>
                            </div>
                        </div>

                        <div class="notif-item">
                            <div class="notif-dot notif-danger">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                            </div>
                            <div>
                                <div class="notif-text">تحذير: نفاد مخزون منتج #145</div>
                                <div class="notif-time">منذ 45 دقيقة</div>
                            </div>
                        </div>

                        <div class="notif-item">
                            <div class="notif-dot notif-info">
                                <i class="bi bi-file-earmark-bar-graph-fill"></i>
                            </div>
                            <div>
                                <div class="notif-text">تقرير المبيعات الشهري جاهز</div>
                                <div class="notif-time">منذ ساعتين</div>
                            </div>
                        </div>
                    </div>
                </div>

                <button class="nav-icon-btn" title="الإعدادات" data-bs-toggle="modal" data-bs-target="#settingsModal"
                    type="button">
                    <i class="bi bi-gear-fill"></i>
                </button>

                <button class="nav-icon-btn" title="الملف الشخصي" data-bs-toggle="modal" data-bs-target="#profileModal"
                    type="button">
                    <i class="bi bi-person-circle"></i>
                </button>
            </div>
        </nav>

        <main class="main-content">
            @yield('content')
        </main>

        <footer class="main-footer">
            جميع الحقوق محفوظة © {{ date('Y') }}
        </footer>
    </div>

    <!-- Settings Modal -->
    <div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="settingsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="settingsModalLabel">الإعدادات</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>يمكنك إدارة إعدادات النظام هنا.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Modal -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileModalLabel">الملف الشخصي</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>معلومات المستخدم والملف الشخصي.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-primary">تحديث الملف</button>
                </div>
            </div>
        </div>
    </div>



    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>

    @livewireScripts

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainWrapper = document.getElementById('mainWrapper');
            const backdrop = document.getElementById('sidebarBackdrop');

            if (!sidebar || !mainWrapper || !backdrop) return;

            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('mobile-open');
                backdrop.classList.toggle('show');
            } else {
                sidebar.classList.toggle('collapsed');
                mainWrapper.classList.toggle('collapsed');
            }
        }

        function toggleSub(subId, btn) {
            const sub = document.getElementById(subId);
            const arrow = btn ? btn.querySelector('.nav-arrow') : null;

            if (sub) sub.classList.toggle('open');
            if (arrow) arrow.classList.toggle('open');
        }

        function setThemeUI(theme) {
            const themeIcon = document.getElementById('themeIcon');
            const themeLabel = document.getElementById('themeLabel');

            if (themeIcon) {
                themeIcon.className = theme === 'dark'
                    ? 'bi bi-moon-fill toggle-icon'
                    : 'bi bi-sun-fill toggle-icon';
            }

            if (themeLabel) {
                themeLabel.innerText = theme === 'dark' ? 'وضع الليل' : 'وضع النهار';
            }
        }

        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme') || 'light';
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';

            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            setThemeUI(newTheme);
        }

        function toggleNotif() {
            const dropdown = document.getElementById('notifDropdown');
            if (dropdown) {
                dropdown.classList.toggle('show');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            setThemeUI(savedTheme);

            const notifBtn = document.getElementById('notifBtn');
            const notifDropdown = document.getElementById('notifDropdown');

            document.addEventListener('click', function (event) {
                if (
                    notifBtn &&
                    notifDropdown &&
                    !notifBtn.contains(event.target) &&
                    !notifDropdown.contains(event.target)
                ) {
                    notifDropdown.classList.remove('show');
                }
            });

            window.addEventListener('resize', function () {
                const sidebar = document.getElementById('sidebar');
                const backdrop = document.getElementById('sidebarBackdrop');

                if (window.innerWidth > 768) {
                    if (sidebar) sidebar.classList.remove('mobile-open');
                    if (backdrop) backdrop.classList.remove('show');
                }
            });
        });
        // Add this to your script section
document.addEventListener('DOMContentLoaded', function() {
    const logoutBtn = document.getElementById('logoutBtn');
    const logoutForm = document.getElementById('logoutForm');
    
    if (logoutBtn && logoutForm) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
            const isDark = currentTheme === 'dark';
            
            // Beautiful confirmation dialog
            Swal.fire({
                title: 'تسجيل الخروج',
                html: `
                    <div class="logout-modal">
                        <div class="logout-icon-wrapper">
                            <div class="logout-icon-circle">
                                <i class="bi bi-box-arrow-right"></i>
                            </div>
                        </div>
                        <h3 class="logout-title">هل أنت مستعد للمغادرة؟</h3>
                        <p class="logout-message">سيتم تسجيل خروجك من النظام</p>
                        <div class="logout-warning">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <span>تأكد من حفظ جميع بياناتك قبل المغادرة</span>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'نعم، تسجيل خروج',
                cancelButtonText: 'إلغاء',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                reverseButtons: true,
                background: isDark ? '#1a1a2e' : '#ffffff',
                color: isDark ? '#ffffff' : '#000000',
                width: '480px',
                padding: '2rem',
                showClass: {
                    popup: 'animate__animated animate__zoomIn animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__zoomOut animate__faster'
                },
                customClass: {
                    popup: 'premium-swal-popup',
                    confirmButton: 'premium-swal-confirm',
                    cancelButton: 'premium-swal-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Beautiful loading animation
                    Swal.fire({
                        title: 'جاري تسجيل الخروج',
                        html: `
                            <div class="loading-container">
                                <div class="loading-spinner">
                                    <div class="spinner-ring"></div>
                                    <div class="spinner-ring"></div>
                                    <div class="spinner-ring"></div>
                                    <div class="spinner-icon">
                                        <i class="bi bi-door-closed"></i>
                                    </div>
                                </div>
                                <p class="loading-text">يتم إنهاء الجلسة الحالية...</p>
                                <p class="loading-subtext">يرجى الانتظار لحظة</p>
                            </div>
                        `,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        background: isDark ? '#1a1a2e' : '#ffffff',
                        color: isDark ? '#ffffff' : '#000000',
                        width: '450px',
                        padding: '2rem',
                        customClass: {
                            popup: 'loading-swal-popup'
                        }
                    });
                    
                    // Submit form after animation
                    setTimeout(() => {
                        logoutForm.submit();
                    }, 1500);
                }
            });
        });
    }
});
    </script>
</body>

</html>