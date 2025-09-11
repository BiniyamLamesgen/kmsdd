<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id','title','description','date_awarded'];

    public function employee() {
        return $this->belongsTo(Employee::class);
    }
}

