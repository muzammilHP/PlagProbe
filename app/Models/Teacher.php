<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Teacher extends Authenticatable
{
    use HasFactory;
    use HasFactory;
    protected $table = 'teachers';
    protected $fillable = ['username', 'email', 'contact', 'email_verified_at', 'password'];
}
