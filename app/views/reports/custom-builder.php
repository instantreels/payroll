<?php 
$title = 'Custom Report Builder - Reports';
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
                <h1 class="text-3xl font-bold text-gray-900">Custom Report Builder</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Build custom reports with flexible data selection
                </p>
            </div>
        </div>
    </div>

    <!-- Report Builder -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Configuration Panel -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Data Sources -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Data Sources</h3>
                </div>
                <div class="p-6">
                    <form id="report-builder-form">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Select Tables</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <label class="flex items-center">
                                    <input type="checkbox" name="tables[]" value="employees" class="form-checkbox table-checkbox">
                                    <span class="ml-2 text-sm text-gray-700">Employees</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="tables[]" value="payroll_transactions" class="form-checkbox table-checkbox">
                                    <span class="ml-2 text-sm text-gray-700">Payroll Transactions</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="tables[]" value="salary_components" class="form-checkbox table-checkbox">
                                    <span class="ml-2 text-sm text-gray-700">Salary Components</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="tables[]" value="departments" class="form-checkbox table-checkbox">
                                    <span class="ml-2 text-sm text-gray-700">Departments</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="tables[]" value="designations" class="form-checkbox table-checkbox">
                                    <span class="ml-2 text-sm text-gray-700">Designations</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="tables[]" value="attendance" class="form-checkbox table-checkbox">
                                    <span class="ml-2 text-sm text-gray-700">Attendance</span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Field Selection -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Select Fields</label>
                            <div id="field-selection" class="space-y-4">
                                <!-- Fields will be populated based on table selection -->
                            </div>
                        </div>
                        
                        <!-- Filters -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Filters</label>
                            <div id="filter-section" class="space-y-3">
                                <div class="filter-row flex items-center space-x-3">
                                    <select class="form-select w-1/3" name="filter_field[]">
                                        <option value="">Select Field</option>
                                    </select>
                                    <select class="form-select w-1/4" name="filter_operator[]">
                                        <option value="=">=</option>
                                        <option value="!=">!=</option>
                                        <option value=">">></option>
                                        <option value="<"><</option>
                                        <option value="LIKE">Contains</option>
                                    </select>
                                    <input type="text" class="form-input w-1/3" name="filter_value[]" placeholder="Value">
                                    <button type="button" onclick="addFilter()" class="btn btn-outline btn-sm">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sorting -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Sorting</label>
                            <div class="flex items-center space-x-3">
                                <select name="order_by" class="form-select w-1/2">
                                    <option value="">Select Field</option>
                                </select>
                                <select name="order_direction" class="form-select w-1/4">
                                    <option value="ASC">Ascending</option>
                                    <option value="DESC">Descending</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Export Options -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Export Format</label>
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" name="format" value="excel" class="form-radio" checked>
                                    <span class="ml-2 text-sm text-gray-700">Excel</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="format" value="csv" class="form-radio">
                                    <span class="ml-2 text-sm text-gray-700">CSV</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <button type="button" onclick="previewReport()" class="btn btn-outline">
                                <i class="fas fa-eye mr-2"></i>
                                Preview
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-download mr-2"></i>
                                Generate Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Preview Panel -->
        <div class="space-y-6">
            <!-- Query Preview -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Query Preview</h3>
                </div>
                <div class="p-6">
                    <div id="query-preview" class="bg-gray-50 p-4 rounded-md">
                        <code class="text-sm text-gray-600">
                            Select tables and fields to see query preview
                        </code>
                    </div>
                </div>
            </div>

            <!-- Saved Reports -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Templates</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-2">
                        <button onclick="loadTemplate('employee_master')" class="w-full text-left px-3 py-2 text-sm bg-gray-50 hover:bg-gray-100 rounded-md">
                            Employee Master Report
                        </button>
                        <button onclick="loadTemplate('salary_summary')" class="w-full text-left px-3 py-2 text-sm bg-gray-50 hover:bg-gray-100 rounded-md">
                            Salary Summary Report
                        </button>
                        <button onclick="loadTemplate('attendance_summary')" class="w-full text-left px-3 py-2 text-sm bg-gray-50 hover:bg-gray-100 rounded-md">
                            Attendance Summary
                        </button>
                        <button onclick="loadTemplate('department_wise')" class="w-full text-left px-3 py-2 text-sm bg-gray-50 hover:bg-gray-100 rounded-md">
                            Department-wise Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Field mappings for each table
