<?php 
/**
 * Dashboard Widgets - Reusable dashboard components
 */
?>

<!-- Employee Statistics Widget -->
<div class="widget bg-white rounded-lg shadow-sm border p-6">
    <div class="widget-header">
        <h3 class="widget-title">Employee Statistics</h3>
        <i class="fas fa-users text-blue-500"></i>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div class="text-center">
            <div class="widget-value text-blue-600"><?php echo number_format($stats['employees']['total']); ?></div>
            <div class="text-sm text-gray-500">Total</div>
        </div>
        <div class="text-center">
            <div class="widget-value text-green-600"><?php echo number_format($stats['employees']['active']); ?></div>
            <div class="text-sm text-gray-500">Active</div>
        </div>
    </div>
</div>

<!-- Payroll Summary Widget -->
<div class="widget bg-white rounded-lg shadow-sm border p-6">
    <div class="widget-header">
        <h3 class="widget-title">Payroll Summary</h3>
        <i class="fas fa-money-bill-wave text-green-500"></i>
    </div>
    <div class="space-y-3">
        <div class="flex justify-between">
            <span class="text-gray-600">Current Period:</span>
            <span class="font-medium"><?php echo htmlspecialchars($stats['payroll']['current_period']); ?></span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-600">Processed:</span>
            <span class="font-medium"><?php echo number_format($stats['payroll']['processed_employees']); ?> employees</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-600">Net Payable:</span>
            <span class="font-medium text-green-600">₹<?php echo number_format($stats['payroll']['net_payable'], 2); ?></span>
        </div>
    </div>
</div>

<!-- Attendance Overview Widget -->
<div class="widget bg-white rounded-lg shadow-sm border p-6">
    <div class="widget-header">
        <h3 class="widget-title">Today's Attendance</h3>
        <i class="fas fa-calendar-check text-purple-500"></i>
    </div>
    <div id="attendance-widget" class="grid grid-cols-2 gap-4">
        <div class="text-center">
            <div class="widget-value text-green-600" id="present-count">0</div>
            <div class="text-sm text-gray-500">Present</div>
        </div>
        <div class="text-center">
            <div class="widget-value text-red-600" id="absent-count">0</div>
            <div class="text-sm text-gray-500">Absent</div>
        </div>
    </div>
</div>

<!-- Quick Actions Widget -->
<div class="widget bg-white rounded-lg shadow-sm border p-6">
    <div class="widget-header">
        <h3 class="widget-title">Quick Actions</h3>
        <i class="fas fa-bolt text-yellow-500"></i>
    </div>
    <div class="space-y-2">
        <a href="/employees/create" class="block w-full text-left px-3 py-2 text-sm bg-blue-50 hover:bg-blue-100 rounded-md transition-colors">
            <i class="fas fa-user-plus mr-2 text-blue-600"></i>
            Add Employee
        </a>
        <a href="/payroll/process" class="block w-full text-left px-3 py-2 text-sm bg-green-50 hover:bg-green-100 rounded-md transition-colors">
            <i class="fas fa-play-circle mr-2 text-green-600"></i>
            Process Payroll
        </a>
        <a href="/attendance/mark" class="block w-full text-left px-3 py-2 text-sm bg-yellow-50 hover:bg-yellow-100 rounded-md transition-colors">
            <i class="fas fa-calendar-check mr-2 text-yellow-600"></i>
            Mark Attendance
        </a>
        <a href="/reports/salary-register" class="block w-full text-left px-3 py-2 text-sm bg-purple-50 hover:bg-purple-100 rounded-md transition-colors">
            <i class="fas fa-file-alt mr-2 text-purple-600"></i>
            Generate Report
        </a>
    </div>
</div>

<!-- System Health Widget -->
<div class="widget bg-white rounded-lg shadow-sm border p-6">
    <div class="widget-header">
        <h3 class="widget-title">System Health</h3>
        <i class="fas fa-heartbeat text-red-500"></i>
    </div>
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <span class="text-gray-600">Database</span>
            <span class="flex items-center">
                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                <span class="text-sm text-green-600">Connected</span>
            </span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-gray-600">File System</span>
            <span class="flex items-center">
                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                <span class="text-sm text-green-600">Accessible</span>
            </span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-gray-600">Version</span>
            <span class="text-sm text-gray-600">v<?php echo APP_VERSION; ?></span>
        </div>
    </div>
</div>

<script>
// Load attendance data for widget
function loadAttendanceWidget() {
    const today = new Date().toISOString().split('T')[0];
    
    fetch(`/api/attendance-summary?date=${today}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('present-count').textContent = data.summary.present || 0;
                document.getElementById('absent-count').textContent = data.summary.absent || 0;
            }
        })
        .catch(error => {
            console.error('Error loading attendance widget:', error);
        });
}

// Load widget data on page load
document.addEventListener('DOMContentLoaded', function() {
    loadAttendanceWidget();
    
    // Refresh every 5 minutes
    setInterval(loadAttendanceWidget, 300000);
});
</script>