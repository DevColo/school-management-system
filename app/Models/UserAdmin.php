<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class UserAdmin extends Model
{
    use HasFactory,HasRoles;
    protected $table = 'user_admin';
    protected $fillable = [
        'created_by','user_detail_id'
    ];
}
