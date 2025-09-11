<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KnowledgeSharingResource extends JsonResource
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
            'document_title' => $this->document_title,
            'document_type' => $this->document_type,
            'link' => $this->link,
            'date_shared' => $this->date_shared,
            'formatted_date' => $this->date_shared ? \Carbon\Carbon::parse($this->date_shared)->format('M d, Y') : null,
            'time_since_shared' => $this->getTimeSinceShared(),
            'document_category' => $this->getDocumentCategory(),
            'has_link' => !empty($this->link),
            'sharing_year' => $this->date_shared ? \Carbon\Carbon::parse($this->date_shared)->year : null,
            'employee' => new EmployeeResource($this->whenLoaded('employee')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function getTimeSinceShared(): ?string
    {
        if (!$this->date_shared) {
            return null;
        }

        $sharedDate = \Carbon\Carbon::parse($this->date_shared);
        $now = \Carbon\Carbon::now();

        $years = $sharedDate->diffInYears($now);
        $months = $sharedDate->copy()->addYears($years)->diffInMonths($now);

        if ($years > 0) {
            return "{$years} year" . ($years > 1 ? 's' : '') . " ago";
        } elseif ($months > 0) {
            return "{$months} month" . ($months > 1 ? 's' : '') . " ago";
        } else {
            $days = $sharedDate->diffInDays($now);
            if ($days > 0) {
                return "{$days} day" . ($days > 1 ? 's' : '') . " ago";
            } else {
                return "Today";
            }
        }
    }

    private function getDocumentCategory(): string
    {
        $type = strtolower($this->document_type ?? '');

        switch ($type) {
            case 'article':
            case 'blog':
                return 'Article/Blog';
            case 'tutorial':
            case 'guide':
                return 'Tutorial/Guide';
            case 'presentation':
                return 'Presentation';
            case 'documentation':
                return 'Documentation';
            case 'video':
                return 'Video';
            case 'template':
                return 'Template';
            default:
                return ucfirst($type) ?: 'Other';
        }
    }
}
