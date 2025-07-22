<?php 
$title = 'Payslips - Payroll Management System';
include __DIR__ . '/../layout/header.php'; 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h1 class="text-3xl font-bold text-gray-900">Payslip Management</h1>
            <p class="mt-1 text-sm text-gray-500">
                Generate, view, and manage employee payslips
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
            <button onclick="bulkGeneratePayslips()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-file-invoice mr-2"></i>
                Bulk Generate
            </button>
            <button onclick="emailPayslips()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700">
                <i class="fas fa-envelope mr-2"></i>
                Email Payslips
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6">
        <div class="p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="period_id" class="block text-sm font-medium text-gray-700 mb-2">Payroll Period</label>
                    <select name="period_id" id="period_id" class="form-select">
                        <option value="">All Periods</option>
                        <?php if (isset($periods)): ?>
                            <?php foreach ($periods as $period): ?>
                                <option value="<?php echo $period['id']; ?>" <?php echo (isset($_GET['period_id']) && $_GET['period_id'] == $period['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($period['period_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div>
                    <label for="department_id" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                    <select name="department_id" id="department_id" class="form-select">
                        <option value="">All Departments</option>
                        <?php if (isset($departments)): ?>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?php echo $dept['id']; ?>" <?php echo (isset($_GET['department_id']) && $_GET['department_id'] == $dept['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($dept['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div>
                    <label for="employee_search" class="block text-sm font-medium text-gray-700 mb-2">Employee</label>
                    <input type="text" name="employee_search" id="employee_search" 
                           value="<?php echo htmlspecialchars($_GET['employee_search'] ?? ''); ?>"
                           class="form-input" placeholder="Search by name or code">
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

    <!-- Payslips Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Sample Payslip Cards -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-blue-600 font-medium text-sm">JD</span>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-gray-900">John Doe</h3>
                            <p class="text-xs text-gray-500">EMP001</p>
                        </div>
                    </div>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                        Generated
                    </span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Period:</span>
                        <span class="font-medium">July 2024</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Gross Salary:</span>
                        <span class="font-medium">₹44,850</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Deductions:</span>
                        <span class="font-medium text-red-600">₹6,300</span>
                    </div>
                    <div class="flex justify-between text-sm font-semibold border-t pt-2">
                        <span class="text-gray-900">Net Pay:</span>
                        <span class="text-green-600">₹38,550</span>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <button onclick="viewPayslip(1, 7)" class="text-blue-600 hover:text-blue-800" title="View">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="downloadPayslip(1, 7)" class="text-green-600 hover:text-green-800" title="Download">
                            <i class="fas fa-download"></i>
                        </button>
                        <button onclick="emailPayslip(1, 7)" class="text-purple-600 hover:text-purple-800" title="Email">
                            <i class="fas fa-envelope"></i>
                        </button>
                    </div>
                    <span class="text-xs text-gray-500">Generated on Jul 31</span>
                </div>
            </div>
        </div>

        <!-- More sample cards would be generated dynamically -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                            <span class="text-purple-600 font-medium text-sm">JS</span>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-gray-900">Jane Smith</h3>
                            <p class="text-xs text-gray-500">EMP002</p>
                        </div>
                    </div>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                        Generated
                    </span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Period:</span>
                        <span class="font-medium">July 2024</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Gross Salary:</span>
                        <span class="font-medium">₹65,850</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Deductions:</span>
                        <span class="font-medium text-red-600">₹10,800</span>
                    </div>
                    <div class="flex justify-between text-sm font-semibold border-t pt-2">
                        <span class="text-gray-900">Net Pay:</span>
                        <span class="text-green-600">₹55,050</span>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <button onclick="viewPayslip(2, 7)" class="text-blue-600 hover:text-blue-800" title="View">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="downloadPayslip(2, 7)" class="text-green-600 hover:text-green-800" title="Download">
                            <i class="fas fa-download"></i>
                        </button>
                        <button onclick="emailPayslip(2, 7)" class="text-purple-600 hover:text-purple-800" title="Email">
                            <i class="fas fa-envelope"></i>
                        </button>
                    </div>
                    <span class="text-xs text-gray-500">Generated on Jul 31</span>
                </div>
            </div>
        </div>

        <!-- Add New Payslip Card -->
        <div class="bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 hover:border-gray-400 transition-colors duration-200">
            <div class="p-6 text-center">
                <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-plus text-gray-400 text-xl"></i>
                </div>
                <h3 class="text-sm font-medium text-gray-900 mb-2">Generate New Payslip</h3>
                <p class="text-xs text-gray-500 mb-4">Create payslips for employees</p>
                <button onclick="openGenerateModal()" class="btn btn-primary btn-sm">
                    <i class="fas fa-file-invoice mr-2"></i>
                    Generate
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Generate Payslip Modal -->
<div id="generate-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Generate Payslip</h3>
            <form id="generate-form">
                <div class="mb-4">
                    <label for="gen_period_id" class="block text-sm font-medium text-gray-700 mb-2">Period *</label>
                    <select name="period_id" id="gen_period_id" required class="form-select">
                        <option value="">Select Period</option>
                        <!-- Periods would be populated here -->
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="gen_employee_id" class="block text-sm font-medium text-gray-700 mb-2">Employee *</label>
                    <select name="employee_id" id="gen_employee_id" required class="form-select">
                        <option value="">Select Employee</option>
                        <!-- Employees would be populated here -->
                    </select>
                </div>
                
                <div class="mb-6">
                    <label for="format" class="block text-sm font-medium text-gray-700 mb-2">Format</label>
                    <select name="format" id="format" class="form-select">
                        <option value="pdf">PDF</option>
                        <option value="html">HTML</option>
                    </select>
                </div>
                
                <div class="flex items-center justify-end space-x-4">
                    <button type="button" onclick="closeGenerateModal()" class="btn btn-outline">Cancel</button>
                    <button type="submit" class="btn btn-primary">Generate</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function viewPayslip(employeeId, periodId) {
    window.open(`/payroll/payslip/${employeeId}/${periodId}`, '_blank');
}

function downloadPayslip(employeeId, periodId) {
    window.location.href = `/payroll/payslip/${employeeId}/${periodId}?download=1`;
}

function emailPayslip(employeeId, periodId) {
    if (confirm('Send payslip via email to the employee?')) {
        showLoading();
        
        fetch(`/payroll/email-payslip/${employeeId}/${periodId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showMessage('Payslip sent successfully', 'success');
            } else {
                showMessage(data.message || 'Failed to send payslip', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showMessage('Failed to send payslip', 'error');
        });
    }
}

function bulkGeneratePayslips() {
    showMessage('Bulk generation feature coming soon', 'info');
}

function emailPayslips() {
    showMessage('Bulk email feature coming soon', 'info');
}

function openGenerateModal() {
    document.getElementById('generate-modal').classList.remove('hidden');
}

function closeGenerateModal() {
    document.getElementById('generate-modal').classList.add('hidden');
}

// Auto-submit form when filters change
document.getElementById('period_id').addEventListener('change', function() {
    this.form.submit();
});

document.getElementById('department_id').addEventListener('change', function() {
    this.form.submit();
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>