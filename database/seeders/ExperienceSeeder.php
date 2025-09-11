<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Experience;
use Carbon\Carbon;

class ExperienceSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();
        $companies = [
            'Tech Corp', 'Innovation Labs', 'Digital Solutions', 'Software House',
            'Data Systems', 'Web Dynamics', 'Cloud Services', 'Mobile First'
        ];
        
        $positions = [
            'Junior Developer', 'Software Developer', 'Senior Developer', 'Lead Developer',
            'Frontend Developer', 'Backend Developer', 'Full Stack Developer',
            'UI/UX Designer', 'Project Manager', 'Team Lead'
        ];

        foreach ($employees as $employee) {
            // Create 1-3 experiences per employee
            $experienceCount = rand(1, 3);
            
            for ($i = 0; $i < $experienceCount; $i++) {
                $startDate = Carbon::now()->subYears(rand(1, 8))->subMonths(rand(0, 11));
                $endDate = $i === 0 ? null : $startDate->copy()->addYears(rand(1, 3)); // Current job has no end date
                
                Experience::create([
                    'employee_id' => $employee->id,
                    'company_name' => $companies[array_rand($companies)],
                    'position' => $positions[array_rand($positions)],
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate?->format('Y-m-d'),
                    'responsibilities' => 'Developed and maintained web applications, collaborated with cross-functional teams, implemented new features and bug fixes.',
                ]);
            }
        }
    }
}
