<?php

namespace App\Http\Controllers;

use App\Models\StudentAssignments;
use App\Models\Student;
use App\Models\ActivityLog;
use App\Models\Assignment;
use App\Models\Teacher;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use thiagoalessio\TesseractOCR\TesseractOCR;
use PhpOffice\PhpWord\IOFactory;
use Smalot\PdfParser\Parser as PdfParser;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class HandwrittenAssignmentController extends Controller
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
        $selectedText = $this->extractTextFromFile($selectedFilePath);

        Log::info('Selected file path: ' . $selectedFilePath);

        $otherAssignments = StudentAssignments::where('assignment_id', $selectedAssignment->assignment_id)
            ->where('id', '!=', $selectedAssignment->id)
            ->get();

        $results = [];

        foreach ($otherAssignments as $other) {
            $otherFilePath = storage_path('app/' . str_replace('storage/', 'public/', $other->file_path));
            Log::info('Comparing with: ' . $otherFilePath);

            $otherText = $this->extractTextFromFile($otherFilePath);

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

    public function checkClassPlag(Request $request)
    {
        $assignmentId = $request->input('id');
        \Log::info('Assignment ID: ' . $assignmentId);

        $submissions = StudentAssignments::where('assignment_id', $assignmentId)->get();

        if ($submissions->count() < 2) {
            return back()->with('error', 'Not enough submissions to perform class-level plagiarism check.');
        }

        $fileTexts = [];
        $fileNames = [];
        $fileMap = [];

        foreach ($submissions as $submission) {
            $filePath = storage_path('app/' . str_replace('storage/', 'public/', $submission->file_path));
            $fileTexts[] = $this->extractTextFromFile($filePath);
            $fileNames[] = basename($submission->file_path);
            $fileMap[basename($submission->file_path)] = $submission->student->username;
        }

        if (count($fileTexts) < 2) {
            return back()->with('error', 'Not enough valid files to perform class-level plagiarism check.');
        }

        $scriptPath = base_path('storage/app/scripts/hand_clustering.py');
        set_time_limit(300);
        $inputData = json_encode(['texts' => $fileTexts, 'file_names' => $fileNames]);
        $command = "python " . escapeshellarg($scriptPath);

        $output = [];
        $returnCode = null;

        $descriptorspec = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'], 
            2 => ['pipe', 'w'], 
        ];

        $process = proc_open($command, $descriptorspec, $pipes);

        if (is_resource($process)) {
            fwrite($pipes[0], $inputData);
            fclose($pipes[0]);

            $output = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            $errorOutput = stream_get_contents($pipes[2]);
            fclose($pipes[2]);

            $returnCode = proc_close($process);

            if ($returnCode !== 0) {
                \Log::error('Python script error: ' . $errorOutput);
                return back()->with('error', 'Error occurred while performing class-level plagiarism check.');
            }
        }

        $results = json_decode($output, true);

        if (isset($results['clusters']) && is_array($results['clusters'])) {
            session(['classClusters' => $results['clusters']]);
        } else {
            \Log::error('Invalid clustering data format:', $results);
            return redirect()->back()->with('error', 'Invalid clustering data format.');
        }

        foreach ($results['clusters'] as &$entry) {
            $fileName = $entry['file_name'] ?? null;
            $entry['student_name'] = $fileName && isset($fileMap[$fileName]) ? $fileMap[$fileName] : 'Unknown';
        }

        $teacherId = Auth::guard('teacher')->id();
        $assignment = Assignment::find($assignmentId);

        ActivityLog::create([
            'activity_type' => 'class_plagiarism_checked',
            'description' => 'Class-level plagiarism clustering executed for assignment "' . ($assignment->name ?? 'Unknown') . '" involving ' . count($fileTexts) . ' student submissions on ' . now()->format('F j, Y, g:i A') . '.',
            'teacher_id' => $teacherId,
        ]);

        return redirect()->route('teacher.plagiarism.class.report');
    }

    private function extractTextFromFile($filePath)
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        try {
            if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                return (new TesseractOCR($filePath))->run();
            } elseif ($extension === 'pdf') {
                $parser = new PdfParser();
                $pdf = $parser->parseFile($filePath);
                return $pdf->getText();
            } elseif ($extension === 'docx') {
                $phpWord = IOFactory::load($filePath);
                $text = '';
                foreach ($phpWord->getSections() as $section) {
                    foreach ($section->getElements() as $element) {
                        if (method_exists($element, 'getText')) {
                            $text .= $element->getText() . ' ';
                        }
                    }
                }
                return $text;
            } else {
                return '';
            }
        } catch (\Exception $e) {
            Log::error("Text extraction failed: " . $e->getMessage());
            return '';
        }
    }

    private function calculateTextSimilarity($text1, $text2)
    {
        try {
            Log::info('Extracted Text 1:', ['text1' => $text1]);
            Log::info('Extracted Text 2:', ['text2' => $text2]);

            $response = Http::timeout(300)
                ->post('http://127.0.0.1:8001/hand_similarity', [
                    'text1' => $text1,
                    'text2' => $text2,
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