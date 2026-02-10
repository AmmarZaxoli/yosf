/**
 * Modern Sidebar Navigation System
 * Features:
 * - Responsive sidebar with collapse/expand functionality
 * - Persistent state using localStorage
 * - Active menu item highlighting
 * - Mobile-friendly behavior
 * - Smooth animations and transitions
 */

document.addEventListener('DOMContentLoaded', function () {
    // Initialize sidebar when DOM is fully loaded
    initSidebar();
});

/**
 * Initialize the sidebar functionality
 */
function initSidebar() {
    // Get DOM elements
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarBackdrop = document.getElementById('sidebarBackdrop');
    const sidebarToggler = document.querySelector('.sidebar-toggler');
    const body = document.body;

    // Check if elements exist before proceeding
    if (!sidebar || !sidebarToggle || !sidebarBackdrop || !sidebarToggler) {
        console.error('Sidebar elements not found');
        return;
    }

    // Set initial state from localStorage
    const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (isCollapsed) {
        sidebar.classList.add('collapsed');
    }
    adjustMainContent(sidebar, body);

    // Initialize event listeners
    setupEventListeners(sidebar, sidebarToggle, sidebarBackdrop, sidebarToggler, body);

    // Set active menu item based on current URL
    setActiveMenuItem(sidebar);

    // Handle responsive adjustments
    handleResponsiveAdjustments(sidebar, body);
}

/**
 * Adjust main content padding based on sidebar state
 */
function adjustMainContent(sidebar, body) {
    if (sidebar.classList.contains('collapsed')) {
        body.style.paddingRight = 'var(--sidebar-collapsed-width)';
    } else {
        body.style.paddingRight = 'var(--sidebar-width)';
    }
}

/**
 * Set up all event listeners for the sidebar
 */
function setupEventListeners(sidebar, sidebarToggle, sidebarBackdrop, sidebarToggler, body) {
    // Desktop toggler
    sidebarToggler.addEventListener('click', function () {
        toggleSidebar(sidebar, body);
    });

    // Mobile toggler
    sidebarToggle.addEventListener('click', function () {
        toggleMobileSidebar(sidebar, sidebarBackdrop);
    });

    // Backdrop click
    sidebarBackdrop.addEventListener('click', function () {
        toggleMobileSidebar(sidebar, sidebarBackdrop);
    });

    // Window resize handler
    window.addEventListener('resize', function () {
        handleResponsiveAdjustments(sidebar, body);
    });

    // Add ripple effect to nav items
    addRippleEffect();
}

/**
 * Toggle sidebar collapse/expand state
 */
function toggleSidebar(sidebar, body) {
    sidebar.classList.toggle('collapsed');
    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
    adjustMainContent(sidebar, body);
}

/**
 * Toggle mobile sidebar visibility
 */
function toggleMobileSidebar(sidebar, sidebarBackdrop) {
    sidebar.classList.toggle('show');
    sidebarBackdrop.classList.toggle('show');
    document.body.classList.toggle('overflow-hidden');
}

/**
 * Set active menu item based on current URL
 */
function setActiveMenuItem(sidebar) {
    const currentPath = window.location.pathname;
    const navLinks = sidebar.querySelectorAll('.nav-link');

    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentPath.includes(href)) {
            link.classList.add('active');

            // Expand parent collapse if this is a submenu item
            const parentCollapse = link.closest('.collapse');
            if (parentCollapse) {
                const collapseTrigger = document.querySelector(`[href="#${parentCollapse.id}"]`);
                if (collapseTrigger) {
                    collapseTrigger.classList.add('active');
                    collapseTrigger.setAttribute('aria-expanded', 'true');
                    parentCollapse.classList.add('show');
                }
            }
        }

        // Handle click events
        link.addEventListener('click', function (e) {
            if (this.hasAttribute('data-bs-toggle')) return;

            // Remove active class from all links
            navLinks.forEach(l => l.classList.remove('active'));

            // Add active class to clicked link
            this.classList.add('active');

            // Save active state to localStorage
            localStorage.setItem('activeLink', this.getAttribute('href'));

            // Close mobile sidebar if open
            if (window.innerWidth < 992) {
                toggleMobileSidebar(sidebar, document.getElementById('sidebarBackdrop'));
            }
        });
    });

    // Initialize collapse behavior
    initCollapseBehavior();
}

