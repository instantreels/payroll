<?php if (isset($_SESSION['user_id'])): ?>
<!-- Sidebar for mobile and desktop -->
<div id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0">
    <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200">
        <div class="flex items-center">
            <i class="fas fa-calculator text-primary-500 text-xl mr-2"></i>
            <span class="font-bold text-lg text-gray-900">PayrollPro</span>
        </div>
        <button id="sidebar-close" class="lg:hidden text-gray-500 hover:text-gray-700">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <nav class="mt-6">
        <div class="px-3">
            <!-- Dashboard -->
            <a href="/dashboard" class="sidebar-link <?php echo ($_SERVER['REQUEST_URI'] == '/dashboard') ? 'active' : ''; ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                <i class="fas fa-tachometer-alt mr-3 text-gray-400 group-hover:text-gray-500"></i>
                Dashboard
            </a>
            
            <?php if (isset($_SESSION['permissions']) && (str_contains($_SESSION['permissions'], 'employees') || $_SESSION['permissions'] === 'all')): ?>
            <!-- Employees -->
            <a href="/employees" class="sidebar-link <?php echo str_contains($_SERVER['REQUEST_URI'], '/employees') ? 'active' : ''; ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                <i class="fas fa-users mr-3 text-gray-400 group-hover:text-gray-500"></i>
                Employees
            </a>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['permissions']) && (str_contains($_SESSION['permissions'], 'payroll') || $_SESSION['permissions'] === 'all')): ?>
            <!-- Payroll -->
            <div class="mt-1">
                <button class="sidebar-dropdown-toggle w-full group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    <i class="fas fa-money-bill-wave mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Payroll
                    <i class="fas fa-chevron-right ml-auto transform transition-transform duration-200"></i>
                </button>
                <div class="sidebar-dropdown mt-1 space-y-1 hidden">
                    <a href="/payroll" class="block px-10 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md">Overview</a>
                    <a href="/payroll/periods" class="block px-10 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md">Periods</a>
                    <a href="/payroll/process" class="block px-10 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md">Process</a>
                </div>
            </div>
            
            <!-- Attendance -->
            <a href="/attendance" class="sidebar-link <?php echo str_contains($_SERVER['REQUEST_URI'], '/attendance') ? 'active' : ''; ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                <i class="fas fa-calendar-check mr-3 text-gray-400 group-hover:text-gray-500"></i>
                Attendance
            </a>
            
            <!-- Loans -->
            <a href="/loans" class="sidebar-link <?php echo str_contains($_SERVER['REQUEST_URI'], '/loans') ? 'active' : ''; ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                <i class="fas fa-hand-holding-usd mr-3 text-gray-400 group-hover:text-gray-500"></i>
                Loans
            </a>
            
            <!-- Reports -->
            <a href="/reports" class="sidebar-link <?php echo str_contains($_SERVER['REQUEST_URI'], '/reports') ? 'active' : ''; ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                <i class="fas fa-chart-bar mr-3 text-gray-400 group-hover:text-gray-500"></i>
                Reports
            </a>
            <?php endif; ?>
            
            <!-- Masters -->
            <div class="mt-1">
                <button class="sidebar-dropdown-toggle w-full group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    <i class="fas fa-cogs mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Masters
                    <i class="fas fa-chevron-right ml-auto transform transition-transform duration-200"></i>
                </button>
                <div class="sidebar-dropdown mt-1 space-y-1 hidden">
                    <a href="/departments" class="block px-10 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md">Departments</a>
                    <a href="/designations" class="block px-10 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md">Designations</a>
                    <a href="/salary-components" class="block px-10 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md">Salary Components</a>
                    <a href="/loan-types" class="block px-10 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md">Loan Types</a>
                    <a href="/leave-types" class="block px-10 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md">Leave Types</a>
                    <a href="/holidays" class="block px-10 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md">Holidays</a>
                </div>
            </div>
            
            <?php if (isset($_SESSION['permissions']) && (str_contains($_SESSION['permissions'], 'users') || $_SESSION['permissions'] === 'all')): ?>
            <!-- User Management -->
            <a href="/users" class="sidebar-link <?php echo str_contains($_SERVER['REQUEST_URI'], '/users') ? 'active' : ''; ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                <i class="fas fa-user-cog mr-3 text-gray-400 group-hover:text-gray-500"></i>
                Users
            </a>
            <?php endif; ?>
        </div>
        
        <!-- User Profile Section -->
        <div class="mt-8 pt-6 border-t border-gray-200 px-3">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 rounded-full bg-primary-500 flex items-center justify-center">
                        <span class="text-white text-sm font-medium">
                            <?php echo strtoupper(substr($_SESSION['full_name'] ?? 'U', 0, 1)); ?>
                        </span>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-700"><?php echo $_SESSION['full_name'] ?? 'User'; ?></p>
                    <p class="text-xs text-gray-500"><?php echo $_SESSION['role'] ?? 'User'; ?></p>
                </div>
            </div>
            
            <div class="mt-3 space-y-1">
                <a href="/profile" class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md">
                    <i class="fas fa-user mr-2"></i>Profile
                </a>
                <a href="/change-password" class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md">
                    <i class="fas fa-key mr-2"></i>Change Password
                </a>
                <a href="/logout" class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            </div>
        </div>
    </nav>
</div>

<!-- Sidebar overlay for mobile -->
<div id="sidebar-overlay" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-40 lg:hidden hidden"></div>

<script>
// Sidebar functionality
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');
    const sidebarClose = document.getElementById('sidebar-close');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    
    // Toggle sidebar on mobile
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.remove('hidden');
        });
    }
    
    // Close sidebar
    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        sidebarOverlay.classList.add('hidden');
    }
    
    if (sidebarClose) {
        sidebarClose.addEventListener('click', closeSidebar);
    }
    
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeSidebar);
    }
    
    // Dropdown functionality
    document.querySelectorAll('.sidebar-dropdown-toggle').forEach(toggle => {
        toggle.addEventListener('click', function() {
            const dropdown = this.nextElementSibling;
            const chevron = this.querySelector('.fa-chevron-right');
            
            dropdown.classList.toggle('hidden');
            chevron.classList.toggle('rotate-90');
        });
    });
});
</script>

<style>
.sidebar-link {
    @apply text-gray-700 hover:text-gray-900 hover:bg-gray-50;
}

.sidebar-link.active {
    @apply bg-primary-50 text-primary-700 border-r-2 border-primary-500;
}

.sidebar-dropdown {
    @apply pl-6;
}

.rotate-90 {
    transform: rotate(90deg);
}
</style>
<?php endif; ?>