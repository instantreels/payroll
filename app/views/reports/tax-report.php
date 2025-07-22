<?php 
$title = 'Tax Reports - Reports';
include __DIR__ . '/../layout/header.php'; 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center">
            <a href="/reports" class="text-gray-500 hover:text-gray-700 mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <?php echo strtoupper($type); ?> Report
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Generate <?php echo strtoupper($type); ?> compliance reports
                </p>
            </div>
        </div>
    </div>

    <!-- Report Type Tabs -->
    <div class="mb-6">
        <nav class="flex space-x-8">
            <a href="/reports/tax-report?type=tds" 
               class="<?php echo $type === 'tds' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'; ?> whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                TDS Report
            </a>
            <a href="/reports/tax-report?type=pf" 
               class="<?php echo $type === 'pf' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'; ?> whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                PF Report
            </a>
            <a href="/reports/tax-report?type=esi" 
               class="<?php echo $type === 'esi' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'; ?> whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                ESI Report
            </a>
        </nav>
    </div>

    <!-- Report Filters -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Report Parameters</h3>
        </div>
        <div class="p-6">
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <?php if ($type === 'tds'): ?>
                        <div>
                            <label for="financial_year" class="block text-sm font-medium text-gray-700 mb-2">Financial Year *</label>
                            <select name="financial_year" id="financial_year" required class="form-select">
                                <option value="">Select Financial Year</option>
                                <?php foreach ($financial_years as $fy): ?>
                                    <option value="<?php echo $fy['financial_year']; ?>">
                                        FY <?php echo $fy['financial_year']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php else: ?>
                        <div>
                            <label for="period_id" class="block text-sm font-medium text-gray-700 mb-2">Period *</label>
                            <select name="period_id" id="period_id" required class="form-select">
                                <option value="">Select Period</option>
                                <?php foreach ($periods as $period): ?>
                                    <option value="<?php echo $period['id']; ?>">
                                        <?php echo htmlspecialchars($period['period_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    
                    <div>
                        <label for="format" class="block text-sm font-medium text-gray-700 mb-2">Export Format</label>
                        <select name="format" id="format" class="form-select">
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="csv">CSV (.csv)</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="w-full btn btn-primary">
                            <i class="fas fa-download mr-2"></i>
                            Generate Report
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Report Information -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Report Description -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <?php echo strtoupper($type); ?> Report Details
                </h3>
            </div>
            <div class="p-6">
                <div class="text-sm text-gray-600 space-y-3">
                    <?php if ($type === 'tds'): ?>
                        <p><strong>Purpose:</strong> Income Tax (TDS) deduction summary for employees</p>
                        <p><strong>Includes:</strong></p>
                        <ul class="list-disc list-inside space-y-1 ml-4">
                            <li>Employee details with PAN numbers</li>
                            <li>Annual taxable income</li>
                            <li>TDS deducted amount</li>
                            <li>Tax calculation breakdown</li>
                        </ul>
                        <p><strong>Use Case:</strong> Form 16 preparation, TDS returns filing</p>
                    <?php elseif ($type === 'pf'): ?>
                        <p><strong>Purpose:</strong> Provident Fund contribution summary</p>
                        <p><strong>Includes:</strong></p>
                        <ul class="list-disc list-inside space-y-1 ml-4">
                            <li>Employee PF numbers and UAN</li>
                            <li>Employee and employer contributions</li>
                            <li>Basic salary for PF calculation</li>
                            <li>Monthly contribution details</li>
                        </ul>
                        <p><strong>Use Case:</strong> ECR file generation, PF compliance</p>
                    <?php else: ?>
                        <p><strong>Purpose:</strong> Employee State Insurance contribution summary</p>
                        <p><strong>Includes:</strong></p>
                        <ul class="list-disc list-inside space-y-1 ml-4">
                            <li>Employee ESI numbers</li>
                            <li>Employee and employer contributions</li>
                            <li>Gross salary for ESI calculation</li>
                            <li>ESI eligibility status</li>
                        </ul>
                        <p><strong>Use Case:</strong> ESI returns filing, compliance reporting</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sample Data -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Sample Report Data</h3>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Emp Code</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <?php if ($type === 'tds'): ?>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">PAN</th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">TDS</th>
                                <?php elseif ($type === 'pf'): ?>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">UAN</th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">PF</th>
                                <?php else: ?>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">ESI No</th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">ESI</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr class="text-sm">
                                <td class="px-3 py-2 text-gray-500">EMP001</td>
                                <td class="px-3 py-2 text-gray-500">John Doe</td>
                                <?php if ($type === 'tds'): ?>
                                    <td class="px-3 py-2 text-gray-500 font-mono">ABCDE1234F</td>
                                    <td class="px-3 py-2 text-right font-medium text-gray-900">₹2,500</td>
                                <?php elseif ($type === 'pf'): ?>
                                    <td class="px-3 py-2 text-gray-500 font-mono">123456789012</td>
                                    <td class="px-3 py-2 text-right font-medium text-gray-900">₹3,600</td>
                                <?php else: ?>
                                    <td class="px-3 py-2 text-gray-500 font-mono">1234567890</td>
                                    <td class="px-3 py-2 text-right font-medium text-gray-900">₹338</td>
                                <?php endif; ?>
                            </tr>
                            <tr class="text-sm">
                                <td class="px-3 py-2 text-gray-500">EMP002</td>
                                <td class="px-3 py-2 text-gray-500">Jane Smith</td>
                                <?php if ($type === 'tds'): ?>
                                    <td class="px-3 py-2 text-gray-500 font-mono">FGHIJ5678K</td>
                                    <td class="px-3 py-2 text-right font-medium text-gray-900">₹5,200</td>
                                <?php elseif ($type === 'pf'): ?>
                                    <td class="px-3 py-2 text-gray-500 font-mono">123456789013</td>
                                    <td class="px-3 py-2 text-right font-medium text-gray-900">₹5,400</td>
                                <?php else: ?>
                                    <td class="px-3 py-2 text-gray-500 font-mono">1234567891</td>
                                    <td class="px-3 py-2 text-right font-medium text-gray-900">₹0</td>
                                <?php endif; ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Form submission for tax report generation
document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const requiredField = '<?php echo $type === "tds" ? "financial_year" : "period_id"; ?>';
    
    if (!formData.get(requiredField)) {
        showMessage('Please select the required field', 'error');
        return;
    }
    
    showLoading();
    
    // Convert FormData to JSON
    const data = Object.fromEntries(formData);
    
    fetch('/reports/tax-report?type=<?php echo $type; ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        hideLoading();
        
        if (response.ok) {
            const contentType = response.headers.get('content-type');
            
            if (contentType && contentType.includes('application/json')) {
                return response.json().then(data => {
                    if (data.success) {
                        showMessage('Report generated successfully', 'success');
                    } else {
                        showMessage(data.message || 'Failed to generate report', 'error');
                    }
                });
            } else {
                // File download
                const filename = `<?php echo $type; ?>_report_${new Date().toISOString().split('T')[0]}.${data.format}`;
                
                return response.blob().then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);
                    
                    showMessage('Report downloaded successfully', 'success');
                });
            }
        } else {
            showMessage('Failed to generate report', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showMessage('An error occurred while generating the report', 'error');
    });
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>