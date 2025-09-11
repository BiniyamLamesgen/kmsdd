<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = ['skill_name'];

    public function employees() {
        return $this->belongsToMany(Employee::class, 'employee_skills')
                    ->withPivot('proficiency_level','endorsements_count')
                    ->withTimestamps();
    }

    public function endorsements() {
        return $this->hasMany(Endorsement::class);
    }
}

