<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentClass extends Model
{
    use HasFactory;
    protected $table = 'student_classes';
    protected $fillable = ['student_id', 'class_code'];

public function classroom()
{
    return $this->belongsTo(Classroom::class, 'class_code', 'class_code');
}
}
