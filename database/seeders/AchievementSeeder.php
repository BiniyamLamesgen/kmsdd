<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Achievement;
use Carbon\Carbon;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();
        $achievements = [
            'Employee of the Month',
            'Outstanding Performance Award',
            'Innovation Award',
            'Team Player Award',
            'Customer Excellence Award',
            'Technical Excellence Award',
            'Leadership Award',
            'Mentor of the Year',
            'Best Project Delivery',
            'Quality Assurance Champion'
        ];
        
        $descriptions = [
            'Recognized for exceptional performance and dedication to work.',
            'Awarded for innovative solutions and creative problem-solving.',
            'Acknowledged for outstanding teamwork and collaboration.',
            'Received for delivering high-quality results consistently.',
            'Honored for technical expertise and knowledge sharing.'
        ];

        foreach ($employees as $employee) {
            // Create 0-3 achievements per employee
            $achievementCount = rand(0, 3);
            
            for ($i = 0; $i < $achievementCount; $i++) {
                Achievement::create([
                    'employee_id' => $employee->id,
                    'title' => $achievements[array_rand($achievements)],
                    'description' => $descriptions[array_rand($descriptions)],
                    'date_awarded' => Carbon::now()->subYears(rand(0, 3))->subMonths(rand(0, 11))->format('Y-m-d'),
                ]);
            }
        }
    }
}
