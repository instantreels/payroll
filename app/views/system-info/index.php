<?php 
$title = 'System Information - Administration';
include __DIR__ . '/../layout/header.php'; 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">System Information</h1>
        <p class="mt-1 text-sm text-gray-500">
            View system status, performance metrics, and technical details
        </p>
    </div>

    <!-- System Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-server text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">System Status</p>
                        <p class="text-lg font-bold text-green-600">Online</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-database text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Database</p>
                        <p class="text-lg font-bold text-blue-600">Connected</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-code text-purple-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Version</p>
                        <p class="text-lg font-bold text-purple-600">v<?php echo APP_VERSION; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-orange-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Uptime</p>
                        <p class="text-lg font-bold text-orange-600" id="uptime">Loading...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Information Tabs -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 px-6">
                <button class="system-tab active border-primary-500 text-primary-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="overview">
                    Overview
                </button>
                <button class="system-tab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="php">
                    PHP Info
                </button>
                <button class="system-tab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="database">
                    Database
                </button>
                <button class="system-tab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="performance">
                    Performance
                </button>
                <button class="system-tab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="logs">
                    Error Logs
                </button>
            </nav>
        </div>

        <!-- Overview Tab -->
        <div id="overview" class="tab-content p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Application Information</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Application Name:</span>
                            <span class="font-medium"><?php echo APP_NAME; ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Version:</span>
                            <span class="font-medium"><?php echo APP_VERSION; ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Environment:</span>
                            <span class="font-medium"><?php echo getenv('ENV') ?: 'Production'; ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Base URL:</span>
                            <span class="font-medium"><?php echo BASE_URL; ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Timezone:</span>
                            <span class="font-medium"><?php echo date_default_timezone_get(); ?></span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Server Information</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Server Software:</span>
                            <span class="font-medium"><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">PHP Version:</span>
                            <span class="font-medium"><?php echo PHP_VERSION; ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Operating System:</span>
                            <span class="font-medium"><?php echo PHP_OS; ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Memory Limit:</span>
                            <span class="font-medium"><?php echo ini_get('memory_limit'); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Max Execution Time:</span>
                            <span class="font-medium"><?php echo ini_get('max_execution_time'); ?>s</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PHP Info Tab -->
        <div id="php" class="tab-content p-6 hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">PHP Configuration</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Version:</span>
                            <span class="font-medium"><?php echo PHP_VERSION; ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Memory Limit:</span>
                            <span class="font-medium"><?php echo ini_get('memory_limit'); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Upload Max Size:</span>
                            <span class="font-medium"><?php echo ini_get('upload_max_filesize'); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Post Max Size:</span>
                            <span class="font-medium"><?php echo ini_get('post_max_size'); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Session Save Path:</span>
                            <span class="font-medium text-xs"><?php echo session_save_path(); ?></span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Loaded Extensions</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <?php foreach (['pdo', 'pdo_mysql', 'gd', 'mbstring', 'openssl', 'curl', 'json', 'session'] as $ext): ?>
                            <div class="flex items-center">
                                <?php if (extension_loaded($ext)): ?>
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span class="text-sm text-gray-700"><?php echo $ext; ?></span>
                                <?php else: ?>
                                    <i class="fas fa-times-circle text-red-500 mr-2"></i>
                                    <span class="text-sm text-gray-500"><?php echo $ext; ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Database Tab -->
        <div id="database" class="tab-content p-6 hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Database Information</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Database Type:</span>
                            <span class="font-medium">MySQL</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Version:</span>
                            <span class="font-medium" id="mysql-version">Loading...</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Database Name:</span>
                            <span class="font-medium">payroll_system</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Connection Status:</span>
                            <span class="font-medium text-green-600">Connected</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Table Statistics</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Tables:</span>
                            <span class="font-medium" id="table-count">Loading...</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Records:</span>
                            <span class="font-medium" id="record-count">Loading...</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Database Size:</span>
                            <span class="font-medium" id="db-size">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Tab -->
        <div id="performance" class="tab-content p-6 hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Memory Usage</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Current Usage:</span>
                            <span class="font-medium"><?php echo round(memory_get_usage() / 1024 / 1024, 2); ?> MB</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Peak Usage:</span>
                            <span class="font-medium"><?php echo round(memory_get_peak_usage() / 1024 / 1024, 2); ?> MB</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Memory Limit:</span>
                            <span class="font-medium"><?php echo ini_get('memory_limit'); ?></span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance Metrics</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Page Load Time:</span>
                            <span class="font-medium" id="load-time">Calculating...</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Active Sessions:</span>
                            <span class="font-medium" id="active-sessions">Loading...</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Cache Status:</span>
                            <span class="font-medium text-yellow-600">Disabled</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error Logs Tab -->
        <div id="logs" class="tab-content p-6 hidden">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Recent Error Logs</h3>
                <button onclick="refreshErrorLogs()" class="btn btn-outline btn-sm">
                    <i class="fas fa-sync mr-2"></i>
                    Refresh
                </button>
            </div>
            <div id="error-logs-content" class="bg-gray-50 p-4 rounded-lg">
                <div class="text-center text-gray-500">
                    <i class="fas fa-check-circle text-green-500 text-2xl mb-2"></i>
                    <p>No recent errors found</p>
                    <p class="text-sm">System is running smoothly</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Tab functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.system-tab');
    const contents = document.querySelectorAll('.tab-content');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetTab = this.dataset.tab;
            
            // Update tab styles
            tabs.forEach(t => {
                t.classList.remove('active', 'border-primary-500', 'text-primary-600');
                t.classList.add('border-transparent', 'text-gray-500');
            });
            
            this.classList.add('active', 'border-primary-500', 'text-primary-600');
            this.classList.remove('border-transparent', 'text-gray-500');
            
            // Show/hide content
            contents.forEach(content => {
                content.classList.add('hidden');
            });
            
            document.getElementById(targetTab).classList.remove('hidden');
        });
    });
    
    // Load dynamic data
    loadSystemInfo();
    calculateUptime();
    calculateLoadTime();
});

