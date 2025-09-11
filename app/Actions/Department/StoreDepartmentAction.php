<?php

namespace App\Actions\Department;

use App\Models\Department;
use Illuminate\Support\Str;

class StoreDepartmentAction
{
    public function execute(array $data): array
    {
        try {
            if (isset($data['name'])) {
                $data['slug'] = Str::slug($data['name']) . '-' . Str::random(6); // Optional slug generation
            } else {
                throw new \Exception('Name is required.');
            }

            Department::create($data);

            return [
                'success' => true,
                'message' => 'Department created successfully.',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error creating department: ' . $e->getMessage(),
            ];
        }
    }
}
