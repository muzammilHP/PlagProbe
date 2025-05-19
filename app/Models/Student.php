<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Student extends Authenticatable
{
    use HasFactory;
    protected $table = 'students';
    protected $fillable = ['username', 'email', 'contact', 'email_verified_at', 'password'];

    public function classes()
{
    return $this->hasMany(StudentClass::class);
}
}
