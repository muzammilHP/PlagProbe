<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
// App\Http\Controllers\Auth
use App\Models\Student;
use App\Models\Assignment;
use App\Models\Admin;
use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\Report;
use App\Models\StudentClass;
use App\Models\StudentAssignments;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class TradeController extends Controller
{
    public function homepage(){
        return view('Main.index');
    }

    public function adminDashboard(){
        $students = Student::all();
        $teachers = Teacher::all();
        $classes = Classroom::all();
        $reports = Report::all();

    $totalStudents = $students->count();
    $totalTeachers = $teachers->count();
    $totalClasses = $classes->count();
    $totalReports = $reports->count();
    return view('Admin.admin', compact('students', 'teachers', 'classes', 'reports','totalStudents', 'totalTeachers', 'totalClasses', 'totalReports'));
    }
    public function studentpanel(){
        $student = auth('student')->user();
        $studentId = $student->id;

        $totalClasses = StudentClass::where('student_id', $studentId)->count();
  
        $classCodes = StudentClass::where('student_id', $studentId)->pluck('class_code');
    
        $totalAssignments = Assignment::whereIn('class_code', $classCodes)->count();
    
        $uploadedAssignments = StudentAssignments::where('student_id', $studentId)->count();
    
        $assignmentIds = Assignment::whereIn('class_code', $classCodes)->pluck('id');
    
        $pendingAssignments = Assignment::whereIn('id', $assignmentIds)
            ->whereNotIn('id', function ($query) use ($studentId) {
                $query->select('assignment_id')
                    ->from('student_assignments')
                    ->where('student_id', $studentId);
            })->count();
    
        return view('Main.studentpanel', compact(
            'student',
            'totalClasses',
            'totalAssignments',
            'uploadedAssignments',
            'pendingAssignments'
        ));
    }
    

    public function teacherPanel(){
        $teacher = auth('teacher')->user();
        $teacherId = $teacher->id;

        $totalClasses = Classroom::where('teacher_id', $teacherId)->count();

        $totalStudents = StudentClass::whereIn('class_code', 
            Classroom::where('teacher_id', $teacherId)->pluck('class_code')
        )->distinct('student_id')->count();

        $totalAssignments = Assignment::where('teacher_id', $teacherId)->count();   
        $totalReports = Report::where('teacher_id', $teacherId)->count();
        $recentActivities = ActivityLog::where('teacher_id', auth('teacher')->id())
    ->orderBy('created_at', 'desc')
    ->take(10)
    ->get();      

        return view('Main.teacherpanel', compact('totalClasses', 'totalStudents', 'totalAssignments','teacher','recentActivities','totalReports'));
    }
}
