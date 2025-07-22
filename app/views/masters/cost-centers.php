<?php 
$title = 'Cost Centers - Master Data';
include __DIR__ . '/../layout/header.php'; 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h1 class="text-3xl font-bold text-gray-900">Cost Center Management</h1>
            <p class="mt-1 text-sm text-gray-500">
                Manage cost centers for financial tracking and reporting
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <button onclick="openCreateModal()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700">
                <i class="fas fa-plus mr-2"></i>
                Add Cost Center
            </button>
        </div>
    </div>

    <!-- Cost Centers List -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Cost Center
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Code
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Description
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Employees
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (!empty($cost_centers)): ?>
                        <?php foreach ($cost_centers as $costCenter): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?php echo htmlspecialchars($costCenter['name']); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded bg-gray-100 text-gray-800">
                                        <?php echo htmlspecialchars($costCenter['code']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <?php echo htmlspecialchars($costCenter['description'] ?? 'No description'); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <?php echo $costCenter['employee_count']; ?> employees
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <button onclick="editCostCenter(<?php echo $costCenter['id']; ?>)" 
                                                class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="deleteCostCenter(<?php echo $costCenter['id']; ?>)" 
                                                class="text-red-600 hover:text-red-900" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <i class="fas fa-building text-4xl mb-4"></i>
                                    <p class="text-lg font-medium">No cost centers found</p>
                                    <p class="text-sm">Create your first cost center to get started</p>
                                    <button onclick="openCreateModal()" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-primary-700 bg-primary-100 hover:bg-primary-200">
                                        <i class="fas fa-plus mr-2"></i>
                                        Add Cost Center
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create/Edit Cost Center Modal -->
<div id="cost-center-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 id="modal-title" class="text-lg font-medium text-gray-900 mb-4">Add Cost Center</h3>
            <form id="cost-center-form">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <input type="hidden" name="action" value="create">
                <input type="hidden" name="id" id="cost_center_id">
                
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Cost Center Name *</label>
                    <input type="text" name="name" id="name" required
                           class="form-input" placeholder="e.g., Head Office">
                </div>
                
                <div class="mb-4">
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Cost Center Code *</label>
                    <input type="text" name="code" id="code" required
                           class="form-input" placeholder="e.g., HO001" maxlength="20">
                </div>
                
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="3" class="form-textarea" 
                              placeholder="Optional description"></textarea>
                </div>
                
                <div class="flex items-center justify-end space-x-4">
                    <button type="button" onclick="closeCostCenterModal()" class="btn btn-outline">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <span id="submit-text">Add Cost Center</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('modal-title').textContent = 'Add Cost Center';
    document.getElementById('submit-text').textContent = 'Add Cost Center';
    document.getElementById('cost-center-form').reset();
    document.querySelector('input[name="action"]').value = 'create';
    document.getElementById('cost_center_id').value = '';
    document.getElementById('cost-center-modal').classList.remove('hidden');
}

function editCostCenter(costCenterId) {
    document.getElementById('modal-title').textContent = 'Edit Cost Center';
    document.getElementById('submit-text').textContent = 'Update Cost Center';
    document.querySelector('input[name="action"]').value = 'update';
    document.getElementById('cost_center_id').value = costCenterId;
    document.getElementById('cost-center-modal').classList.remove('hidden');
    
    // In a real implementation, you would fetch and populate the form data
}

function closeCostCenterModal() {
    document.getElementById('cost-center-modal').classList.add('hidden');
}

function deleteCostCenter(costCenterId) {
    if (confirm('Are you sure you want to delete this cost center? This action cannot be undone.')) {
        showLoading();
        
        fetch('/cost-centers', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                action: 'delete',
                id: costCenterId,
                csrf_token: '<?php echo $csrf_token; ?>'
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
            showMessage('An error occurred while deleting the cost center', 'error');
        });
    }
}

// Form submission
document.getElementById('cost-center-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    showLoading();
    
    fetch('/cost-centers', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showMessage(data.message, 'success');
            closeCostCenterModal();
            setTimeout(() => location.reload(), 1500);
        } else {
            if (data.errors) {
                Object.keys(data.errors).forEach(field => {
                    showMessage(data.errors[field], 'error');
                });
            } else {
                showMessage(data.message || 'Failed to save cost center', 'error');
            }
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showMessage('An error occurred while saving the cost center', 'error');
    });
});

// Auto-generate code from name
document.getElementById('name').addEventListener('input', function() {
    const name = this.value.trim();
    if (name && !document.getElementById('code').value) {
        const words = name.split(' ');
        let code = '';
        words.forEach(word => {
            if (word.length > 0) {
                code += word.charAt(0).toUpperCase();
            }
        });
        code += '001'; // Add default number
        document.getElementById('code').value = code.substring(0, 20);
    }
});

// Convert code to uppercase
document.getElementById('code').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>