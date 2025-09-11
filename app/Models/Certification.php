<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'certification_name',
        'issuing_organization',
        'issue_date',
        'image',
    ];

    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? \Storage::disk('public')->url($this->image) : null;
    }
}

