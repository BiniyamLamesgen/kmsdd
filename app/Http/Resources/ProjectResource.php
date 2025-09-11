<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'project_name' => $this->project_name,
            'description' => $this->description,
            'role' => $this->role,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'outcome' => $this->outcome,
            'duration' => $this->calculateDuration(),
            'status' => $this->getProjectStatus(),
            'is_ongoing' => $this->end_date === null,
            'employee' => new EmployeeResource($this->whenLoaded('employee')),
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

    private function getProjectStatus(): string
    {
        if (!$this->start_date) {
            return 'Planned';
        }

        $now = \Carbon\Carbon::now();
        $startDate = \Carbon\Carbon::parse($this->start_date);

        if ($startDate->isFuture()) {
            return 'Upcoming';
        }

        if ($this->end_date) {
            $endDate = \Carbon\Carbon::parse($this->end_date);
            return $endDate->isPast() ? 'Completed' : 'In Progress';
        }

        return 'In Progress';
    }
}
