<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class EmployeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->first_name . ' ' . $this->last_name,
            'position' => $this->position,
            'department' => $this->department ? $this->department->name : null,
            'email' => $this->email,
            'phone' => $this->phone,
            'internal_extension' => $this->internal_extension,
            'profile_picture' => $this->profile_picture ? Storage::disk('public')->url($this->profile_picture) : null,
            'date_joined' => $this->date_joined,

            'social_links' => [
                'linkedin' => $this->linkedin,
                'facebook' => $this->facebook,
                'twitter' => $this->twitter,
                'github' => $this->github,
                'personal_website' => $this->personal_website,
            ],

            'languages' => $this->languages,
            'mentoring_interest' => $this->mentoring_interest,
            'availability_for_sharing' => $this->availability_for_sharing,

            // relationships
            'experiences' => ExperienceResource::collection($this->whenLoaded('experiences')),
            'projects' => ProjectResource::collection($this->whenLoaded('projects')),
            'skills' => SkillResource::collection($this->whenLoaded('skills')),
            'achievements' => AchievementResource::collection($this->whenLoaded('achievements')),
            'certifications' => CertificationResource::collection($this->whenLoaded('certifications')),
            'education' => EducationResource::collection($this->whenLoaded('education')),
            'knowledge_sharing' => KnowledgeSharingResource::collection($this->whenLoaded('knowledgeSharing')),
            'endorsements' => EndorsementResource::collection($this->whenLoaded('endorsementsReceived')),

            'roles' => $this->whenLoaded('roles', function () {
                return $this->roles->pluck('name');
            }),

            'permissions' => $this->when($request->user()?->can('view permissions'), function () {
                return $this->getAllPermissions()->pluck('name');
            }),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
