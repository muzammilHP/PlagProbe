<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\StudentClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ActivityLog;


class ClassroomController extends Controller
{
    public function createClass(Request $request)
    {
        $request->validate([
            'courseName' => 'required',
            'teacherName' => 'required',
            'sectionName' => 'required',
            'classCode' => 'required|unique:classrooms,class_code',
        ]);

        $teacherId = Auth::guard('teacher')->id();
        \Log::info('Authenticated Teacher ID: ' . ($teacherId ?? 'NULL'));

        Classroom::create([
            'course_name' => $request->courseName,
            'teacher_name' => $request->teacherName,
            'section_name' => $request->sectionName,
            'teacher_id' => $teacherId,
            'class_code' => $request->classCode
        ]);

        ActivityLog::create([
            'activity_type' => 'class_created',
            'description' => 'Class "' . $request->courseName . '" (Section: ' . $request->sectionName .') was created by '. ' on ' . now()->format('F j, Y, g:i A') . '.',
            'teacher_id' => $teacherId,
        ]);
        return response()->json(['success' => true, 'message' => 'Class created successfully!']);
    }

    public function getClasses()
    {
        $teacherId = auth('teacher')->id();
        $classes = Classroom::where('teacher_Id', $teacherId)->get();
        return response()->json($classes);
    }

    public function deleteClass($id)
    {
        $class = Classroom::find($id);

    if (!$class) {
        return response()->json(['success' => false, 'message' => 'Class not found.'], 404);
    }

    $courseName = $class->course_name;
    $sectionName = $class->section_name;
    $teacherId = $class->teacher_id;

    $class->delete();

    ActivityLog::create([
        'activity_type' => 'class_deleted',
        'description' => 'Class "' . $courseName . '" (Section: ' . $sectionName . ') was deleted on ' . now()->format('F j, Y, g:i A') . '.',
        'teacher_id' => $teacherId,
    ]);

    return response()->json(['success' => true]);
    }

    public function joinClass(Request $request){

        $request->validate([
            'classCode' => 'required|exists:classrooms,class_code',
        ]);

        $studentId=Auth::guard('student')->id();

        $alreadyJoined=StudentClass::where('student_id',$studentId)->where('class_code',$request->classCode)->exists();
        if($alreadyJoined){
            return response()->json(['success' => false, 'message' => 'You are already enrolled in this class.']);
        }

        StudentClass::create([
            'student_id'=>$studentId,
            'class_code'=>$request->classCode,
        ]);

        $student = Student::find($studentId);
    $classroom = Classroom::where('class_code', $request->classCode)->first();

    ActivityLog::create([
        'activity_type' => 'class_joined',
        'description' => 'Student "' . $student->name . '" joined class "' . $classroom->course_name . '" (Section: ' . $classroom->section_name . ') on ' . now()->format('F j, Y, g:i A') . '.',
        'student_id' => $studentId,
    ]);
        return response()->json(['message' => 'Class joined successfully']);
    }

    public function getStudentClasses(){
        $studentId=Auth::guard('student')->id();
        $classes = Classroom::join('student_classes', 'classrooms.class_code', '=', 'student_classes.class_code')
                        ->where('student_classes.student_id', $studentId)
                        ->select('classrooms.*', 'student_classes.id as student_class_id')
                        ->get();

        return response()->json($classes);
    }

    public function deleteStudentClass($id)
    {
        \Log::info("Deleting class with ID: " . $id);
    
        $class = StudentClass::find($id);
        if (!$class) {
            return response()->json(['success' => false, 'message' => 'Class not found!'], 404);
        }
    
        $class->delete();
        return response()->json(['success' => true, 'message' => 'Class deleted successfully!']);
    }

}
