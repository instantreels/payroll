<?php
/**
 * Cost Center Controller
 */

require_once __DIR__ . '/../core/Controller.php';

class CostCenterController extends Controller {
    
    public function index() {
        $this->checkAuth();
        $this->checkPermission('employees');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleCostCenterAction();
        } else {
            $this->showCostCenters();
        }
    }
    
    private function handleCostCenterAction() {
        $input = json_decode(file_get_contents('php://input'), true);
        $action = $input['action'] ?? '';
        $csrfToken = $input['csrf_token'] ?? '';
        
        if (!$this->validateCSRFToken($csrfToken)) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid token'], 400);
            return;
        }
        
        switch ($action) {
            case 'create':
                $this->createCostCenter($input);
                break;
            case 'update':
                $this->updateCostCenter($input);
                break;
            case 'delete':
                $this->deleteCostCenter($input);
                break;
            default:
                $this->jsonResponse(['success' => false, 'message' => 'Invalid action'], 400);
        }
    }
    
    private function createCostCenter($data) {
        $rules = [
            'name' => ['required' => true, 'max_length' => 100],
            'code' => ['required' => true, 'max_length' => 20]
        ];
        
        $errors = $this->validateInput($data, $rules);
        
        if (!empty($errors)) {
            $this->jsonResponse(['success' => false, 'errors' => $errors], 400);
            return;
        }
        
        // Check if code already exists
        $existing = $this->db->fetch("SELECT id FROM cost_centers WHERE code = :code", ['code' => $data['code']]);
        if ($existing) {
            $this->jsonResponse(['success' => false, 'message' => 'Cost center code already exists'], 400);
            return;
        }
        
        try {
            $insertData = [
                'name' => $data['name'],
                'code' => $data['code'],
                'description' => $data['description'] ?? null,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $id = $this->db->insert('cost_centers', $insertData);
            
            $this->logActivity('create_cost_center', 'cost_centers', $id);
            $this->jsonResponse(['success' => true, 'message' => 'Cost center created successfully']);
        } catch (Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to create cost center'], 500);
        }
    }
    
    private function updateCostCenter($data) {
        $id = $data['id'] ?? '';
        
        if (empty($id)) {
            $this->jsonResponse(['success' => false, 'message' => 'Cost center ID is required'], 400);
            return;
        }
        
        try {
            $updateData = [
                'name' => $data['name'],
                'code' => $data['code'],
                'description' => $data['description'] ?? null
            ];
            
            $this->db->update('cost_centers', $updateData, 'id = :id', ['id' => $id]);
            
            $this->logActivity('update_cost_center', 'cost_centers', $id);
            $this->jsonResponse(['success' => true, 'message' => 'Cost center updated successfully']);
        } catch (Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to update cost center'], 500);
        }
    }
    
    private function deleteCostCenter($data) {
        $id = $data['id'] ?? '';
        
        if (empty($id)) {
            $this->jsonResponse(['success' => false, 'message' => 'Cost center ID is required'], 400);
            return;
        }
        
        // Check if cost center has employees
        $employeeCount = $this->db->fetch(
            "SELECT COUNT(*) as count FROM employees WHERE cost_center_id = :id AND status != 'deleted'",
            ['id' => $id]
        );
        
        if ($employeeCount['count'] > 0) {
            $this->jsonResponse(['success' => false, 'message' => 'Cannot delete cost center with active employees'], 400);
            return;
        }
        
        try {
            $this->db->update('cost_centers', ['status' => 'inactive'], 'id = :id', ['id' => $id]);
            
            $this->logActivity('delete_cost_center', 'cost_centers', $id);
            $this->jsonResponse(['success' => true, 'message' => 'Cost center deleted successfully']);
        } catch (Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to delete cost center'], 500);
        }
    }
    
    private function showCostCenters() {
        $costCenters = $this->db->fetchAll(
            "SELECT cc.*,
                    (SELECT COUNT(*) FROM employees WHERE cost_center_id = cc.id AND status = 'active') as employee_count
             FROM cost_centers cc
             WHERE cc.status = 'active'
             ORDER BY cc.name ASC"
        );
        
        $this->loadView('masters/cost-centers', [
            'cost_centers' => $costCenters,
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }
}