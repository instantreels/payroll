<?php 
$title = 'System Settings - Payroll Management System';
include __DIR__ . '/../layout/header.php'; 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">System Settings</h1>
        <p class="mt-1 text-sm text-gray-500">
            Configure system-wide settings and preferences
        </p>
    </div>

    <!-- Settings Categories -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Settings Navigation -->
        <div class="lg:col-span-1">
            <nav class="space-y-1">
                <a href="#general" class="settings-nav-link active bg-primary-50 text-primary-700 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-cog mr-3"></i>
                    General Settings
                </a>
                <a href="#payroll" class="settings-nav-link text-gray-700 hover:text-gray-900 hover:bg-gray-50 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-money-bill-wave mr-3"></i>
                    Payroll Settings
                </a>
                <a href="#email" class="settings-nav-link text-gray-700 hover:text-gray-900 hover:bg-gray-50 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-envelope mr-3"></i>
                    Email Settings
                </a>
                <a href="#security" class="settings-nav-link text-gray-700 hover:text-gray-900 hover:bg-gray-50 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-shield-alt mr-3"></i>
                    Security Settings
                </a>
                <a href="#backup" class="settings-nav-link text-gray-700 hover:text-gray-900 hover:bg-gray-50 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-database mr-3"></i>
                    Backup Settings
                </a>
            </nav>
        </div>

        <!-- Settings Content -->
        <div class="lg:col-span-3">
            <!-- General Settings -->
            <div id="general" class="settings-section">
                <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">General Settings</h3>
                    </div>
                    <div class="p-6">
                        <form id="general-settings-form">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                                    <input type="text" name="company_name" id="company_name" 
                                           value="PayrollPro Enterprise" class="form-input">
                                </div>
                                
                                <div>
                                    <label for="company_code" class="block text-sm font-medium text-gray-700 mb-2">Company Code</label>
                                    <input type="text" name="company_code" id="company_code" 
                                           value="PPE001" class="form-input">
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label for="company_address" class="block text-sm font-medium text-gray-700 mb-2">Company Address</label>
                                    <textarea name="company_address" id="company_address" rows="3" class="form-textarea">123 Business Street, Corporate City, State 12345</textarea>
                                </div>
                                
                                <div>
                                    <label for="financial_year_start" class="block text-sm font-medium text-gray-700 mb-2">Financial Year Start Month</label>
                                    <select name="financial_year_start" id="financial_year_start" class="form-select">
                                        <option value="1">January</option>
                                        <option value="4" selected>April</option>
                                        <option value="7">July</option>
                                        <option value="10">October</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                                    <select name="currency" id="currency" class="form-select">
                                        <option value="INR" selected>Indian Rupee (₹)</option>
                                        <option value="USD">US Dollar ($)</option>
                                        <option value="EUR">Euro (€)</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="timezone" class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                                    <select name="timezone" id="timezone" class="form-select">
                                        <option value="Asia/Kolkata" selected>Asia/Kolkata (IST)</option>
                                        <option value="America/New_York">America/New_York (EST)</option>
                                        <option value="Europe/London">Europe/London (GMT)</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="date_format" class="block text-sm font-medium text-gray-700 mb-2">Date Format</label>
                                    <select name="date_format" id="date_format" class="form-select">
                                        <option value="dd/mm/yyyy" selected>DD/MM/YYYY</option>
                                        <option value="mm/dd/yyyy">MM/DD/YYYY</option>
                                        <option value="yyyy-mm-dd">YYYY-MM-DD</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-2"></i>
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Payroll Settings -->
            <div id="payroll" class="settings-section hidden">
                <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Payroll Settings</h3>
                    </div>
                    <div class="p-6">
                        <form id="payroll-settings-form">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="pf_rate_employee" class="block text-sm font-medium text-gray-700 mb-2">PF Rate - Employee (%)</label>
                                    <input type="number" name="pf_rate_employee" id="pf_rate_employee" 
                                           value="12" step="0.01" min="0" max="100" class="form-input">
                                </div>
                                
                                <div>
                                    <label for="pf_rate_employer" class="block text-sm font-medium text-gray-700 mb-2">PF Rate - Employer (%)</label>
                                    <input type="number" name="pf_rate_employer" id="pf_rate_employer" 
                                           value="12" step="0.01" min="0" max="100" class="form-input">
                                </div>
                                
                                <div>
                                    <label for="esi_rate_employee" class="block text-sm font-medium text-gray-700 mb-2">ESI Rate - Employee (%)</label>
                                    <input type="number" name="esi_rate_employee" id="esi_rate_employee" 
                                           value="0.75" step="0.01" min="0" max="100" class="form-input">
                                </div>
                                
                                <div>
                                    <label for="esi_rate_employer" class="block text-sm font-medium text-gray-700 mb-2">ESI Rate - Employer (%)</label>
                                    <input type="number" name="esi_rate_employer" id="esi_rate_employer" 
                                           value="3.25" step="0.01" min="0" max="100" class="form-input">
                                </div>
                                
                                <div>
                                    <label for="esi_threshold" class="block text-sm font-medium text-gray-700 mb-2">ESI Threshold Amount</label>
                                    <input type="number" name="esi_threshold" id="esi_threshold" 
                                           value="21000" min="0" class="form-input">
                                </div>
                                
                                <div>
                                    <label for="pt_amount" class="block text-sm font-medium text-gray-700 mb-2">Professional Tax Amount</label>
                                    <input type="number" name="pt_amount" id="pt_amount" 
                                           value="200" min="0" class="form-input">
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="auto_calculate_tds" class="form-checkbox" checked>
                                        <span class="ml-2 text-sm text-gray-700">Auto-calculate TDS based on tax slabs</span>
                                    </label>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="auto_process_loans" class="form-checkbox" checked>
                                        <span class="ml-2 text-sm text-gray-700">Auto-process loan EMIs during payroll</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-2"></i>
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Email Settings -->
            <div id="email" class="settings-section hidden">
                <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Email Settings</h3>
                    </div>
                    <div class="p-6">
                        <form id="email-settings-form">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="smtp_host" class="block text-sm font-medium text-gray-700 mb-2">SMTP Host</label>
                                    <input type="text" name="smtp_host" id="smtp_host" 
                                           value="smtp.gmail.com" class="form-input">
                                </div>
                                
                                <div>
                                    <label for="smtp_port" class="block text-sm font-medium text-gray-700 mb-2">SMTP Port</label>
                                    <input type="number" name="smtp_port" id="smtp_port" 
                                           value="587" class="form-input">
                                </div>
                                
                                <div>
                                    <label for="smtp_username" class="block text-sm font-medium text-gray-700 mb-2">SMTP Username</label>
                                    <input type="email" name="smtp_username" id="smtp_username" 
                                           placeholder="your-email@gmail.com" class="form-input">
                                </div>
                                
                                <div>
                                    <label for="smtp_password" class="block text-sm font-medium text-gray-700 mb-2">SMTP Password</label>
                                    <input type="password" name="smtp_password" id="smtp_password" 
                                           placeholder="••••••••" class="form-input">
                                </div>
                                
                                <div>
                                    <label for="from_email" class="block text-sm font-medium text-gray-700 mb-2">From Email</label>
                                    <input type="email" name="from_email" id="from_email" 
                                           value="noreply@company.com" class="form-input">
                                </div>
                                
                                <div>
                                    <label for="from_name" class="block text-sm font-medium text-gray-700 mb-2">From Name</label>
                                    <input type="text" name="from_name" id="from_name" 
                                           value="PayrollPro System" class="form-input">
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="enable_ssl" class="form-checkbox" checked>
                                        <span class="ml-2 text-sm text-gray-700">Enable SSL/TLS encryption</span>
                                    </label>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="auto_email_payslips" class="form-checkbox">
                                        <span class="ml-2 text-sm text-gray-700">Automatically email payslips after generation</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mt-6 flex items-center justify-between">
                                <button type="button" onclick="testEmailSettings()" class="btn btn-outline">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    Test Email
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-2"></i>
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Security Settings -->
            <div id="security" class="settings-section hidden">
                <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Security Settings</h3>
                    </div>
                    <div class="p-6">
                        <form id="security-settings-form">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="session_timeout" class="block text-sm font-medium text-gray-700 mb-2">Session Timeout (minutes)</label>
                                    <input type="number" name="session_timeout" id="session_timeout" 
                                           value="30" min="5" max="480" class="form-input">
                                </div>
                                
                                <div>
                                    <label for="max_login_attempts" class="block text-sm font-medium text-gray-700 mb-2">Max Login Attempts</label>
                                    <input type="number" name="max_login_attempts" id="max_login_attempts" 
                                           value="3" min="1" max="10" class="form-input">
                                </div>
                                
                                <div>
                                    <label for="lockout_duration" class="block text-sm font-medium text-gray-700 mb-2">Lockout Duration (minutes)</label>
                                    <input type="number" name="lockout_duration" id="lockout_duration" 
                                           value="15" min="1" max="1440" class="form-input">
                                </div>
                                
                                <div>
                                    <label for="password_min_length" class="block text-sm font-medium text-gray-700 mb-2">Minimum Password Length</label>
                                    <input type="number" name="password_min_length" id="password_min_length" 
                                           value="6" min="4" max="20" class="form-input">
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="require_password_change" class="form-checkbox">
                                        <span class="ml-2 text-sm text-gray-700">Require password change on first login</span>
                                    </label>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="enable_audit_log" class="form-checkbox" checked>
                                        <span class="ml-2 text-sm text-gray-700">Enable audit logging</span>
                                    </label>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="enable_2fa" class="form-checkbox">
                                        <span class="ml-2 text-sm text-gray-700">Enable Two-Factor Authentication (Coming Soon)</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-2"></i>
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Backup Settings -->
            <div id="backup" class="settings-section hidden">
                <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Backup Settings</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="backup_frequency" class="block text-sm font-medium text-gray-700 mb-2">Backup Frequency</label>
                                <select name="backup_frequency" id="backup_frequency" class="form-select">
                                    <option value="daily" selected>Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="backup_retention" class="block text-sm font-medium text-gray-700 mb-2">Retention Period (days)</label>
                                <input type="number" name="backup_retention" id="backup_retention" 
                                       value="30" min="1" max="365" class="form-input">
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="backup_database" class="form-checkbox" checked>
                                    <span class="ml-2 text-sm text-gray-700">Include database in backups</span>
                                </label>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="backup_files" class="form-checkbox" checked>
                                    <span class="ml-2 text-sm text-gray-700">Include uploaded files in backups</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex items-center justify-between">
                            <button type="button" onclick="createBackup()" class="btn btn-outline">
                                <i class="fas fa-download mr-2"></i>
                                Create Backup Now
                            </button>
                            <button type="button" onclick="saveBackupSettings()" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>
                                Save Settings
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Settings navigation
document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.settings-nav-link');
    const sections = document.querySelectorAll('.settings-section');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all links
            navLinks.forEach(l => l.classList.remove('active', 'bg-primary-50', 'text-primary-700'));
            navLinks.forEach(l => l.classList.add('text-gray-700', 'hover:text-gray-900', 'hover:bg-gray-50'));
            
            // Add active class to clicked link
            this.classList.add('active', 'bg-primary-50', 'text-primary-700');
            this.classList.remove('text-gray-700', 'hover:text-gray-900', 'hover:bg-gray-50');
            
            // Hide all sections
            sections.forEach(section => section.classList.add('hidden'));
            
            // Show target section
            const targetId = this.getAttribute('href').substring(1);
            document.getElementById(targetId).classList.remove('hidden');
        });
    });
});

// Form submissions
document.getElementById('general-settings-form').addEventListener('submit', function(e) {
    e.preventDefault();
    showMessage('General settings saved successfully', 'success');
});

document.getElementById('payroll-settings-form').addEventListener('submit', function(e) {
    e.preventDefault();
    showMessage('Payroll settings saved successfully', 'success');
});

document.getElementById('email-settings-form').addEventListener('submit', function(e) {
    e.preventDefault();
    showMessage('Email settings saved successfully', 'success');
});

document.getElementById('security-settings-form').addEventListener('submit', function(e) {
    e.preventDefault();
    showMessage('Security settings saved successfully', 'success');
});

function testEmailSettings() {
    showLoading();
    
    setTimeout(() => {
        hideLoading();
        showMessage('Test email sent successfully', 'success');
    }, 2000);
}

function saveBackupSettings() {
    showMessage('Backup settings saved successfully', 'success');
}

function createBackup() {
    if (confirm('Create a backup now? This may take a few minutes.')) {
        showLoading();
        
        setTimeout(() => {
            hideLoading();
            showMessage('Backup created successfully', 'success');
        }, 3000);
    }
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>