<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AchievementResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'date_awarded' => $this->date_awarded,
            'formatted_date' => $this->date_awarded ? \Carbon\Carbon::parse($this->date_awarded)->format('M d, Y') : null,
            'time_since_awarded' => $this->getTimeSinceAwarded(),
            'achievement_year' => $this->date_awarded ? \Carbon\Carbon::parse($this->date_awarded)->year : null,
            'employee' => new EmployeeResource($this->whenLoaded('employee')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function getTimeSinceAwarded(): ?string
    {
        if (!$this->date_awarded) {
            return null;
        }

        $awardedDate = \Carbon\Carbon::parse($this->date_awarded);
        $now = \Carbon\Carbon::now();
        
        if ($awardedDate->isFuture()) {
            return 'Future achievement';
        }

        $diffInDays = $awardedDate->diffInDays($now);
        
        if ($diffInDays === 0) {
            return 'Today';
        } elseif ($diffInDays === 1) {
            return '1 day ago';
        } elseif ($diffInDays < 30) {
            return "{$diffInDays} days ago";
        } elseif ($diffInDays < 365) {
            $months = $awardedDate->diffInMonths($now);
            return $months === 1 ? '1 month ago' : "{$months} months ago";
        } else {
            $years = $awardedDate->diffInYears($now);
            return $years === 1 ? '1 year ago' : "{$years} years ago";
        }
    }
}
