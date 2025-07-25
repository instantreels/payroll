<?php 
$title = 'Audit Logs - System Administration';
include __DIR__ . '/../layout/header.php'; 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h1 class="text-3xl font-bold text-gray-900">Audit Logs</h1>
            <p class="mt-1 text-sm text-gray-500">
                Track and monitor all system activities and user actions
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
            <button onclick="exportAuditLogs()" class="btn btn-outline">
                <i class="fas fa-download mr-2"></i>
                Export Logs
            </button>
            <button onclick="clearOldLogs()" class="btn btn-outline">
                <i class="fas fa-trash mr-2"></i>
                Clear Old Logs
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6">
        <div class="p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">User</label>
                    <select name="user_id" id="user_id" class="form-select">
                        <option value="">All Users</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?php echo $user['id']; ?>" <?php echo $selected_user == $user['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($user['full_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label for="action" class="block text-sm font-medium text-gray-700 mb-2">Action</label>
                    <select name="action" id="action" class="form-select">
                        <option value="">All Actions</option>
                        <option value="login" <?php echo $selected_action === 'login' ? 'selected' : ''; ?>>Login</option>
                        <option value="logout" <?php echo $selected_action === 'logout' ? 'selected' : ''; ?>>Logout</option>
                        <option value="create_employee" <?php echo $selected_action === 'create_employee' ? 'selected' : ''; ?>>Create Employee</option>
                        <option value="update_employee" <?php echo $selected_action === 'update_employee' ? 'selected' : ''; ?>>Update Employee</option>
                        <option value="process_payroll" <?php echo $selected_action === 'process_payroll' ? 'selected' : ''; ?>>Process Payroll</option>
                        <option value="generate_report" <?php echo $selected_action === 'generate_report' ? 'selected' : ''; ?>>Generate Report</option>
                    </select>
                </div>
                
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="date" name="start_date" id="start_date" 
                           value="<?php echo $start_date; ?>" class="form-input">
                </div>
                
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="date" name="end_date" id="end_date" 
                           value="<?php echo $end_date; ?>" class="form-input">
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="w-full btn btn-primary">
                        <i class="fas fa-filter mr-2"></i>
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Audit Logs Table -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Timestamp
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            User
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Action
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Table/Record
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            IP Address
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Details
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (!empty($audit_logs)): ?>
                        <?php foreach ($audit_logs as $log): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo date('M j, Y H:i:s', strtotime($log['created_at'])); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?php echo htmlspecialchars($log['full_name'] ?? 'System'); ?>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <?php echo htmlspecialchars($log['username'] ?? 'system'); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        <?php 
                                        switch($log['action']) {
                                            case 'login': echo 'bg-green-100 text-green-800'; break;
                                            case 'logout': echo 'bg-gray-100 text-gray-800'; break;
                                            case 'create_employee': echo 'bg-blue-100 text-blue-800'; break;
                                            case 'update_employee': echo 'bg-yellow-100 text-yellow-800'; break;
                                            case 'delete_employee': echo 'bg-red-100 text-red-800'; break;
                                            case 'process_payroll': echo 'bg-purple-100 text-purple-800'; break;
                                            default: echo 'bg-gray-100 text-gray-800';
                                        }
                                        ?>">
                                        <?php echo ucwords(str_replace('_', ' ', $log['action'])); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php if ($log['table_name']): ?>
                                        <?php echo htmlspecialchars($log['table_name']); ?>
                                        <?php if ($log['record_id']): ?>
                                            <span class="text-gray-400">#<?php echo $log['record_id']; ?></span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                    <?php echo htmlspecialchars($log['ip_address'] ?? '-'); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <?php if ($log['old_values'] || $log['new_values']): ?>
                                        <button onclick="showLogDetails(<?php echo $log['id']; ?>)" 
                                                class="text-blue-600 hover:text-blue-900" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <i class="fas fa-search text-4xl mb-4"></i>
                                    <p class="text-lg font-medium">No audit logs found</p>
                                    <p class="text-sm">Try adjusting your filters</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if (!empty($pagination) && $pagination['total_pages'] > 1): ?>
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    <?php if ($pagination['has_previous']): ?>
                        <a href="?page=<?php echo $pagination['current_page'] - 1; ?>&<?php echo http_build_query($_GET); ?>" 
                           class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Previous
                        </a>
                    <?php endif; ?>
                    <?php if ($pagination['has_next']): ?>
                        <a href="?page=<?php echo $pagination['current_page'] + 1; ?>&<?php echo http_build_query($_GET); ?>" 
                           class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Next
                        </a>
                    <?php endif; ?>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing page <span class="font-medium"><?php echo $pagination['current_page']; ?></span> of 
                            <span class="font-medium"><?php echo $pagination['total_pages']; ?></span>
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Log Details Modal -->
<div id="log-details-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Audit Log Details</h3>
                <button onclick="closeLogDetailsModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="log-details-content">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
function showLogDetails(logId) {
    // In a real implementation, fetch log details via AJAX
    document.getElementById('log-details-content').innerHTML = `
        <div class="space-y-4">
            <div>
                <h4 class="text-sm font-medium text-gray-900 mb-2">Old Values</h4>
                <pre class="bg-gray-100 p-3 rounded text-xs overflow-x-auto">{"name": "John Doe", "status": "active"}</pre>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-900 mb-2">New Values</h4>
                <pre class="bg-gray-100 p-3 rounded text-xs overflow-x-auto">{"name": "John Smith", "status": "active"}</pre>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-900 mb-2">User Agent</h4>
                <p class="text-sm text-gray-600">Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36</p>
            </div>
        </div>
    `;
    document.getElementById('log-details-modal').classList.remove('hidden');
}

function closeLogDetailsModal() {
    document.getElementById('log-details-modal').classList.add('hidden');
}

function exportAuditLogs() {
    const params = new URLSearchParams(window.location.search);
    params.append('export', 'csv');
    
    window.location.href = '/audit?' + params.toString();
}

function clearOldLogs() {
    if (confirm('Are you sure you want to clear old audit logs? This action cannot be undone.')) {
        showLoading();
        
        fetch('/audit/clear-old', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                csrf_token: '<?php echo $this->generateCSRFToken(); ?>'
            })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showMessage(data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showMessage('An error occurred while clearing logs', 'error');
        });
    }
}

// Auto-submit form when filters change
document.getElementById('user_id').addEventListener('change', function() {
    this.form.submit();
});

document.getElementById('action').addEventListener('change', function() {
    this.form.submit();
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>