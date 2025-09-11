<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id','project_name','description','role','start_date','end_date','outcome'
    ];

    public function employee() {
        return $this->belongsTo(Employee::class);
    }
}

