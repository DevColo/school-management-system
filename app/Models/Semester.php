<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AcademicYear;

class Semester extends Model
{
    use HasFactory;

    public function AcademicYear(){
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

}