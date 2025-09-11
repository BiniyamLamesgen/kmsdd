<?php
namespace App\Actions\Department;
use Illuminate\Support\Facades\Log;

use App\Models\Department;

class DestroyDepartmentAction
{
    public function execute(Department $department): array
    {
        try {
            $name = $department->name;
            $department->delete();

            return [
                'success' => true,
                'message' => "Department '{$name}' deleted successfully."
            ];
        } catch (\Exception $e) {
            Log::error("Error deleting department: {$e->getMessage()}");

            return [
                'success' => false,
                'message' => 'Failed to delete the department.'
            ];
        }
    }
}
