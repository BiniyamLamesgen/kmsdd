<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CertificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Check if the image exists and handle URL generation properly
        $imageUrl = null;
        if ($this->image) {
            // Use Storage::exists to check if the file exists
            if (Storage::disk('public')->exists($this->image)) {
                $imageUrl = request()->getSchemeAndHttpHost() . '/storage/' . $this->image;
            } else {
                // Log for debugging
                \Log::warning("Certification image file not found: {$this->image}");
            }
        }

        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'certification_name' => $this->certification_name,
            'issuing_organization' => $this->issuing_organization,
            'issue_date' => $this->issue_date,
            'image' => $this->image,
            'image_url' => $imageUrl,
            'employee' => $this->whenLoaded('employee', function() {
                return [
                    'id' => $this->employee_id,
                    'first_name' => $this->employee->first_name ?? null,
                    'last_name' => $this->employee->last_name ?? null,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function getCertificationStatus(): string
    {
        if (!$this->expiry_date) {
            return 'No Expiry';
        }

        $expiryDate = Carbon::parse($this->expiry_date);
        $now = Carbon::now();

        if ($expiryDate->isPast()) {
            return 'Expired';
        } elseif ($expiryDate->diffInDays($now) <= 30) {
            return 'Expiring Soon';
        } else {
            return 'Valid';
        }
    }

    private function getDaysUntilExpiry(): ?int
    {
        if (!$this->expiry_date) {
            return null;
        }

        $expiryDate = Carbon::parse($this->expiry_date);
        $now = Carbon::now();

        return $expiryDate->isPast() ? 0 : $now->diffInDays($expiryDate);
    }

    private function isExpired(): bool
    {
        if (!$this->expiry_date) {
            return false;
        }

        return Carbon::parse($this->expiry_date)->isPast();
    }

    private function isExpiringSoon(): bool
    {
        if (!$this->expiry_date) {
            return false;
        }

        $expiryDate = Carbon::parse($this->expiry_date);
        return !$expiryDate->isPast() && $expiryDate->diffInDays(Carbon::now()) <= 30;
    }

    private function getValidityPeriod(): ?string
    {
        if (!$this->issue_date || !$this->expiry_date) {
            return null;
        }

        $issueDate = Carbon::parse($this->issue_date);
        $expiryDate = Carbon::parse($this->expiry_date);
        
        $years = $issueDate->diffInYears($expiryDate);
        $months = $issueDate->copy()->addYears($years)->diffInMonths($expiryDate);
        
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
