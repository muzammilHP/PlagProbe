<?php

namespace App\Http\Controllers;
use App\Models\Assignment;
use App\Models\StudentClass;
use App\Models\StudentAssignments;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Report;
use App\Models\Classroom;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\ActivityLog;

class AssignmentController extends Controller
{
    public function createAssignment(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'completion_date' => 'required|date',
        'type' => 'required|string',
        'class_code' => 'required|string',
    ]);
    $teacherId = Auth::guard('teacher')->id();
    Assignment::create([
        'name' => $request->name,
        'completion_date' => $request->completion_date,
        'type' => $request->type,
        'teacher_id' => $teacherId,
        'class_code' => $request->class_code,
    ]);
    $classroom = Classroom::where('class_code', $request->class_code)->first();

    ActivityLog::create([
        'activity_type' => 'assignment_created',
        'description' => 'Assignment "' . $request->name . '" (Type: ' . $request->type . ') for class "' . $classroom->course_name . '" (Section: ' . $classroom->section_name . ') was created by on ' . now()->format('F j, Y, g:i A') . '.',
        'teacher_id' => $teacherId,
    ]);

    return response()->json(['message' => 'Assignment saved successfully!']);
}

public function getAssignments($classCode)
{
    $assignments = Assignment::where('class_code', $classCode)->get();
    
    if ($assignments->isEmpty()) {
        return response()->json(['message' => 'No assignments found'], 404);
    }

    return response()->json($assignments);
}

public function getTeacherAssignments()
{
    $teacherId = Auth::guard('teacher')->id();

    $assignments = DB::table('assignments')
        ->join('classrooms', 'assignments.class_code', '=', 'classrooms.class_code')
        ->where('assignments.teacher_id', $teacherId)
        ->select(
            'assignments.name as assignment_name',
            'assignments.completion_date',
            'assignments.type',
            'assignments.id',
            'assignments.created_at',
            'classrooms.course_name',
            'classrooms.section_name'
        )
        ->get();

    return response()->json($assignments);
}

public function deleteAssignment($id){
    $assignment = Assignment::find($id);

    if (!$assignment) {
        return response()->json(['success' => false, 'message' => 'Assignment not found.'], 404);
    }

    $teacher = Teacher::find($assignment->teacher_id);
    $classroom = Classroom::where('class_code', $assignment->class_code)->first();

    $assignment->delete();

    ActivityLog::create([
        'activity_type' => 'assignment_deleted',
        'description' => 'Assignment "' . $assignment->name . '" (Type: ' . $assignment->type . ') for class "' . $classroom->course_name . '" (Section: ' . $classroom->section_name . ') was deleted on ' . now()->format('F j, Y, g:i A') . '.',
        'teacher_id' => $assignment->teacher_id,
    ]);

    return response()->json(['success' => true]);
}
public function getStudentAssignments($classCode)
{
    $studentId = Auth::guard('student')->id();

    $enrolledClass = StudentClass::where('student_id', $studentId)
        ->where('class_code', $classCode)
        ->first();

    if (!$enrolledClass) {
        return response()->json(['error' => 'Class not found or you are not enrolled'], 404);
    }

    // Fetch assignments with submission status
    $assignments = Assignment::where('class_code', $classCode)
        ->get()
        ->map(function ($assignment) use ($studentId) {
            $assignment->is_submitted = StudentAssignments::where('assignment_id', $assignment->id)
                ->where('student_id', $studentId)
                ->exists();
            return $assignment;
        });

    return response()->json($assignments);
}

