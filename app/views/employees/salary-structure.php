<?php 
$title = 'Salary Structure - ' . $employee['first_name'] . ' ' . $employee['last_name'];
include __DIR__ . '/../layout/header.php'; 
?>

<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center">
            <a href="/employees/<?php echo $employee['id']; ?>" class="text-gray-500 hover:text-gray-700 mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Salary Structure</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Configure salary components for <?php echo htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']); ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Employee Info Card -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <?php if (!empty($employee['photo'])): ?>
                        <img class="h-16 w-16 rounded-full object-cover" src="/uploads/<?php echo htmlspecialchars($employee['photo']); ?>" alt="">
                    <?php else: ?>
                        <div class="h-16 w-16 rounded-full bg-gray-300 flex items-center justify-center">
                            <span class="text-xl font-medium text-gray-600">
                                <?php echo strtoupper(substr($employee['first_name'], 0, 1) . substr($employee['last_name'], 0, 1)); ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        <?php echo htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']); ?>
                    </h3>
                    <p class="text-sm text-gray-500">
                        <?php echo htmlspecialchars($employee['emp_code']); ?> • 
                        <?php echo htmlspecialchars($employee['designation_name']); ?> • 
                        <?php echo htmlspecialchars($employee['department_name']); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Salary Structure Form -->
    <form method="POST" id="salary-structure-form" class="space-y-6">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
        
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Salary Components</h3>
                    <div>
                        <label for="effective_date" class="block text-sm font-medium text-gray-700 mb-1">Effective Date</label>
                        <input type="date" name="effective_date" id="effective_date" 
                               value="<?php echo date('Y-m-d'); ?>" 
                               class="form-input">
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <!-- Earnings -->
                <div class="mb-8">
                    <h4 class="text-md font-semibold text-green-700 mb-4">Earnings</h4>
                    <div class="space-y-4">
                        <?php foreach ($salary_components as $component): ?>
                            <?php if ($component['type'] === 'earning'): ?>
                                <div class="component-row earning-component flex items-center justify-between p-4 bg-green-50 rounded-lg" 
                                     data-component-code="<?php echo $component['code']; ?>"
                                     data-component-type="<?php echo $component['type']; ?>"
                                     data-component-name="<?php echo htmlspecialchars($component['name']); ?>"
                                     data-formula="<?php echo htmlspecialchars($component['formula'] ?? ''); ?>">
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-900">
                                            <?php echo htmlspecialchars($component['name']); ?>
                                            <?php if ($component['is_mandatory']): ?>
                                                <span class="text-red-500">*</span>
                                            <?php endif; ?>
                                        </label>
                                        <p class="text-xs text-gray-500">
                                            <?php echo htmlspecialchars($component['code']); ?>
                                            <?php if (!empty($component['formula'])): ?>
                                                • Formula: <?php echo htmlspecialchars($component['formula']); ?>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                    <div class="ml-4">
                                        <div class="flex items-center">
                                            <span class="text-green-600 mr-2">₹</span>
                                            <input type="number" 
                                                   name="components[<?php echo $component['id']; ?>]" 
                                                   value="<?php echo $current_amounts[$component['id']] ?? ''; ?>"
                                                   class="component-amount form-input w-32 text-right" 
                                                   step="0.01" 
                                                   placeholder="0.00"
                                                   <?php echo $component['is_mandatory'] ? 'required' : ''; ?>>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Deductions -->
                <div class="mb-8">
                    <h4 class="text-md font-semibold text-red-700 mb-4">Deductions</h4>
                    <div class="space-y-4">
                        <?php foreach ($salary_components as $component): ?>
                            <?php if ($component['type'] === 'deduction'): ?>
                                <div class="component-row deduction-component flex items-center justify-between p-4 bg-red-50 rounded-lg"
                                     data-component-code="<?php echo $component['code']; ?>"
                                     data-component-type="<?php echo $component['type']; ?>"
                                     data-component-name="<?php echo htmlspecialchars($component['name']); ?>"
                                     data-formula="<?php echo htmlspecialchars($component['formula'] ?? ''); ?>">
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-900">
                                            <?php echo htmlspecialchars($component['name']); ?>
                                            <?php if ($component['is_mandatory']): ?>
                                                <span class="text-red-500">*</span>
                                            <?php endif; ?>
                                        </label>
                                        <p class="text-xs text-gray-500">
                                            <?php echo htmlspecialchars($component['code']); ?>
                                            <?php if (!empty($component['formula'])): ?>
                                                • Formula: <?php echo htmlspecialchars($component['formula']); ?>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                    <div class="ml-4">
                                        <div class="flex items-center">
                                            <span class="text-red-600 mr-2">₹</span>
                                            <input type="number" 
                                                   name="components[<?php echo $component['id']; ?>]" 
                                                   value="<?php echo $current_amounts[$component['id']] ?? ''; ?>"
                                                   class="component-amount form-input w-32 text-right" 
                                                   step="0.01" 
                                                   placeholder="0.00"
                                                   <?php echo $component['is_mandatory'] ? 'required' : ''; ?>>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Summary -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h4 class="text-md font-semibold text-gray-900 mb-4">Salary Summary</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center">
                            <p class="text-sm text-gray-500">Total Earnings</p>
                            <p id="total-earnings" class="text-2xl font-bold text-green-600">₹0.00</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-500">Total Deductions</p>
                            <p id="total-deductions" class="text-2xl font-bold text-red-600">₹0.00</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-500">Net Salary</p>
                            <p id="net-salary" class="text-2xl font-bold text-blue-600">₹0.00</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-4">
            <a href="/employees/<?php echo $employee['id']; ?>" class="btn btn-outline">
                <i class="fas fa-times mr-2"></i>
                Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-2"></i>
                Save Salary Structure
            </button>
        </div>
    </form>
</div>

<script src="/public/js/payroll.js"></script>
<script>
// Initialize salary structure form
document.addEventListener('DOMContentLoaded', function() {
    setupSalaryStructureForm(document.getElementById('salary-structure-form'));
});

// Form submission
document.getElementById('salary-structure-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    showLoading();
    
    fetch('/employees/<?php echo $employee['id']; ?>/salary-structure', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showMessage(data.message, 'success');
            setTimeout(() => {
                window.location.href = '/employees/<?php echo $employee['id']; ?>';
            }, 2000);
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showMessage('An error occurred while saving salary structure', 'error');
    });
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>