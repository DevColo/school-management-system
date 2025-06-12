<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\College;
use App\Models\Course;

class AssignCourse extends Model
{
    use HasFactory;

    public function College(){
        return $this->belongsTo(College::class, 'college_id');
    }

    public function Course(){
        return $this->belongsTo(Course::class, 'course_id');
    }
}