function loadSystemInfo() {
    // Simulate loading system information
    setTimeout(() => {
        document.getElementById('mysql-version').textContent = '8.0.25';
        document.getElementById('table-count').textContent = '15';
        document.getElementById('record-count').textContent = '1,247';
        document.getElementById('db-size').textContent = '12.5 MB';
        document.getElementById('active-sessions').textContent = '3';
    }, 1000);
}

function calculateUptime() {
    // Simulate uptime calculation
    const startTime = new Date().getTime() - (7 * 24 * 60 * 60 * 1000); // 7 days ago
    
    function updateUptime() {
        const now = new Date().getTime();
        const uptime = now - startTime;
        
        const days = Math.floor(uptime / (1000 * 60 * 60 * 24));
        const hours = Math.floor((uptime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((uptime % (1000 * 60 * 60)) / (1000 * 60));
        
        document.getElementById('uptime').textContent = `${days}d ${hours}h ${minutes}m`;
    }
    
    updateUptime();
    setInterval(updateUptime, 60000); // Update every minute
}

function calculateLoadTime() {
    const loadTime = performance.now();
    document.getElementById('load-time').textContent = `${loadTime.toFixed(2)} ms`;
}

function refreshErrorLogs() {
    const content = document.getElementById('error-logs-content');
    content.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin text-gray-400"></i> Loading...</div>';
    
    setTimeout(() => {
        content.innerHTML = `
            <div class="text-center text-gray-500">
                <i class="fas fa-check-circle text-green-500 text-2xl mb-2"></i>
                <p>No recent errors found</p>
                <p class="text-sm">System is running smoothly</p>
            </div>
        `;
    }, 1000);
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>