const tableFields = {
    employees: [
        { value: 'e.emp_code', label: 'Employee Code' },
        { value: 'e.first_name', label: 'First Name' },
        { value: 'e.last_name', label: 'Last Name' },
        { value: 'e.email', label: 'Email' },
        { value: 'e.phone', label: 'Phone' },
        { value: 'e.join_date', label: 'Join Date' },
        { value: 'e.status', label: 'Status' }
    ],
    payroll_transactions: [
        { value: 'pt.amount', label: 'Transaction Amount' },
        { value: 'pt.calculated_amount', label: 'Calculated Amount' },
        { value: 'pt.created_at', label: 'Transaction Date' }
    ],
    salary_components: [
        { value: 'sc.name', label: 'Component Name' },
        { value: 'sc.code', label: 'Component Code' },
        { value: 'sc.type', label: 'Component Type' }
    ],
    departments: [
        { value: 'd.name', label: 'Department Name' },
        { value: 'd.code', label: 'Department Code' }
    ],
    designations: [
        { value: 'des.name', label: 'Designation Name' },
        { value: 'des.code', label: 'Designation Code' },
        { value: 'des.grade', label: 'Grade' }
    ],
    attendance: [
        { value: 'a.attendance_date', label: 'Attendance Date' },
        { value: 'a.status', label: 'Attendance Status' },
        { value: 'a.total_hours', label: 'Total Hours' }
    ]
};

// Update field selection based on selected tables
document.addEventListener('change', function(e) {
    if (e.target.matches('.table-checkbox')) {
        updateFieldSelection();
        updateQueryPreview();
    }
});

function updateFieldSelection() {
    const selectedTables = Array.from(document.querySelectorAll('.table-checkbox:checked')).map(cb => cb.value);
    const fieldSelection = document.getElementById('field-selection');
    
    fieldSelection.innerHTML = '';
    
    selectedTables.forEach(table => {
        if (tableFields[table]) {
            const tableDiv = document.createElement('div');
            tableDiv.innerHTML = `
                <h4 class="text-sm font-medium text-gray-900 mb-2">${table.charAt(0).toUpperCase() + table.slice(1)}</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    ${tableFields[table].map(field => `
                        <label class="flex items-center">
                            <input type="checkbox" name="fields[]" value="${field.value}" class="form-checkbox field-checkbox">
                            <span class="ml-2 text-sm text-gray-700">${field.label}</span>
                        </label>
                    `).join('')}
                </div>
            `;
            fieldSelection.appendChild(tableDiv);
        }
    });
    
    // Update filter and sort dropdowns
    updateFilterOptions();
}

function updateFilterOptions() {
    const selectedFields = Array.from(document.querySelectorAll('.field-checkbox:checked'));
    const filterSelects = document.querySelectorAll('select[name="filter_field[]"]');
    const orderBySelect = document.querySelector('select[name="order_by"]');
    
    const options = selectedFields.map(field => {
        const label = field.parentNode.textContent.trim();
        return `<option value="${field.value}">${label}</option>`;
    }).join('');
    
    filterSelects.forEach(select => {
        select.innerHTML = '<option value="">Select Field</option>' + options;
    });
    
    if (orderBySelect) {
        orderBySelect.innerHTML = '<option value="">Select Field</option>' + options;
    }
}

