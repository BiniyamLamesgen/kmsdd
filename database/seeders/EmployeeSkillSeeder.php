<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Skill;
use App\Models\EmployeeSkill;

class EmployeeSkillSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();
        $skills = Skill::all();
        $proficiencyLevels = ['Beginner', 'Intermediate', 'Advanced', 'Expert'];

        foreach ($employees as $employee) {
            // Assign 3-8 random skills to each employee
            $randomSkills = $skills->random(rand(3, 8));
            
            foreach ($randomSkills as $skill) {
                EmployeeSkill::create([
                    'employee_id' => $employee->id,
                    'skill_id' => $skill->id,
                    'proficiency_level' => $proficiencyLevels[array_rand($proficiencyLevels)],
                    'endorsements_count' => rand(0, 10),
                ]);
            }
        }
    }
}
