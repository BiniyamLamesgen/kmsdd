<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        try {
            // Seed Department 1
            $it = new Department();
            $it->name = 'IT';
            $it->description = 'Information Technology department responsible for tech infrastructure.';
            $it->created_at = now();
            $it->updated_at = now();
            $it->save();

            // Seed Department 2
            $finance = new Department();
            $finance->name = 'Finance';
            $finance->description = 'Finance department handling budgeting and accounting.';
            $finance->created_at = now();
            $finance->updated_at = now();
            $finance->save();

            // Seed Department 3
            $hr = new Department();
            $hr->name = 'Human Resources';
            $hr->description = 'HR department managing employee relations and recruitment.';
            $hr->created_at = now();
            $hr->updated_at = now();
            $hr->save();

            $this->command->info('Departments table seeded successfully.');
        } catch (\Exception $e) {
            $this->command->error('Seeding failed: ' . $e->getMessage());
            throw $e;
        }
    }
}