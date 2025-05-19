<?php

namespace App\Models;
use App\Models\Teacher;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $table = 'reports';
    protected $fillable = ['teacher_id', 'student_name', 'student_email','student_id', 'file_path','course_name','section','assignment_id'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
    public function student_assignments()
{
    return $this->belongsTo(StudentAssignments::class, 'assignment_id');
}
}
