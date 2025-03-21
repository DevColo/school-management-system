<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Semester;

class Period extends Model
{
    use HasFactory;

    public function Semester(){
        return $this->belongsTo(Semester::class, 'semester_id');
    }
}
