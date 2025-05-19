<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;
    protected $table = 'activity_logs';
    protected $fillable = ['activity_type', 'description', 'teacher_id'];
    public function studentAssignments()
    {
        return $this->hasMany(StudentAssignments::class, 'assignment_id');
    }
}
