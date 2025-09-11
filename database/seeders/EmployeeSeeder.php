<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;
use App\Models\Department;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        // Make sure departments exist first
        $departments = Department::all();
        if ($departments->count() < 3) {
            // Create some departments if they don't exist
            Department::firstOrCreate(['name' => 'IT'], [
                'description' => 'Information Technology department responsible for tech infrastructure.'
            ]);
            Department::firstOrCreate(['name' => 'Finance'], [
                'description' => 'Finance department handling budgeting and accounting.'
            ]);
            Department::firstOrCreate(['name' => 'Human Resources'], [
                'description' => 'HR department managing employee relations and recruitment.'
            ]);

            $departments = Department::all();
        }

        $employees = [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'position' => 'Software Engineer',
                'department_id' => $departments->where('name', 'IT')->first()?->id ?? 1,
                'email' => 'john.doe@company.com',
                'password' => Hash::make('password'),
                'phone' => '+1234567890',
                'internal_extension' => '1001',
                'date_joined' => '2020-01-15',
                'linkedin' => 'https://linkedin.com/in/johndoe',
                'github' => 'https://github.com/johndoe',
                'languages' => 'English, Spanish',
                'mentoring_interest' => 'Frontend development, React, Vue.js',
                'availability_for_sharing' => true,
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'position' => 'Senior Developer',
                'department_id' => $departments->where('name', 'IT')->first()?->id ?? 1,
                'email' => 'jane.smith@company.com',
                'password' => Hash::make('password'),
                'phone' => '+1234567891',
                'internal_extension' => '1002',
                'date_joined' => '2019-03-10',
                'linkedin' => 'https://linkedin.com/in/janesmith',
                'github' => 'https://github.com/janesmith',
                'languages' => 'English, French',
                'mentoring_interest' => 'Backend development, PHP, Laravel',
                'availability_for_sharing' => true,
            ],
            [
                'first_name' => 'Mike',
                'last_name' => 'Johnson',
                'position' => 'Project Manager',
                'department_id' => $departments->where('name', 'Finance')->first()?->id ?? 2,
                'email' => 'mike.johnson@company.com',
                'password' => Hash::make('password'),
                'phone' => '+1234567892',
                'internal_extension' => '1003',
                'date_joined' => '2018-07-20',
                'linkedin' => 'https://linkedin.com/in/mikejohnson',
                'languages' => 'English',
                'mentoring_interest' => 'Project management, Agile methodologies',
                'availability_for_sharing' => false,
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Wilson',
                'position' => 'UX Designer',
                'department_id' => $departments->where('name', 'Human Resources')->first()?->id ?? 3,
                'email' => 'sarah.wilson@company.com',
                'password' => Hash::make('password'),
                'phone' => '+1234567893',
                'internal_extension' => '1004',
                'date_joined' => '2021-02-12',
                'linkedin' => 'https://linkedin.com/in/sarahwilson',
                'languages' => 'English, German',
                'mentoring_interest' => 'UI/UX design, User research',
                'availability_for_sharing' => true,
            ],
            [
                'first_name' => 'Admin',
                'last_name' => 'Employee',
                'position' => 'System Administrator',
                'department_id' => $departments->where('name', 'IT')->first()?->id ?? 1,
                'email' => 'admin@company.com',
                'password' => Hash::make('password'),
                'phone' => '+1234567894',
                'internal_extension' => '1000',
                'date_joined' => '2017-01-01',
                'languages' => 'English',
                'mentoring_interest' => 'System administration, DevOps',
                'availability_for_sharing' => true,
            ],
        ];

        foreach ($employees as $employeeData) {
            Employee::create($employeeData);
        }
    }
}
