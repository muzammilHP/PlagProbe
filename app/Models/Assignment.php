<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;
    protected $table = 'assignments';
    protected $fillable = ['name', 'completion_date', 'type', 'teacher_id','class_code'];
    public function studentAssignments()
    {
        return $this->hasMany(StudentAssignments::class, 'assignment_id');
    }
    public function classroom()
{
    return $this->belongsTo(Classroom::class, 'class_code', 'class_code');
}
}
