<?php
/**
 * Audit Controller
 */

require_once __DIR__ . '/../core/Controller.php';

class AuditController extends Controller {
    
    public function index() {
        $this->checkAuth();
        $this->checkPermission('settings');
        
        $auditModel = $this->loadModel('AuditLog');
        $page = max(1, intval($_GET['page'] ?? 1));
        
        // Filters
        $userId = $_GET['user_id'] ?? '';
        $action = $_GET['action'] ?? '';
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        
        // Build conditions
        $conditions = [];
        $params = [];
        
        if (!empty($userId)) {
            $conditions[] = 'al.user_id = :user_id';
            $params['user_id'] = $userId;
        }
        
        if (!empty($action)) {
            $conditions[] = 'al.action = :action';
            $params['action'] = $action;
        }
        
        if (!empty($startDate) && !empty($endDate)) {
            $conditions[] = 'DATE(al.created_at) BETWEEN :start_date AND :end_date';
            $params['start_date'] = $startDate;
            $params['end_date'] = $endDate;
        }
        
        $whereClause = !empty($conditions) ? implode(' AND ', $conditions) : '';
        
        // Get audit logs with pagination
        $sql = "SELECT al.*, u.full_name, u.username
                FROM audit_logs al
                LEFT JOIN users u ON al.user_id = u.id";
        
        if ($whereClause) {
            $sql .= " WHERE {$whereClause}";
        }
        
        $sql .= " ORDER BY al.created_at DESC";
        
        // Handle export
        if (isset($_GET['export'])) {
            $this->exportAuditLogs($sql, $params);
            return;
        }
        
        // Pagination
        $totalSql = "SELECT COUNT(*) as count FROM audit_logs al";
        if ($whereClause) {
            $totalSql .= " WHERE {$whereClause}";
        }
        
        $totalResult = $this->db->fetch($totalSql, $params);
        $total = $totalResult['count'];
        $perPage = 50;
        $totalPages = ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;
        
        $sql .= " LIMIT {$perPage} OFFSET {$offset}";
        
        $auditLogs = $this->db->fetchAll($sql, $params);
        
        // Get users for filter
        $users = $this->db->fetchAll("SELECT id, full_name FROM users ORDER BY full_name ASC");
        
        $this->loadView('audit/index', [
            'audit_logs' => $auditLogs,
            'users' => $users,
            'selected_user' => $userId,
            'selected_action' => $action,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'has_previous' => $page > 1,
                'has_next' => $page < $totalPages,
                'total' => $total
            ]
        ]);
    }
    
    public function clearOld() {
        $this->checkAuth();
        $this->checkPermission('settings');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $csrfToken = $input['csrf_token'] ?? '';
            
            if (!$this->validateCSRFToken($csrfToken)) {
                $this->jsonResponse(['success' => false, 'message' => 'Invalid token'], 400);
                return;
            }
            
            $auditModel = $this->loadModel('AuditLog');
            $result = $auditModel->cleanOldLogs(365); // Keep logs for 1 year
            
            if ($result) {
                $this->logActivity('clear_old_audit_logs', 'audit_logs', null);
                $this->jsonResponse(['success' => true, 'message' => 'Old audit logs cleared successfully']);
            } else {
                $this->jsonResponse(['success' => false, 'message' => 'Failed to clear old logs'], 500);
            }
        }
    }
    
    public function details($id) {
        $this->checkAuth();
        $this->checkPermission('settings');
        
        $auditModel = $this->loadModel('AuditLog');
        $log = $auditModel->findById($id);
        
        if (!$log) {
            $this->jsonResponse(['success' => false, 'message' => 'Log not found'], 404);
            return;
        }
        
        $this->jsonResponse(['success' => true, 'log' => $log]);
    }
    
    private function exportAuditLogs($sql, $params) {
        $auditLogs = $this->db->fetchAll($sql, $params);
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="audit_logs_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Headers
        fputcsv($output, [
            'Timestamp', 'User', 'Action', 'Table', 'Record ID', 
            'IP Address', 'User Agent', 'Old Values', 'New Values'
        ]);
        
        // Data
        foreach ($auditLogs as $log) {
            fputcsv($output, [
                $log['created_at'],
                $log['full_name'] ?? 'System',
                $log['action'],
                $log['table_name'] ?? '',
                $log['record_id'] ?? '',
                $log['ip_address'] ?? '',
                $log['user_agent'] ?? '',
                $log['old_values'] ?? '',
                $log['new_values'] ?? ''
            ]);
        }
        
        fclose($output);
        exit;
    }
}