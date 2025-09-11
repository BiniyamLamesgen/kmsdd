<?php

namespace App\Actions\Department;

use App\Models\Department;
use Illuminate\Support\Facades\DB;

class RestoreDepartmentAction
{
    /**
     * Restore a single department from trash.
     */
    public function execute(int $id): array
    {
        try {
            $department = Department::onlyTrashed()->findOrFail($id);

            if ($department->restore()) {
                return [
                    'success' => true,
                    'message' => "Department '{$department->name}' restored successfully.",
                    'department' => $department->fresh(),
                ];
            }

            return [
                'success' => false,
                'message' => "Failed to restore department '{$department->name}'.",
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error restoring department: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Restore multiple departments from trash.
     */
    public function bulkRestore(array $ids): array
    {
        $restored = 0;
        $failed = 0;
        $errors = [];
        $restoredNames = [];

        DB::beginTransaction();

        try {
            foreach ($ids as $id) {
                $department = Department::onlyTrashed()->find($id);

                if ($department) {
                    if ($department->restore()) {
                        $restored++;
                        $restoredNames[] = $department->name;
                    } else {
                        $failed++;
                        $errors[] = "Failed to restore '{$department->name}'.";
                    }
                } else {
                    $failed++;
                    $errors[] = "Department with ID {$id} not found in trash.";
                }
            }

            DB::commit();

            return [
                'success' => $restored > 0,
                'restored' => $restored,
                'failed' => $failed,
                'restored_names' => $restoredNames,
                'errors' => $errors,
                'message' => "Restored {$restored} department(s), {$failed} failed.",
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Error during bulk restore: ' . $e->getMessage(),
            ];
        }
    }
}
