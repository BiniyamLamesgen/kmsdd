<?php

namespace App\Models;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Modules\HumanResource\Database\Factories\DepartmentFactory;

class Department extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'name',
    'description'
  ];

  protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'deleted_at' => 'datetime',
  ];

  public function head()
  {
    return $this->belongsTo(Employee::class, 'head_id');
  }
  public function employees()
  {
    return $this->hasMany(Employee::class);
  }
}
