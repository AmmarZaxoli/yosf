<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <link rel="icon" type="image/png" href="{{ asset('images/logolaxe.png') }}">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة التحكم المتطورة')</title> <!-- default title -->
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Bootstrap 5 RTL CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts - Tajawal -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/styledashboard.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/styleaccounting.css') }}" />

    @livewireStyles
</head>

<body>

    <!-- Topbar -->
    <nav class="topbar">
        <button class="btn btn-link text-dark d-lg-none" type="button" id="sidebarToggle">
            <i class="bi bi-list fs-3"></i>
        </button>
    </nav>

    <!-- Sidebar Backdrop -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- Modern Sidebar -->
    <aside class="sidebar" id="sidebar">
        <!-- Sidebar Brand -->
        <div class="sidebar-brand">
            <div class="brand-content">
                <div class="logo-icon">
                    <i class="bi bi-columns-gap"></i>
                </div>
            </div>
            <button class="sidebar-toggler d-none d-lg-block" type="button">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>

        <!-- Sidebar Navigation -->
        <div class="sidebar-nav">
            <!-- Dashboard Section -->
            <div class="nav-group">

                <li class="nav-item">
                    <a class="nav-link {{ Route::is('create.invoices', 'invoices.show') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" href="#productsMenu" role="button"
                        aria-expanded="{{ Route::is('create.invoices') ? 'true' : 'false' }}">
                        <span class="nav-icon"><i class="bi bi-box-seam"></i></span>
                        <span class="nav-text">إدارة الطلبات</span>
                        <span class="nav-arrow"><i class="bi bi-chevron-left"></i></span>
                    </a>
                    <div class="collapse {{ Route::is('create.invoices', 'invoices.show', 'invoices.edit') ? 'show' : '' }}"
                        id="productsMenu">
                        <ul class="submenu">
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('create.invoices') ? 'active' : '' }}"
                                    href="{{ route('create.invoices') }}">
                                    <span class="nav-text">انشاء الطلبات</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('invoices.show') ? 'active' : '' }}"
                                    href="{{ route('invoices.show') }}">
                                    <span class="nav-text">عرض الطلبات</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('invoices.Preparation') ? 'active' : '' }}"
                                    href="{{ route('invoices.Preparation') }}">
                                    <span class="nav-text"> تحضير</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('invoices.edit') ? 'active' : '' }}"
                                    href="{{ route('invoices.edit') }}">
                                    <span class="nav-text">تعديل الطلبات</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('invoices.track') ? 'active' : '' }}"
                                    href="{{ route('invoices.track') }}">
                                    <span class="nav-text">Truck</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>



                <!-- Sidebar Footer -->
                <div class="sidebar-footer">
                    <div class="user-info">
                        <div class="user-avatar">
                            <i class="bi bi-person"></i>
                        </div>

                    </div>
                </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>

    @livewireScripts

    <script>
        Livewire.on('flash', data => {
            Swal.fire({
                icon: data.type,
                title: data.message,
                timer: 5000,
                showConfirmButton: false,
                position: 'top-end',
                toast: true,
                timerProgressBar: true,
            });
        });
    </script>



</body>

</html>