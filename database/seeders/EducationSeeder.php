<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Education;

class EducationSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();
        $fields = [
            'Computer Science',
            'Software Engineering',
            'Information Technology',
            'Business Administration',
            'Information Systems',
            'Data Science',
            'Cybersecurity',
            'Web Development',
            'Mobile Development',
            'AI/ML'
        ];

        $institutions = [
            'Stanford University',
            'MIT',
            'Harvard University',
            'UC Berkeley',
            'Carnegie Mellon University',
            'University of Washington',
            'Georgia Tech',
            'University of Illinois',
            'Cornell University',
            'Columbia University',
            'State University',
            'Community College'
        ];

        foreach ($employees as $employee) {
            // Create 1-2 education records per employee
            $educationCount = rand(1, 2);

            for ($i = 0; $i < $educationCount; $i++) {
                Education::create([
                    'employee_id' => $employee->id,
                    'field_of_study' => $fields[array_rand($fields)],
                    'institution' => $institutions[array_rand($institutions)],
                    'graduation_year' => rand(2010, 2023),
                ]);
            }
        }
    }
}
