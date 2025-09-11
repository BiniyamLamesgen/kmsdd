<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Project;
use Carbon\Carbon;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();
        $projectNames = [
            'E-commerce Platform', 'Customer Portal', 'Mobile App Development',
            'API Integration', 'Database Migration', 'UI Redesign',
            'Performance Optimization', 'Security Enhancement', 'Analytics Dashboard',
            'Payment Gateway', 'Content Management System', 'Inventory System'
        ];
        
        $roles = [
            'Lead Developer', 'Frontend Developer', 'Backend Developer',
            'Project Manager', 'UI/UX Designer', 'Quality Assurance',
            'DevOps Engineer', 'Technical Lead'
        ];

        foreach ($employees as $employee) {
            // Create 2-5 projects per employee
            $projectCount = rand(2, 5);
            
            for ($i = 0; $i < $projectCount; $i++) {
                $startDate = Carbon::now()->subYears(rand(0, 3))->subMonths(rand(0, 11));
                $endDate = rand(0, 1) ? $startDate->copy()->addMonths(rand(1, 12)) : null;
                
                Project::create([
                    'employee_id' => $employee->id,
                    'project_name' => $projectNames[array_rand($projectNames)],
                    'description' => 'A comprehensive project focused on delivering high-quality solutions to meet business requirements.',
                    'role' => $roles[array_rand($roles)],
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate?->format('Y-m-d'),
                    'outcome' => $endDate ? 'Successfully completed with positive client feedback and improved system performance.' : null,
                ]);
            }
        }
    }
}
