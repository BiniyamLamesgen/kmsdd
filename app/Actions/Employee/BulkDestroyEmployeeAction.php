<?php

namespace App\Actions\Employee;

use App\Models\Employee;

class BulkDestroyEmployeeAction
{
    public function execute(array $ids)
    {
        $count = Employee::whereIn('id', $ids)->delete();
        return [
            'success' => $count > 0,
            'message' => $count > 0 ? 'Employees deleted successfully.' : 'No employees deleted.',
        ];
    }
}
