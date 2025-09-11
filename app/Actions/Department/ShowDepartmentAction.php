<?php

namespace App\Actions\Department;

use App\Models\Department;

class ShowDepartmentAction
{
    public function execute(string $id): array
    {
        try {
            $department = Department::withTrashed()
                ->findOrFail($id);

            return [
                'success' => true,
                'department' => $department,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Department not found.',
            ];
        }
    }

    public function show(string $id)
    {
        return Department::findOrFail($id);
    }
}
