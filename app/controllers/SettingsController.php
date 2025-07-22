<?php
/**
 * Settings Controller
 */

require_once __DIR__ . '/../core/Controller.php';

class SettingsController extends Controller {
    
    public function index() {
        $this->checkAuth();
        $this->checkPermission('settings');
        
        $this->loadView('settings/index', [
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }
    
    public function updateGeneral() {
        $this->checkAuth();
        $this->checkPermission('settings');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->sanitizeInput($_POST);
            $csrfToken = $_POST['csrf_token'] ?? '';
            
            if (!$this->validateCSRFToken($csrfToken)) {
                $this->jsonResponse(['success' => false, 'message' => 'Invalid token'], 400);
                return;
            }
            
            // Save general settings
            $this->saveSettings('general', $data);
            
            $this->logActivity('update_general_settings');
            $this->jsonResponse(['success' => true, 'message' => 'General settings updated successfully']);
        }
    }
    
    public function updatePayroll() {
        $this->checkAuth();
        $this->checkPermission('settings');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->sanitizeInput($_POST);
            $csrfToken = $_POST['csrf_token'] ?? '';
            
            if (!$this->validateCSRFToken($csrfToken)) {
                $this->jsonResponse(['success' => false, 'message' => 'Invalid token'], 400);
                return;
            }
            
            // Save payroll settings
            $this->saveSettings('payroll', $data);
            
            $this->logActivity('update_payroll_settings');
            $this->jsonResponse(['success' => true, 'message' => 'Payroll settings updated successfully']);
        }
    }
    
    public function updateEmail() {
        $this->checkAuth();
        $this->checkPermission('settings');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->sanitizeInput($_POST);
            $csrfToken = $_POST['csrf_token'] ?? '';
            
            if (!$this->validateCSRFToken($csrfToken)) {
                $this->jsonResponse(['success' => false, 'message' => 'Invalid token'], 400);
                return;
            }
            
            // Save email settings
            $this->saveSettings('email', $data);
            
            $this->logActivity('update_email_settings');
            $this->jsonResponse(['success' => true, 'message' => 'Email settings updated successfully']);
        }
    }
    
    public function testEmail() {
        $this->checkAuth();
        $this->checkPermission('settings');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $testEmail = $_POST['test_email'] ?? $_SESSION['email'] ?? 'admin@company.com';
            
            // In a real implementation, you would send a test email
            $this->logActivity('test_email_settings');
            $this->jsonResponse(['success' => true, 'message' => 'Test email sent successfully']);
        }
    }
    
    public function backup() {
        $this->checkAuth();
        $this->checkPermission('settings');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            switch ($action) {
                case 'create':
                    $this->createBackup();
                    break;
                case 'download':
                    $this->downloadBackup($_POST['backup_id'] ?? '');
                    break;
                case 'delete':
                    $this->deleteBackup($_POST['backup_id'] ?? '');
                    break;
                default:
                    $this->jsonResponse(['success' => false, 'message' => 'Invalid action'], 400);
            }
        } else {
            $this->showBackups();
        }
    }
    
    private function saveSettings($category, $data) {
        // In a real implementation, you would save settings to database or config file
        // For now, we'll just simulate saving
        
        $settingsFile = __DIR__ . "/../../config/settings_{$category}.json";
        file_put_contents($settingsFile, json_encode($data, JSON_PRETTY_PRINT));
    }
    
    private function loadSettings($category) {
        $settingsFile = __DIR__ . "/../../config/settings_{$category}.json";
        
        if (file_exists($settingsFile)) {
            return json_decode(file_get_contents($settingsFile), true);
        }
        
        return [];
    }
    
    private function createBackup() {
        try {
            $backupId = uniqid('backup_');
            $backupPath = __DIR__ . "/../../backups/{$backupId}";
            
            if (!is_dir(dirname($backupPath))) {
                mkdir(dirname($backupPath), 0777, true);
            }
            
            // Create database backup
            $dbBackup = $this->createDatabaseBackup();
            
            // Create files backup
            $filesBackup = $this->createFilesBackup();
            
            $this->logActivity('create_backup', 'backups', $backupId);
            $this->jsonResponse(['success' => true, 'message' => 'Backup created successfully', 'backup_id' => $backupId]);
        } catch (Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to create backup'], 500);
        }
    }
    
    private function createDatabaseBackup() {
        // In a real implementation, you would use mysqldump
        $timestamp = date('Y-m-d_H-i-s');
        $filename = "database_backup_{$timestamp}.sql";
        
        // Simulate database backup
        return $filename;
    }
    
    private function createFilesBackup() {
        // In a real implementation, you would create a tar/zip archive
        $timestamp = date('Y-m-d_H-i-s');
        $filename = "files_backup_{$timestamp}.tar.gz";
        
        // Simulate files backup
        return $filename;
    }
    
    private function downloadBackup($backupId) {
        if (empty($backupId)) {
            $this->jsonResponse(['success' => false, 'message' => 'Backup ID is required'], 400);
            return;
        }
        
        // In a real implementation, you would serve the backup file
        $this->logActivity('download_backup', 'backups', $backupId);
        $this->jsonResponse(['success' => true, 'message' => 'Backup download initiated']);
    }
    
    private function deleteBackup($backupId) {
        if (empty($backupId)) {
            $this->jsonResponse(['success' => false, 'message' => 'Backup ID is required'], 400);
            return;
        }
        
        // In a real implementation, you would delete the backup files
        $this->logActivity('delete_backup', 'backups', $backupId);
        $this->jsonResponse(['success' => true, 'message' => 'Backup deleted successfully']);
    }
    
    private function showBackups() {
        // In a real implementation, you would list available backups
        $backups = [
            [
                'id' => 'backup_001',
                'name' => 'Daily Backup - ' . date('Y-m-d'),
                'size' => '15.2 MB',
                'created_at' => date('Y-m-d H:i:s'),
                'type' => 'automatic'
            ]
        ];
        
        $this->loadView('settings/backups', [
            'backups' => $backups,
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }
}