<?php
/**
 * Cost Center Model
 */

require_once __DIR__ . '/../core/Model.php';

class CostCenter extends Model {
    protected $table = 'cost_centers';
    
    public function getActiveCostCenters() {
        return $this->findAll('status = :status', ['status' => 'active'], 'name ASC');
    }
    
    public function getCostCentersWithEmployeeCount() {
        $sql = "SELECT cc.*, 
                       (SELECT COUNT(*) FROM employees WHERE cost_center_id = cc.id AND status = 'active') as employee_count
                FROM {$this->table} cc
                WHERE cc.status = 'active'
                ORDER BY cc.name ASC";
        
        return $this->db->fetchAll($sql);
    }
    
    public function createCostCenter($data) {
        $rules = [
            'name' => ['required' => true, 'max_length' => 100],
            'code' => ['required' => true, 'max_length' => 20, 'unique' => true]
        ];
        
        $errors = $this->validateData($data, $rules);
        
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        try {
            $data['created_at'] = date('Y-m-d H:i:s');
            $id = $this->create($data);
            return ['success' => true, 'id' => $id];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Failed to create cost center'];
        }
    }
    
    public function updateCostCenter($id, $data) {
        try {
            $this->update($id, $data);
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Failed to update cost center'];
        }
    }
    
    public function canDelete($id) {
        $employeeCount = $this->db->fetch(
            "SELECT COUNT(*) as count FROM employees WHERE cost_center_id = :id AND status != 'deleted'",
            ['id' => $id]
        );
        
        return $employeeCount['count'] == 0;
    }
    
    public function getCostCenterStats() {
        $sql = "SELECT 
                    cc.name,
                    COUNT(e.id) as employee_count,
                    SUM(CASE WHEN ss.amount IS NOT NULL THEN ss.amount ELSE 0 END) as total_cost
                FROM {$this->table} cc
                LEFT JOIN employees e ON cc.id = e.cost_center_id AND e.status = 'active'
                LEFT JOIN salary_structures ss ON e.id = ss.employee_id AND ss.component_id = 1 AND ss.end_date IS NULL
                WHERE cc.status = 'active'
                GROUP BY cc.id, cc.name
                ORDER BY total_cost DESC";
        
        return $this->db->fetchAll($sql);
    }
}