/**
 * Initialize collapse behavior for sidebar menus
 */
function initCollapseBehavior() {
    const collapseTriggers = document.querySelectorAll('[data-bs-toggle="collapse"]');

    collapseTriggers.forEach(trigger => {
        trigger.addEventListener('click', function () {
            const targetId = this.getAttribute('href');
            const isShowing = this.classList.contains('collapsed');

            if (isShowing) {
                // Close all other open collapses in the same sidebar
                document.querySelectorAll('.sidebar .collapse.show').forEach(openCollapse => {
                    if (openCollapse.id !== targetId.substring(1)) {
                        const bsCollapse = bootstrap.Collapse.getInstance(openCollapse);
                        if (bsCollapse) bsCollapse.hide();
                    }
                });
            }
        });
    });
}

/**
 * Handle responsive adjustments
 */
function handleResponsiveAdjustments(sidebar, body) {
    if (window.innerWidth < 992) {
        sidebar.classList.remove('collapsed');
        body.style.paddingRight = '0';
    } else {
        adjustMainContent(sidebar, body);
    }
}

/**
 * Add ripple effect to navigation items
 */
function addRippleEffect() {
    const navItems = document.querySelectorAll('.nav-link');

    navItems.forEach(item => {
        item.addEventListener('click', function (e) {
            // Remove any existing ripples
            const existingRipples = this.querySelectorAll('.ripple');
            existingRipples.forEach(ripple => ripple.remove());

            // Create new ripple
            const ripple = document.createElement('span');
            ripple.classList.add('ripple');
            this.appendChild(ripple);

            // Position ripple
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            ripple.style.width = ripple.style.height = `${size}px`;
            ripple.style.left = `${e.clientX - rect.left - size / 2}px`;
            ripple.style.top = `${e.clientY - rect.top - size / 2}px`;

            // Remove ripple after animation
            ripple.addEventListener('animationend', () => {
                ripple.remove();
            });
        });
    });
}

/**
 * Date-Time Auto Update
 */
function updateDateTimeOnceIfEmpty() {
    const dateTimeInput = document.getElementById('dateBuy');
    if (dateTimeInput && !dateTimeInput.value) {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        dateTimeInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
    }
}

// Initialize date time update
document.addEventListener('DOMContentLoaded', updateDateTimeOnceIfEmpty);

/**
 * Delete Confirmation Dialog
 */
window.addEventListener('show-delete-buyinvoice', event => {
    Swal.fire({
        title: "هل أنت متأكد؟",
        text: "لن تتمكن من التراجع!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "نعم، احذفه!",
        cancelButtonText: "إلغاء",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Livewire.dispatch('deleteConfirmed');
        }
    });
});



window.addEventListener('show-delete-productofinvoicebuy', event => {
    Swal.fire({
        title: "هل أنت متأكد؟",
        text: "لن تتمكن من التراجع!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "نعم، احذفه!",
        cancelButtonText: "إلغاء",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Livewire.dispatch('deleteProduct');
        }
    });
});





/**
 * Success Notification
 */
window.addEventListener('driverDelete', event => {
    Swal.fire({
        title: 'تم الحذف!',
        text: 'تم حذف السائق بنجاح.',
        icon: 'success',
        confirmButtonText: 'حسناً',
        timer: 3000,
        timerProgressBar: true
    });
});

/**
 * Livewire Event Handlers
 */
document.addEventListener('livewire:load', function () {
    // Handle receipt printing
    Livewire.on('receipt-printed', function (invoiceId) {
        window.open('/receipt/print', '_blank', 'width=600,height=800');
    });
});