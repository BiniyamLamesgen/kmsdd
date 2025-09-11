<?php

namespace App\Actions\Employee;

use App\Models\Employee;

class DestroyEmployeeAction
{
    public function execute(string $id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return ['success' => false, 'message' => 'Employee not found.'];
        }
        $employee->delete();
        return ['success' => true, 'message' => 'Employee deleted successfully.'];
    }
}
