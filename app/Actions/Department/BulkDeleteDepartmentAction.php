<?php
namespace App\Actions\Department;
use Illuminate\Support\Facades\Log;

class BulkDeleteDepartmentAction
{
    protected DestroyDepartmentAction $destroyAction;
    protected ShowDepartmentAction $showAction;

    public function __construct(
        DestroyDepartmentAction $destroyAction,
        ShowDepartmentAction $showAction
    ) {
        $this->destroyAction = $destroyAction;
        $this->showAction = $showAction;
    }

    public function execute(array $ids): array
    {
        $deleted = 0;
        $failed = 0;
        $deletedNames = [];
        $failedNames = [];

        foreach ($ids as $id) {
            try {
                $department = $this->showAction->show($id);
                $result = $this->destroyAction->execute($department);

                if ($result['success']) {
                    $deleted++;
                    $deletedNames[] = $department->name;
                } else {
                    $failed++;
                    $failedNames[] = $department->name;
                }
            } catch (\Exception $e) {
                $failed++;
                Log::error("Bulk delete error for department ID {$id}: " . $e->getMessage());
            }
        }

        return [
            'deleted' => $deleted,
            'failed' => $failed,
            'deleted_names' => $deletedNames,
            'failed_names' => $failedNames,
            'success' => $deleted > 0,
            'message' => $this->generateMessage($deleted, $failed),
        ];
    }

    private function generateMessage(int $deleted, int $failed): string
    {
        if ($deleted > 0 && $failed === 0) {
            return "Successfully deleted {$deleted} department(s).";
        } elseif ($deleted > 0 && $failed > 0) {
            return "Partially deleted: {$deleted} success, {$failed} failed.";
        } else {
            return "Failed to delete departments.";
        }
    }
}
