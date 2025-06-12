<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AcademicYear;
use App\Models\College;
use App\Models\StudentDetail;
use App\Models\Major;

class StudentEnrollment extends Model
{
    use HasFactory;

    public function AcademicYear(){
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function College(){
        return $this->belongsTo(College::class, 'college_id');
    }

    public function StudentDetail(){
        return $this->belongsTo(StudentDetail::class, 'student_detail_id');
    }

    public function Major(){
        return $this->belongsTo(Major::class, 'major_id');
    }
}