public function uploadAssignment(Request $request)
{
    // Validate basic file requirements
    $request->validate([
        'assignment_id' => 'required|exists:assignments,id',
        'student_id' => 'required|exists:students,id',
        'file' => 'required|file|max:10120' // Max file size: 5MB
    ]);
    
    // Retrieve assignment details
    $assignment = Assignment::find($request->assignment_id);
    if (!$assignment) {
        return response()->json(['success' => false, 'message' => 'Assignment not found'], 404);
    }

    $student = Student::find($request->student_id);
    $existingSubmission = StudentAssignments::where('assignment_id', $assignment->id)
            ->where('student_id', $student->id)
            ->first();

    if ($existingSubmission) {
            return response()->json(['success' => false, 'message' => 'You have already submitted this assignment'], 400);
    }
    
    $allowedExtensions = [
        'text' => ['pdf', 'docx', 'txt'],
        'handwritten' => ['jpg','jpeg','png','pdf','docx'],
        'programming' => ['cpp', 'py', 'java', 'js', 'php'],
        'presentation' => ['ppt', 'pptx']
    ];

    // Get file extension
    $file = $request->file('file');
    $fileExtension = strtolower($file->getClientOriginalExtension());

    // Check if the uploaded file matches the assignment type
    if (!in_array($fileExtension, $allowedExtensions[$assignment->type])) {
        return response()->json([
            'success' => false,
            'message' => "Invalid file type. Please upload a " . strtoupper($assignment->type) . " file."
        ], 400);
    }

    // Create a folder structure like: assignments/Assignment_1/Student_Name/
    $folderPath = "assignments/{$assignment->name}/{$student->name}";
$fileName = time() . '_' . $file->getClientOriginalName();

// Store in 'public' disk (which maps to storage/app/public)
$filePath = $file->storeAs("public/$folderPath", $fileName);

// Save the publicly accessible path in the database
StudentAssignments::create([
    'assignment_id' => $assignment->id,
    'student_id' => $student->id,
    'file_path' => "storage/$folderPath/$fileName"
]);

ActivityLog::create([
    'activity_type' => 'assignment_uploaded',
    'description' => 'Student "' . $student->name . '" submitted assignment "' . $assignment->name . '" (Type: ' . $assignment->type . ') on ' . now()->format('F j, Y, g:i A') . '.',
    'student_id' => $student->id,
]);

return response()->json([
    'success' => true,
    'filePath' => asset("storage/$folderPath/$fileName"),
    'message' => 'Assignment uploaded successfully!'
]);
}

public function getSubmittedAssignments($classCode,$assignmentId)
{
    try {
        $submittedAssignments = StudentAssignments::whereHas('assignment', function ($query) use ($classCode, $assignmentId) {
            $query->where('class_code', $classCode)
                  ->where('id', $assignmentId); // Filter by assignment ID
        })->with('student', 'assignment')->get();

        // Fix file path and rename 'username' to 'name' for frontend compatibility
        $submittedAssignments->transform(function ($submission) {
            $submission->file_url = asset(str_replace('public/', 'storage/', $submission->file_path));
            $submission->student_name = $submission->student->username; // Rename 'username' to 'student_name'
            return $submission;
        });

        return response()->json($submittedAssignments);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
public function getAllSubmittedAssignments($assignmentId){
    try {
        $submittedAssignments = StudentAssignments::whereHas('assignment', function ($query) use ($assignmentId) {
            $query->where('id', $assignmentId);
        })->with('student', 'assignment')->get();
    
        $submittedAssignments->transform(function ($submission) {
            $submission->file_url = asset(str_replace('public/', 'storage/', $submission->file_path));
            $submission->student_name = $submission->student->username;
            return $submission;
        });
    
        return response()->json($submittedAssignments);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function getPendingAssignments()
{
    $studentId = Auth::guard('student')->id();
    $classCodes = StudentClass::where('student_id', $studentId)->pluck('class_code');

    $allAssignments = Assignment::whereIn('class_code', $classCodes)->get();

    $submittedAssignments = StudentAssignments::where('student_id', $studentId)->pluck('assignment_id')->toArray();

    $pendingAssignments = $allAssignments->filter(function ($assignment) use ($submittedAssignments) {
        return !in_array($assignment->id, $submittedAssignments);
    });

    return response()->json($pendingAssignments->values());
}

public function getUploadedHistory(Request $request)
{
    $studentId = Auth::guard('student')->id();

    $studentClasses = StudentClass::with('classroom')
        ->where('student_id', $studentId)
        ->get();

    $classroomMap = $studentClasses->pluck('classroom', 'class_code');

    $uploads = StudentAssignments::with('assignment')
        ->where('student_id', $studentId)
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($upload) use ($classroomMap) {

            $classCode = optional($upload->assignment)->class_code ?? null;
            $classroom = $classroomMap[$classCode] ?? null;

            return [
                'assignment_name' => optional($upload->assignment)->name ?? 'N/A',
                'course_name' => optional($classroom)->course_name ?? 'N/A',
                'section_name' => optional($classroom)->section_name ?? 'N/A',
                'created_at' => $upload->created_at,
                'file_path' => $upload->file_path,
            ];
        });

    return response()->json([
        'success' => true,
        'uploads' => $uploads
    ]);
}
}
