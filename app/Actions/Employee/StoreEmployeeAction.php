<?php

namespace App\Actions\Employee;

use App\Models\Employee;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class StoreEmployeeAction
{
    public function execute(array $data, ?UploadedFile $profilePicture = null): array
    {
        try {
            // Debug logging
            Log::info('StoreEmployeeAction Debug:', [
                'data_keys' => array_keys($data),
                'profile_picture_param' => $profilePicture ? 'File received' : 'No file',
                'profile_picture_info' => $profilePicture ? [
                    'name' => $profilePicture->getClientOriginalName(),
                    'size' => $profilePicture->getSize(),
                    'mime' => $profilePicture->getMimeType(),
                ] : null,
            ]);

            // Hash the password
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            // Handle profile picture upload
            if ($profilePicture && $profilePicture->isValid()) {
                $path = Storage::disk('public')->putFile('employee_profiles', $profilePicture);
                $data['profile_picture'] = $path;

                Log::info('Profile picture uploaded:', [
                    'path' => $path,
                    'full_url' => Storage::disk('public')->url($path)
                ]);
            } else {
                Log::info('No valid profile picture to upload');
            }

            $employee = Employee::create($data);

            Log::info('Employee created successfully:', [
                'employee_id' => $employee->id,
                'profile_picture' => $employee->profile_picture
            ]);

            return [
                'success' => true,
                'message' => 'Employee created successfully.',
                'employee' => $employee,
            ];
        } catch (\Exception $e) {
            Log::error('StoreEmployeeAction Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
