<?php

namespace App\Actions\EmployeeSkill;

use App\Models\EmployeeSkill;

class UpdateEmployeeSkillAction
{
    public function execute(string $id, array $data)
    {
        $employeeSkill = EmployeeSkill::find($id);
        if (!$employeeSkill) {
            return ['success' => false, 'message' => 'Employee skill not found.'];
        }
        $employeeSkill->update($data);
        return ['success' => true, 'employeeSkill' => $employeeSkill, 'message' => 'Employee skill updated successfully.'];
    }
}
