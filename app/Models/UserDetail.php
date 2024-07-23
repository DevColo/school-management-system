<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class UserDetail extends Model
{
    use HasFactory,HasRoles;
    protected $table = 'user_detail';
    protected $fillable = [
        'user_id','first_name','other_name','last_name','gender','address','phone','job_title'
    ];
}
