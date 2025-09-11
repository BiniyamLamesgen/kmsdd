<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Employee extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable, HasRoles;

    protected $fillable = [
        'first_name',
        'last_name',
        'position',
        'department_id',
        'email',
        'phone',
        'internal_extension',
        'profile_picture',
        'date_joined',
        'linkedin',
        'facebook',
        'twitter',
        'github',
        'personal_website',
        'languages',
        'mentoring_interest',
        'availability_for_sharing',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'availability_for_sharing' => 'boolean',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'employee_skills')
            ->withPivot('proficiency_level', 'endorsements_count')
            ->withTimestamps();
    }

    public function achievements()
    {
        return $this->hasMany(Achievement::class);
    }

    public function certifications()
    {
        return $this->hasMany(Certification::class);
    }

    public function education()
    {
        return $this->hasMany(Education::class);
    }

    public function knowledgeSharing()
    {
        return $this->hasMany(KnowledgeSharing::class);
    }

    public function endorsementsGiven()
    {
        return $this->hasMany(Endorsement::class, 'endorsed_by');
    }

    public function endorsementsReceived()
    {
        return $this->hasMany(Endorsement::class, 'employee_id');
    }

    /**
     * Get the guard name for the model.
     */
    public function getGuardName(): string
    {
        return 'employee';
    }
}
