<?php
/**
 * Tax Slab Model
 */

require_once __DIR__ . '/../core/Model.php';

class TaxSlab extends Model {
    protected $table = 'tax_slabs';
    
    public function getTaxSlabsByFinancialYear($financialYear) {
        return $this->findAll(
            'financial_year = :fy',
            ['fy' => $financialYear],
            'min_amount ASC'
        );
    }
    
    public function calculateTax($annualIncome, $financialYear) {
        $taxSlabs = $this->getTaxSlabsByFinancialYear($financialYear);
        $totalTax = 0;
        
        foreach ($taxSlabs as $slab) {
            if ($annualIncome > $slab['min_amount']) {
                $taxableAmount = min(
                    $annualIncome, 
                    $slab['max_amount'] ?? $annualIncome
                ) - $slab['min_amount'];
                
                $slabTax = ($taxableAmount * $slab['tax_rate']) / 100;
                
                // Add surcharge if applicable
                if ($slab['surcharge_rate'] > 0) {
                    $slabTax += ($slabTax * $slab['surcharge_rate']) / 100;
                }
                
                // Add cess if applicable
                if ($slab['cess_rate'] > 0) {
                    $slabTax += ($slabTax * $slab['cess_rate']) / 100;
                }
                
                $totalTax += $slabTax;
            }
        }
        
        return round($totalTax, 2);
    }
    
    public function createTaxSlab($data) {
        $rules = [
            'financial_year' => ['required' => true, 'max_length' => 9],
            'min_amount' => ['required' => true, 'type' => 'numeric'],
            'tax_rate' => ['required' => true, 'type' => 'numeric']
        ];
        
        $errors = $this->validateData($data, $rules);
        
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        // Check for overlapping slabs
        $existing = $this->checkOverlappingSlabs(
            $data['financial_year'],
            $data['min_amount'],
            $data['max_amount'] ?? null
        );
        
        if ($existing) {
            return ['success' => false, 'message' => 'Tax slab overlaps with existing slab'];
        }
        
        try {
            $data['created_at'] = date('Y-m-d H:i:s');
            $id = $this->create($data);
            return ['success' => true, 'id' => $id];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Failed to create tax slab'];
        }
    }
    
    private function checkOverlappingSlabs($financialYear, $minAmount, $maxAmount) {
        $conditions = 'financial_year = :fy AND (';
        $params = ['fy' => $financialYear];
        
        if ($maxAmount) {
            $conditions .= '(min_amount <= :max_amount AND (max_amount >= :min_amount OR max_amount IS NULL))';
            $params['min_amount'] = $minAmount;
            $params['max_amount'] = $maxAmount;
        } else {
            $conditions .= 'min_amount >= :min_amount';
            $params['min_amount'] = $minAmount;
        }
        
        $conditions .= ')';
        
        return $this->findAll($conditions, $params);
    }
    
    public function getFinancialYears() {
        $sql = "SELECT DISTINCT financial_year FROM {$this->table} ORDER BY financial_year DESC";
        return $this->db->fetchAll($sql);
    }
    
    public function getCurrentFinancialYear() {
        $currentMonth = date('n');
        $currentYear = date('Y');
        
        if ($currentMonth >= 4) {
            return $currentYear . '-' . ($currentYear + 1);
        } else {
            return ($currentYear - 1) . '-' . $currentYear;
        }
    }
    
    public function getTaxBreakdown($annualIncome, $financialYear) {
        $taxSlabs = $this->getTaxSlabsByFinancialYear($financialYear);
        $breakdown = [];
        $totalTax = 0;
        
        foreach ($taxSlabs as $slab) {
            if ($annualIncome > $slab['min_amount']) {
                $taxableAmount = min(
                    $annualIncome, 
                    $slab['max_amount'] ?? $annualIncome
                ) - $slab['min_amount'];
                
                if ($taxableAmount > 0) {
                    $slabTax = ($taxableAmount * $slab['tax_rate']) / 100;
                    $totalTax += $slabTax;
                    
                    $breakdown[] = [
                        'slab' => $slab,
                        'taxable_amount' => $taxableAmount,
                        'tax_amount' => $slabTax
                    ];
                }
            }
        }
        
        return [
            'breakdown' => $breakdown,
            'total_tax' => $totalTax
        ];
    }
}