<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endorsement extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'skill_id',
        'endorsed_by',
        'endorsement_date',
        'endorsement_message'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function endorsedBy()
    {
        return $this->belongsTo(Employee::class, 'endorsed_by');
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }
}
