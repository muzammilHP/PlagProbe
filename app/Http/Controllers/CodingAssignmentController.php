<?php

namespace App\Http\Controllers;

use App\Models\StudentAssignments;
use App\Models\Student;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class CodingAssignmentController extends Controller
{
    public function checkPlag(Request $request)
    {
        $assignmentId = $request->input('id');
        $selectedAssignment = StudentAssignments::findOrFail($assignmentId);

        $student = $selectedAssignment->student;

        $OriginalStudentName = $student->username;
        $OriginalStudentEmail = $student->email;
        $OriginalStudentId = $student->student_id;

        $selectedFilePath = storage_path('app/' . str_replace('storage/', 'public/', $selectedAssignment->file_path));
        $selectedText = $this->extractCodeFromFile($selectedFilePath);

        Log::info('Selected file path: ' . $selectedFilePath);

        $otherAssignments = StudentAssignments::where('assignment_id', $selectedAssignment->assignment_id)
            ->where('id', '!=', $selectedAssignment->id)
            ->get();

        $results = [];

        foreach ($otherAssignments as $other) {
            $otherFilePath = storage_path('app/' . str_replace('storage/', 'public/', $other->file_path));
            Log::info('Comparing with: ' . $otherFilePath);

            $otherText = $this->extractCodeFromFile($otherFilePath);

            $similarity = $this->calculateTextSimilarity($selectedText, $otherText);
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
            'reportId' => 13,
        ]);

        ActivityLog::create([
            'activity_type' => 'plagiarism_checked',
            'description' => 'Plagiarism check run for student "' . $OriginalStudentName . '" (ID: ' . $OriginalStudentId . ') with average similarity score of ' . $totalSimilarity . '%. Compared against ' . count($results) . ' other submissions on ' . now()->format('F j, Y, g:i A') . '.',
            'student_id' => $selectedAssignment->student_id,
        ]);

        return redirect()->route('teacher.plagiarism.report');
    }

    private function extractCodeFromFile($filePath)
{
    try {
        return file_get_contents($filePath);
    } catch (\Exception $e) {
        Log::error("Code extraction failed: " . $e->getMessage());
        return '';
    }
}


private function calculateTextSimilarity($text1, $text2)
{
    try {
        Log::info('Extracted Code 1:', ['code1' => $text1]);
        Log::info('Extracted Code 2:', ['code2' => $text2]);

        $response = Http::timeout(300)
            ->post('http://127.0.0.1:8001/check_similarity', [
                'code1' => $text1,
                'code2' => $text2,
            ]);

        Log::info('FastAPI Response Body: ' . $response->body());

        if ($response->successful()) {
            $result = $response->json();

            $similarity = isset($result['similarity_score']) ? floatval($result['similarity_score']) : 0;

            Log::info('Parsed Similarity Score: ' . $similarity);

            return $similarity;
        } else {
            Log::error('FastAPI request failed with status: ' . $response->status());
            Log::error('FastAPI Error Body: ' . $response->body());
        }
    } catch (\Exception $e) {
        Log::error('FastAPI connection exception: ' . $e->getMessage());
    }

    return 0;
}

}