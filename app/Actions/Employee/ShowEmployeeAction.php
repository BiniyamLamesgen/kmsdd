<?php

namespace App\Actions\Employee;

use App\Models\Employee;

class ShowEmployeeAction
{
    public function execute($id): array
    {
        try {
            $employee = Employee::with(['department', 'experiences', 'projects', 'skills', 'achievements', 'certifications', 'education', 'knowledgeSharing', 'endorsementsGiven', 'endorsementsReceived'])->findOrFail($id);
            return [
                'success' => true,
                'employee' => $employee,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
