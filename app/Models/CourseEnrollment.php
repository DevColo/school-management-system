<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AcademicYear;
use App\Models\College;
use App\Models\User;
use App\Models\Semester;
use App\Models\Course;

class CourseEnrollment extends Model
{
    use HasFactory;

        public function AcademicYear(){
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function College(){
        return $this->belongsTo(College::class, 'college_id');
    }

    public function User(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function Semester(){
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    public function Course(){
        return $this->belongsTo(Course::class, 'course_id');
    }
}
