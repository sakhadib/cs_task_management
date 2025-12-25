<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Internal IUT Computer Society</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Inter font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        /* Smooth transitions */
        .transition-all-300 {
            transition: all 0.3s ease;
        }
        
        /* Hide scrollbar but keep functionality */
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        
        /* Overlay for mobile sidebar */
        .sidebar-overlay {
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(2px);
        }
        
        /* Custom sidebar width when expanded/collapsed */
        .sidebar-expanded {
            width: 16rem;
        }
        .sidebar-collapsed {
            width: 5rem;
        }
        
        /* Active link styling */
        .sidebar-link.active {
            background-color: rgba(59, 130, 246, 0.2);
            border-left-color: #3b82f6;
        }
        
        /* Ensure sidebar always takes full height */
        .sidebar-container {
            height: 100vh;
        }
        
        /* Mobile sidebar positioning */
        @media (max-width: 768px) {
            .mobile-sidebar {
                transform: translateX(-100%);
            }
            .mobile-sidebar.open {
                transform: translateX(0);
            }
        }

        body {
            margin: 0;
            padding: 0;
        }
        header {
            position: sticky;
            top: 0;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen overflow-x-hidden">
    <!-- Mobile Overlay (only shown when sidebar is open on mobile) -->
    <div id="sidebarOverlay" class="fixed inset-0 z-30 sidebar-overlay hidden md:hidden transition-all-300 opacity-0"></div>
    
    <!-- Desktop Layout Wrapper -->
    <div class="md:flex md:h-screen">
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed top-0 left-0 z-40 bg-black sidebar-container transition-all-300 
                    mobile-sidebar md:relative md:translate-x-0 md:flex-shrink-0
                    sidebar-expanded md:sidebar-expanded">
            
            <!-- Sidebar Header -->
            <div class="p-4 border-b border-gray-800 flex items-center justify-between">
            <!-- Logo/Brand -->
            <div class="flex items-center space-x-3 overflow-hidden">
                <div class="w-10 h-10 rounded-lg bg-blue-600 flex items-center justify-center shrink-0">
                    <i class="fas fa-laptop-code text-white text-lg"></i>
                </div>
                <div class="whitespace-nowrap">
                    <h1 class="text-white font-bold text-lg leading-tight">IUT CS</h1>
                    <p class="text-gray-400 text-xs">Internal</p>
                </div>
            </div>
            
            <!-- Collapse Toggle Button (Desktop) -->
            <button id="sidebarToggleDesktop" class="hidden md:block text-gray-400 hover:text-white transition-all-300">
                <i class="fas fa-chevron-left"></i>
            </button>
            
            <!-- Close Button (Mobile) -->
            <button id="sidebarCloseMobile" class="md:hidden text-gray-400 hover:text-white transition-all-300">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Sidebar Menu -->
        <div class="py-4 overflow-y-auto h-[calc(100vh-5rem)] no-scrollbar">
            <ul class="space-y-1 px-2">
                <!-- Dashboard Menu Item -->
                <li>
                    <a href="#" class="sidebar-link flex items-center p-3 text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition-all-300 active">
                        <i class="fas fa-tachometer-alt text-lg w-8"></i>
                        <span class="ml-3 font-medium whitespace-nowrap">Dashboard</span>
                    </a>
                </li>

                <!-- Main Menu (kept for structure) -->
                <li class="pt-4">
                    <div class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Main Menu
                    </div>
                </li>

                <!-- Users -->
                <li>
                    <a href="{{ route('users.index') }}" class="flex items-center p-3 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-all-300">
                        <i class="fas fa-users text-lg w-8"></i>
                        <span class="ml-3 font-medium whitespace-nowrap">Users</span>
                    </a>
                </li>
                <!-- Panels -->
                <li>
                    <a href="{{ route('panels.index') }}" class="flex items-center p-3 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-all-300">
                        <i class="fas fa-th-large text-lg w-8"></i>
                        <span class="ml-3 font-medium whitespace-nowrap">Panels</span>
                    </a>
                </li>
            </ul>
            
            <!-- Sidebar Footer -->
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-800">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center">
                        <span class="text-white font-bold text-sm">AD</span>
                    </div>
                    <div class="ml-3 overflow-hidden">
                        <p class="text-white text-sm font-medium truncate">Admin User</p>
                        <p class="text-gray-400 text-xs truncate">admin@iutcs.org</p>
                    </div>
                    <a href="#" class="ml-auto text-gray-400 hover:text-white">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </div>
    </aside>
    
    <!-- Main Content Wrapper -->
    <div class="flex-1 md:flex md:flex-col md:overflow-hidden">
        <!-- Mobile Header -->
        <header class="bg-black text-white p-4 md:hidden flex items-center justify-between sticky top-0 z-50">
        <!-- Branding -->
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center">
                <i class="fas fa-laptop-code text-white text-lg"></i>
            </div>
            <h1 class="text-lg font-bold">IUT CS</h1>
        </div>

        <!-- Hamburger Button -->
        <button id="mobileMenuToggle" class="text-gray-400 hover:text-white">
            <i class="fas fa-bars text-xl"></i>
        </button>
    </header>

        <!-- Main Content Area -->
        <div id="mainContent" class="transition-all-300 md:flex-1 md:overflow-y-auto">
            @yield('content')
        </div>
    </div>
    </div>

    <script>
        // DOM Elements
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const mainContent = document.getElementById('mainContent');
        const sidebarToggleMobile = document.getElementById('sidebarToggleMobile');
        const sidebarCloseMobile = document.getElementById('sidebarCloseMobile');
        const sidebarToggleDesktop = document.getElementById('sidebarToggleDesktop');
        const quickActionsBtn = document.getElementById('quickActionsBtn');
        const quickActionsMenu = document.getElementById('quickActionsMenu');
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        
        // Sidebar State
        let isSidebarCollapsed = false;
        let isMobileSidebarOpen = false;
        
        // Toggle Mobile Sidebar
        // Debugging: Log sidebar state changes
        function toggleMobileSidebar() {
            isMobileSidebarOpen = !isMobileSidebarOpen;
            console.log('Toggling mobile sidebar. Open:', isMobileSidebarOpen);

            if (isMobileSidebarOpen) {
                if (sidebar) sidebar.classList.add('open');
                if (sidebarOverlay) sidebarOverlay.classList.remove('hidden');
                setTimeout(() => {
                    if (sidebarOverlay) sidebarOverlay.classList.remove('opacity-0');
                }, 10);
                document.body.style.overflow = 'hidden'; // Prevent scrolling when sidebar is open
            } else {
                if (sidebar) sidebar.classList.remove('open');
                if (sidebarOverlay) sidebarOverlay.classList.add('opacity-0');
                setTimeout(() => {
                    if (sidebarOverlay) sidebarOverlay.classList.add('hidden');
                }, 300);
                document.body.style.overflow = ''; // Restore scrolling
            }
        }
        
        // Toggle Desktop Sidebar (Collapse/Expand)
        function toggleDesktopSidebar() {
            isSidebarCollapsed = !isSidebarCollapsed;
            
            if (sidebar) {
                if (isSidebarCollapsed) {
                    sidebar.classList.remove('sidebar-expanded');
                    sidebar.classList.add('sidebar-collapsed');
                    if (sidebarToggleDesktop) sidebarToggleDesktop.innerHTML = '<i class="fas fa-chevron-right"></i>';
                    
                    // Hide text in sidebar when collapsed
                    document.querySelectorAll('.sidebar-container span').forEach(span => {
                        span.classList.add('hidden');
                    });
                    document.querySelectorAll('.sidebar-container .whitespace-nowrap').forEach(el => {
                        el.classList.add('hidden');
                    });
                    const textRight = document.querySelector('.sidebar-container .text-right');
                    if (textRight) textRight.classList.add('hidden');
                } else {
                    sidebar.classList.remove('sidebar-collapsed');
                    sidebar.classList.add('sidebar-expanded');
                    if (sidebarToggleDesktop) sidebarToggleDesktop.innerHTML = '<i class="fas fa-chevron-left"></i>';
                    
                    // Show text in sidebar when expanded
                    document.querySelectorAll('.sidebar-container span').forEach(span => {
                        span.classList.remove('hidden');
                    });
                    document.querySelectorAll('.sidebar-container .whitespace-nowrap').forEach(el => {
                        el.classList.remove('hidden');
                    });
                    const textRight = document.querySelector('.sidebar-container .text-right');
                    if (textRight) textRight.classList.remove('hidden');
                }
            }
        }
        
        // Adjust content margin dynamically based on sidebar state
        function adjustContentMargin() {
            // No longer needed with flex layout, but keeping for compatibility
            if (mainContent) {
                // Content adjusts automatically with flex layout
            }
        }
        
        // Toggle Quick Actions Menu
        function toggleQuickActionsMenu() {
            quickActionsMenu.classList.toggle('hidden');
        }
        
        // Event Listeners
        if (sidebarToggleMobile) sidebarToggleMobile.addEventListener('click', toggleMobileSidebar);
        if (sidebarCloseMobile) sidebarCloseMobile.addEventListener('click', toggleMobileSidebar);
        if (sidebarOverlay) sidebarOverlay.addEventListener('click', toggleMobileSidebar);
        if (sidebarToggleDesktop) {
            sidebarToggleDesktop.addEventListener('click', () => {
                toggleDesktopSidebar();
                adjustContentMargin();
            });
        }
        
        // Ensure elements exist before attaching event listeners
        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', toggleMobileSidebar);
        }

        if (quickActionsBtn && quickActionsMenu) {
            quickActionsBtn.addEventListener('click', toggleQuickActionsMenu);
            document.addEventListener('click', function(event) {
                if (!quickActionsBtn.contains(event.target) && !quickActionsMenu.contains(event.target)) {
                    quickActionsMenu.classList.add('hidden');
                }
            });
        }
        
        // Close mobile sidebar on window resize if it becomes desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768 && isMobileSidebarOpen) {
                toggleMobileSidebar();
            }
        });
        
        // Set active link on click in sidebar
        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('click', function(e) {
                if (!this.classList.contains('active')) {
                    document.querySelectorAll('.sidebar-link').forEach(item => {
                        item.classList.remove('active');
                    });
                    this.classList.add('active');
                    
                    // If on mobile, close sidebar after clicking a link
                    if (window.innerWidth < 768) {
                        toggleMobileSidebar();
                    }
                }
            });
        });
        
        // Initialize sidebar based on screen size
        function initSidebar() {
            if (window.innerWidth < 768) {
                // Mobile: ensure sidebar is hidden initially
                if (sidebar) sidebar.classList.remove('open');
                if (sidebarOverlay) sidebarOverlay.classList.add('hidden', 'opacity-0');
                isMobileSidebarOpen = false;
            } else {
                // Desktop: ensure sidebar is visible and expanded
                if (sidebar) {
                    sidebar.classList.remove('sidebar-collapsed');
                    sidebar.classList.add('sidebar-expanded');
                }
                isSidebarCollapsed = false;
            }
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', () => {
            initSidebar();
            adjustContentMargin();
        });
    </script>
</body>
</html>