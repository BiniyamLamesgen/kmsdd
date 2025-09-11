<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeSkillResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'skill_id' => $this->skill_id,
            'proficiency_level' => $this->proficiency_level,
            'endorsements_count' => $this->endorsements_count,
            'proficiency_badge' => $this->getProficiencyBadge(),
            'endorsement_status' => $this->getEndorsementStatus(),
            'employee' => new EmployeeResource($this->whenLoaded('employee')),
            'skill' => new SkillResource($this->whenLoaded('skill')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function getProficiencyBadge(): string
    {
        return match($this->proficiency_level) {
            'Beginner' => '🌱',
            'Intermediate' => '🌿',
            'Advanced' => '🌳',
            'Expert' => '⭐',
            default => '❓'
        };
    }

    private function getEndorsementStatus(): string
    {
        $count = $this->endorsements_count ?? 0;
        
        if ($count === 0) return 'No endorsements';
        if ($count <= 2) return 'Few endorsements';
        if ($count <= 5) return 'Some endorsements';
        if ($count <= 10) return 'Many endorsements';
        
        return 'Highly endorsed';
    }
}
