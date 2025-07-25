<?php
/**
 * System Information Controller
 */

require_once __DIR__ . '/../core/Controller.php';

class SystemInfoController extends Controller {
    
    public function index() {
        $this->checkAuth();
        $this->checkPermission('settings');
        
        $systemInfo = $this->getSystemInformation();
        
        $this->loadView('system-info/index', [
            'system_info' => $systemInfo
        ]);
    }
    
    public function phpInfo() {
        $this->checkAuth();
        $this->checkPermission('settings');
        
        // Output PHP info in a clean format
        ob_start();
        phpinfo();
        $phpInfo = ob_get_clean();
        
        // Clean up the output
        $phpInfo = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $phpInfo);
        
        echo $phpInfo;
        exit;
    }
    
    public function databaseInfo() {
        $this->checkAuth();
        $this->checkPermission('settings');
        
        try {
            // Get MySQL version
            $version = $this->db->fetch("SELECT VERSION() as version");
            
            // Get table count
            $tables = $this->db->fetch("SELECT COUNT(*) as count FROM information_schema.tables WHERE table_schema = DATABASE()");
            
            // Get database size
            $size = $this->db->fetch("SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb FROM information_schema.tables WHERE table_schema = DATABASE()");
            
            // Get record counts for main tables
            $recordCounts = [];
            $mainTables = ['employees', 'payroll_transactions', 'attendance', 'audit_logs'];
            
            foreach ($mainTables as $table) {
                try {
                    $count = $this->db->fetch("SELECT COUNT(*) as count FROM {$table}");
                    $recordCounts[$table] = $count['count'];
                } catch (Exception $e) {
                    $recordCounts[$table] = 0;
                }
            }
            
            $this->jsonResponse([
                'success' => true,
                'data' => [
                    'version' => $version['version'],
                    'table_count' => $tables['count'],
                    'size_mb' => $size['size_mb'],
                    'record_counts' => $recordCounts
                ]
            ]);
        } catch (Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to get database info'], 500);
        }
    }
    
    public function performanceMetrics() {
        $this->checkAuth();
        $this->checkPermission('settings');
        
        $metrics = [
            'memory_usage' => [
                'current' => memory_get_usage(true),
                'peak' => memory_get_peak_usage(true),
                'limit' => $this->parseMemoryLimit(ini_get('memory_limit'))
            ],
            'execution_time' => microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],
            'included_files' => count(get_included_files()),
            'active_sessions' => $this->getActiveSessionCount()
        ];
        
        $this->jsonResponse(['success' => true, 'metrics' => $metrics]);
    }
    
    public function errorLogs() {
        $this->checkAuth();
        $this->checkPermission('settings');
        
        $logFile = ini_get('error_log');
        $logs = [];
        
        if ($logFile && file_exists($logFile)) {
            $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $logs = array_slice(array_reverse($lines), 0, 50); // Last 50 errors
        }
        
        $this->jsonResponse(['success' => true, 'logs' => $logs]);
    }
    
    private function getSystemInformation() {
        return [
            'application' => [
                'name' => APP_NAME,
                'version' => APP_VERSION,
                'environment' => getenv('ENV') ?: 'production',
                'base_url' => BASE_URL,
                'timezone' => date_default_timezone_get()
            ],
            'server' => [
                'software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
                'php_version' => PHP_VERSION,
                'os' => PHP_OS,
                'memory_limit' => ini_get('memory_limit'),
                'max_execution_time' => ini_get('max_execution_time'),
                'upload_max_filesize' => ini_get('upload_max_filesize'),
                'post_max_size' => ini_get('post_max_size')
            ],
            'extensions' => [
                'pdo' => extension_loaded('pdo'),
                'pdo_mysql' => extension_loaded('pdo_mysql'),
                'gd' => extension_loaded('gd'),
                'mbstring' => extension_loaded('mbstring'),
                'openssl' => extension_loaded('openssl'),
                'curl' => extension_loaded('curl'),
                'json' => extension_loaded('json'),
                'session' => extension_loaded('session')
            ]
        ];
    }
    
    private function parseMemoryLimit($limit) {
        $limit = trim($limit);
        $last = strtolower($limit[strlen($limit)-1]);
        $limit = (int) $limit;
        
        switch($last) {
            case 'g':
                $limit *= 1024;
            case 'm':
                $limit *= 1024;
            case 'k':
                $limit *= 1024;
        }
        
        return $limit;
    }
    
    private function getActiveSessionCount() {
        // This is a simplified count - in production you might want to track this differently
        $sessionPath = session_save_path();
        if ($sessionPath && is_dir($sessionPath)) {
            $files = glob($sessionPath . '/sess_*');
            return count($files);
        }
        return 0;
    }
}