function addFilter() {
    const filterSection = document.getElementById('filter-section');
    const newFilter = document.createElement('div');
    newFilter.className = 'filter-row flex items-center space-x-3';
    newFilter.innerHTML = `
        <select class="form-select w-1/3" name="filter_field[]">
            <option value="">Select Field</option>
        </select>
        <select class="form-select w-1/4" name="filter_operator[]">
            <option value="=">=</option>
            <option value="!=">!=</option>
            <option value=">">></option>
            <option value="<"><</option>
            <option value="LIKE">Contains</option>
        </select>
        <input type="text" class="form-input w-1/3" name="filter_value[]" placeholder="Value">
        <button type="button" onclick="removeFilter(this)" class="btn btn-outline btn-sm">
            <i class="fas fa-minus"></i>
        </button>
    `;
    filterSection.appendChild(newFilter);
    updateFilterOptions();
}

function removeFilter(button) {
    button.closest('.filter-row').remove();
}

function updateQueryPreview() {
    const selectedTables = Array.from(document.querySelectorAll('.table-checkbox:checked')).map(cb => cb.value);
    const selectedFields = Array.from(document.querySelectorAll('.field-checkbox:checked')).map(cb => cb.value);
    
    let query = 'SELECT ';
    
    if (selectedFields.length > 0) {
        query += selectedFields.join(', ');
    } else {
        query += '*';
    }
    
    query += '\nFROM ';
    
    if (selectedTables.length > 0) {
        query += selectedTables.join(', ');
    } else {
        query += 'table_name';
    }
    
    // Add basic joins if multiple tables selected
    if (selectedTables.length > 1) {
        query += '\n-- Joins will be added automatically based on relationships';
    }
    
    document.getElementById('query-preview').innerHTML = `<code class="text-sm text-gray-800">${query}</code>`;
}

function previewReport() {
    const formData = new FormData(document.getElementById('report-builder-form'));
    const selectedTables = formData.getAll('tables[]');
    const selectedFields = formData.getAll('fields[]');
    
    if (selectedTables.length === 0) {
        showMessage('Please select at least one table', 'error');
        return;
    }
    
    if (selectedFields.length === 0) {
        showMessage('Please select at least one field', 'error');
        return;
    }
    
    showMessage('Preview functionality coming soon', 'info');
}

function loadTemplate(templateName) {
    // Clear current selection
    document.querySelectorAll('.table-checkbox').forEach(cb => cb.checked = false);
    document.querySelectorAll('.field-checkbox').forEach(cb => cb.checked = false);
    
    switch (templateName) {
        case 'employee_master':
            document.querySelector('input[value="employees"]').checked = true;
            document.querySelector('input[value="departments"]').checked = true;
            document.querySelector('input[value="designations"]').checked = true;
            break;
        case 'salary_summary':
            document.querySelector('input[value="employees"]').checked = true;
            document.querySelector('input[value="payroll_transactions"]').checked = true;
            document.querySelector('input[value="salary_components"]').checked = true;
            break;
        case 'attendance_summary':
            document.querySelector('input[value="employees"]').checked = true;
            document.querySelector('input[value="attendance"]').checked = true;
            break;
        case 'department_wise':
            document.querySelector('input[value="employees"]').checked = true;
            document.querySelector('input[value="departments"]').checked = true;
            break;
    }
    
    updateFieldSelection();
    updateQueryPreview();
    showMessage(`${templateName.replace('_', ' ')} template loaded`, 'success');
}

// Form submission
document.getElementById('report-builder-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const selectedTables = formData.getAll('tables[]');
    const selectedFields = formData.getAll('fields[]');
    
    if (selectedTables.length === 0) {
        showMessage('Please select at least one table', 'error');
        return;
    }
    
    if (selectedFields.length === 0) {
        showMessage('Please select at least one field', 'error');
        return;
    }
    
    showLoading();
    
    // Convert FormData to JSON
    const data = {};
    for (let [key, value] of formData.entries()) {
        if (data[key]) {
            if (Array.isArray(data[key])) {
                data[key].push(value);
            } else {
                data[key] = [data[key], value];
            }
        } else {
            data[key] = value;
        }
    }
    
    fetch('/reports/custom-builder', {
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
                const filename = `custom_report_${new Date().toISOString().split('T')[0]}.${data.format}`;
                
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