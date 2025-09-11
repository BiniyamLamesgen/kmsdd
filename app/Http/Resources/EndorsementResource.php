<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EndorsementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'skill_id' => $this->skill_id,
            'endorsed_by' => $this->endorsed_by,
            'endorsement_message' => $this->endorsement_message,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'employee' => new EmployeeResource($this->whenLoaded('employee')),
            'endorser' => new EmployeeResource($this->whenLoaded('endorsedBy')),
            'skill' => new SkillResource($this->whenLoaded('skill')),
        ];
    }
}
