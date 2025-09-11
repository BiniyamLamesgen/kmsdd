<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExperienceResource extends JsonResource
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
            'company_name' => $this->company_name,
            'position' => $this->position,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'responsibilities' => $this->responsibilities,
            'employee' => $this->whenLoaded('employee', function() {
                return [
                    'id' => $this->employee_id,
                    'first_name' => $this->employee->first_name ?? null,
                    'last_name' => $this->employee->last_name ?? null,
                ];
            }),
            'duration' => $this->calculateDuration(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function calculateDuration(): ?string
    {
        if (!$this->start_date) {
            return null;
        }

        $startDate = \Carbon\Carbon::parse($this->start_date);
        $endDate = $this->end_date ? \Carbon\Carbon::parse($this->end_date) : \Carbon\Carbon::now();
        
        $years = $startDate->diffInYears($endDate);
        $months = $startDate->copy()->addYears($years)->diffInMonths($endDate);
        
        if ($years > 0 && $months > 0) {
            return "{$years} year(s) {$months} month(s)";
        } elseif ($years > 0) {
            return "{$years} year(s)";
        } elseif ($months > 0) {
            return "{$months} month(s)";
        } else {
            return "Less than a month";
        }
    }
}
