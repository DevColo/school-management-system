<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Classes;
use App\Models\Subject;

class AssignSubject extends Model
{
    use HasFactory;

    public function Classes(){
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function Subject(){
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
