<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Skill;
use App\Models\Endorsement;
use App\Models\EmployeeSkill;
use Carbon\Carbon;

class EndorsementSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();
        $employeeSkills = EmployeeSkill::all();

        foreach ($employeeSkills as $employeeSkill) {
            // Create 0-5 endorsements per employee skill
            $endorsementCount = rand(0, 5);
            
            // Get other employees who can endorse (not the skill owner)
            $potentialEndorsers = $employees->where('id', '!=', $employeeSkill->employee_id);
            
            if ($potentialEndorsers->count() === 0) continue;
            
            $selectedEndorsers = $potentialEndorsers->random(min($endorsementCount, $potentialEndorsers->count()));
            
            foreach ($selectedEndorsers as $endorser) {
                Endorsement::create([
                    'employee_id' => $employeeSkill->employee_id,
                    'skill_id' => $employeeSkill->skill_id,
                    'endorsed_by' => $endorser->id,
                    'endorsement_date' => Carbon::now()->subYears(rand(0, 2))->subMonths(rand(0, 11))->format('Y-m-d'),
                ]);
            }
        }

        // Update endorsement counts in employee_skills table
        foreach ($employeeSkills as $employeeSkill) {
            $count = Endorsement::where('employee_id', $employeeSkill->employee_id)
                               ->where('skill_id', $employeeSkill->skill_id)
                               ->count();
            
            $employeeSkill->update(['endorsements_count' => $count]);
        }
    }
}
