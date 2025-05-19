<?php

namespace App\Http\Controllers;

use App\Models\TextAssignment;
use App\Models\StudentAssignments;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Assignment;
use App\Models\ActivityLog;
use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\IOFactory;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TextAssignmentController extends Controller
{
    public function checkPlag(Request $request)
{
    $assignmentId = $request->input('id');
    $selectedAssignment = StudentAssignments::findOrFail($assignmentId);

    $student = $selectedAssignment->student;
    
    $OriginalStudentName = $student->username;
    $OriginalStudentEmail = $student->email;
    $OriginalStudentId = $student->id;

    $selectedFilePath = storage_path('app/' . str_replace('storage/', 'public/', $selectedAssignment->file_path));

    Log::info('Selected file path: ' . $selectedFilePath);
    $otherAssignments = StudentAssignments::where('assignment_id', $selectedAssignment->assignment_id)
        ->where('id', '!=', $selectedAssignment->id)
        ->get();

        $results = [];

        foreach ($otherAssignments as $other) {
            $otherFilePath = storage_path('app/' . str_replace('storage/', 'public/', $other->file_path));
            Log::info('Comparing with: ' . $otherFilePath);
        
            $similarity = floatval($this->runPythonComparison($selectedFilePath, $otherFilePath));
            $originality = round(100 - $similarity, 2);

            $studentName = Student::find($other->student_id)->username ?? 'N/A';
        
            $results[] = [
                'student_name' => $studentName,
                'file_name' => basename($other->file_path),
                'file_path' => $other->file_path,
                'similarity' => $similarity,
                'originality' => $originality,
            ];
        }
        $totalSimilarity = round(collect($results)->avg('similarity'), 2);
        
        usort($results, fn($a, $b) => $b['similarity'] <=> $a['similarity']);
        
        session([
            'selected_similarity' => $totalSimilarity,
            'selected_originality' => round(100 - $totalSimilarity, 2),
            'results' => $results,
            'showModal' => true,

            'studentName' => $OriginalStudentName,
            'studentEmail' => $OriginalStudentEmail,
            'studentId' => $OriginalStudentId,
            'course' => 'Final Year Project',
            'assignmentTitle' => 'AI Based Plagiarism Detector',
            'reportId' => 12,
            'assignmentId' => $selectedAssignment->assignment_id,
        ]);
        
        ActivityLog::create([
            'activity_type' => 'plagiarism_checked',
            'description' => 'Plagiarism check run for student "' . $OriginalStudentName . '" (ID: ' . $OriginalStudentId . ') with average similarity score of ' . $totalSimilarity . '%. Compared against ' . count($results) . ' other submissions on ' . now()->format('F j, Y, g:i A') . '.',
            'student_id' => $selectedAssignment->student_id,
        ]);

        return redirect()->route('teacher.plagiarism.report');
}

private function runPythonComparison($file1, $file2)
{
    set_time_limit(300);
    $output = [];
    $returnCode = null;

    $scriptPath = base_path('storage/app/scripts/text_check.py');
    $command = 'python ' . escapeshellarg($scriptPath) . ' ' . escapeshellarg($file1) . ' ' . escapeshellarg($file2) . ' 2>&1';
    
    exec($command, $output, $returnCode);
    foreach ($output as $line) {
        Log::info('[Python Output] ' . $line);
    }

    return end($output) ?: '0';
}

public function checkClassPlag(Request $request)
{
    $assignmentId = $request->input('id');
    \Log::info('Assignment ID: ' . $assignmentId);

    $submissions = StudentAssignments::where('assignment_id', $assignmentId)
        ->with('student')
        ->get();

    if ($submissions->count() < 2) {
        return response()->json(['message' => 'Not enough submissions to compare.'], 400);
    }

    $filePaths = [];
    $fileMap = [];

    foreach ($submissions as $submission) {
        $file = storage_path('app/' . str_replace('storage/', 'public/', $submission->file_path));
        if (file_exists($file)) {
            $filePaths[] = $file;
            $fileMap[basename($file)] = $submission->student->username ?? 'N/A';
        }
    }

    if (count($filePaths) < 2) {
        return response()->json(['message' => 'Not enough valid files for comparison.'], 400);
    }

    $scriptPath = base_path('storage/app/scripts/text_clustering.py'); // Update if named differently
    $command = 'python ' . escapeshellarg($scriptPath);
    foreach ($filePaths as $filePath) {
        $command .= ' ' . escapeshellarg($filePath);
    }

    $output = [];
    $returnCode = null;
    exec($command, $output, $returnCode);

    if ($returnCode !== 0) {
        return response()->json(['message' => 'Clustering script failed.'], 500);
    }

    $results = json_decode(implode('', $output), true);

foreach ($results as &$entry) {
    $entry['student_name'] = $fileMap[$entry['file_name']] ?? $entry['student_name'] ?? 'Unknown';
}
    session([
        'classClusters' => $results,
    ]);    
    $teacherId = Auth::guard('teacher')->id();
$assignment = Assignment::find($assignmentId);

ActivityLog::create([
    'activity_type' => 'class_plagiarism_checked',
    'description' => 'Class-level plagiarism clustering executed for assignment "' . ($assignment->name ?? 'Unknown') . '" involving ' . count($filePaths) . ' student submissions on ' . now()->format('F j, Y, g:i A') . '.',
    'teacher_id' => $teacherId,
]);
    return redirect()->route('teacher.plagiarism.class.report');

    // return response()->json($results);
}

}