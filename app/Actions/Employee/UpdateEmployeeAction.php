<?php

namespace App\Actions\Employee;

use App\Models\Employee;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class UpdateEmployeeAction
{
    public function execute(string $id, array $data, ?UploadedFile $profilePicture = null)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return ['success' => false, 'message' => 'Employee not found.'];
        }

        // Handle profile picture upload
        if ($profilePicture) {
            // Remove old image if exists
            if ($employee->profile_picture && Storage::disk('public')->exists($employee->profile_picture)) {
                Storage::disk('public')->delete($employee->profile_picture);
            }
            $data['profile_picture'] = Storage::disk('public')->putFile('employee_profiles', $profilePicture);
        }

        $employee->update($data);

        return ['success' => true, 'employee' => $employee, 'message' => 'Employee updated successfully.'];
    }
}
