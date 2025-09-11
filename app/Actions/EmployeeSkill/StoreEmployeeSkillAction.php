<?php

namespace App\Actions\EmployeeSkill;

use App\Models\EmployeeSkill;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class StoreEmployeeSkillAction
{
    public function execute(array $data, ?UploadedFile $document = null): array
    {
        try {
            if ($document) {
                $data['document'] = Storage::disk('public')->putFile('employee_skill_documents', $document);
            }
            $employeeSkill = EmployeeSkill::create($data);
            return [
                'success' => true,
                'message' => 'Employee skill created successfully.',
                'employee_skill' => $employeeSkill,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
