<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KnowledgeSharing extends Model
{
    use HasFactory;

    protected $table = 'knowledge_sharings';

    protected $fillable = [
        'employee_id','document_title','document_type','link','date_shared'
    ];

    public function employee() {
        return $this->belongsTo(Employee::class);
    }
}

