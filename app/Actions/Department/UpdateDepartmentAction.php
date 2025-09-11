<?php

namespace App\Actions\Department;
use Illuminate\Support\Facades\Log;
use App\Models\Department;
class UpdateDepartmentAction
{
   public function execute(string $id, array $data): array
{
    try {
        Log::info('Updating department', [
            'id' => $id,
            'data' => $data,
        ]);

        $department = Department::findOrFail($id);
        $department->update($data);

        Log::info('Updated department', ['department' => $department]);

        return [
            'success' => true,
            'department' => $department,
            'message' => 'Department updated successfully.',
        ];
    } catch (\Exception $e) {
        Log::error('Update failed', ['error' => $e->getMessage()]);
        return [
            'success' => false,
            'message' => $e->getMessage(),
        ];
    }
}
}