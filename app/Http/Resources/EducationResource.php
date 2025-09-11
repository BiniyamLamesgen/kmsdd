<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EducationResource extends JsonResource
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
            'degree' => $this->degree,
            'field_of_study' => $this->field_of_study,
            'institution' => $this->institution,
            'graduation_year' => $this->graduation_year,
            'full_qualification' => $this->getFullQualification(),
            'education_level' => $this->getEducationLevel(),
            'years_since_graduation' => $this->getYearsSinceGraduation(),
            'status' => $this->getGraduationStatus(),
            'employee' => new EmployeeResource($this->whenLoaded('employee')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function getFullQualification(): string
    {
        $parts = array_filter([
            $this->degree,
            $this->field_of_study ? "in {$this->field_of_study}" : null,
            $this->institution ? "from {$this->institution}" : null,
        ]);

        return implode(' ', $parts);
    }

    private function getEducationLevel(): string
    {
        $degree = strtolower($this->degree);
        
        if (str_contains($degree, 'phd') || str_contains($degree, 'doctorate')) {
            return 'Doctorate';
        } elseif (str_contains($degree, 'master') || str_contains($degree, 'msc') || str_contains($degree, 'mba')) {
            return 'Masters';
        } elseif (str_contains($degree, 'bachelor') || str_contains($degree, 'bsc') || str_contains($degree, 'ba')) {
            return 'Bachelors';
        } elseif (str_contains($degree, 'associate') || str_contains($degree, 'diploma')) {
            return 'Associate/Diploma';
        } elseif (str_contains($degree, 'certificate')) {
            return 'Certificate';
        } else {
            return 'Other';
        }
    }

    private function getYearsSinceGraduation(): ?int
    {
        if (!$this->graduation_year) {
            return null;
        }

        $currentYear = (int) date('Y');
        return $currentYear - $this->graduation_year;
    }

    private function getGraduationStatus(): string
    {
        if (!$this->graduation_year) {
            return 'Unknown';
        }

        $currentYear = (int) date('Y');
        
        if ($this->graduation_year > $currentYear) {
            return 'Expected';
        } elseif ($this->graduation_year === $currentYear) {
            return 'Recent Graduate';
        } else {
            return 'Graduate';
        }
    }
}
