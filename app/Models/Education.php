<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id','field_of_study','institution','graduation_year'
    ];

    public function employee() {
        return $this->belongsTo(Employee::class);
    }
}

