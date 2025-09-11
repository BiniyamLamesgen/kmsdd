<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SkillResource extends JsonResource
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
            'skill_name' => $this->skill_name,
            'employees_count' => $this->whenCounted('employees'),
            'total_endorsements' => $this->when(
                $this->relationLoaded('employees'),
                function () {
                    return $this->employees->sum('pivot.endorsements_count');
                }
            ),
            'employees' => EmployeeResource::collection($this->whenLoaded('employees')),
            'endorsements' => EndorsementResource::collection($this->whenLoaded('endorsements')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
