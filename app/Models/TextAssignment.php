<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TextAssignment extends Authenticatable
{
    use HasFactory;
    protected $table = 'text_assignments';
    protected $fillable = [
        'student_id',
        'file_name',
        'file_path',
        'text_content',
        'assignment_number',
        'assignment_type',
    ];

    public function student()
    {
        // Make sure to reference the correct column in students table (id)
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
}
