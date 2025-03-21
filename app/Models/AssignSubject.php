<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Classes;

class AssignSubject extends Model
{
    use HasFactory;

    public function Classes(){
        return $this->belongsTo(Classes::class, 'class_id');
    }
}
