<?php

namespace App\Http\Controllers;

use App\Models\StudentAssignments;
use App\Models\Student;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use thiagoalessio\TesseractOCR\TesseractOCR;
use PhpOffice\PhpWord\IOFactory;
use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpPresentation\IOFactory as PresentationIOFactory;
use PhpOffice\PhpPresentation\Shape\RichText;
use Illuminate\Support\Facades\Http;

class PptAssignmentController extends Controller
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

            if (empty($selectedText) || empty($otherText)) {
                Log::warning("One or both extracted texts are empty. Skipping comparison.");
                continue;
            }
            

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

    private function extractTextFromFile($filePath)
{
    try {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        if ($extension !== 'pptx') {
            Log::warning("Unsupported file extension for PPT extraction: " . $extension);
            return '';
        }

        $reader = \PhpOffice\PhpPresentation\IOFactory::createReader('PowerPoint2007');

        if (method_exists($reader, 'setLoadImages')) {
            $reader->setLoadImages(false);
        }

        $presentation = $reader->load($filePath);

        $text = '';

        foreach ($presentation->getAllSlides() as $slide) {
            foreach ($slide->getShapeCollection() as $shape) {
                if ($shape instanceof \PhpOffice\PhpPresentation\Shape\RichText) {
                    $text .= $shape->getPlainText() . ' ';
                }

            }
        }

        return trim($text);
    } catch (\Exception $e) {
        Log::error("Text extraction from PPTX failed: " . $e->getMessage());
        return '';
    }
}

private function calculateTextSimilarity($text1, $text2)
{
    try {

        Log::info('Extracted Text 1:', ['text1' => $text1]);
        Log::info('Extracted Text 2:', ['text2' => $text2]);

        $response = Http::timeout(300)
            ->post('http://127.0.0.1:8001/ppt_similarity', [
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

//     private function calculateTextSimilarity($text1, $text2)
// {
//     set_time_limit(300);

//     $scriptPath = base_path('storage/app/scripts/ppt_check.py');
//     $command = "python $scriptPath";

//     $inputData = json_encode(['text1' => $text1, 'text2' => $text2]);

//     \Log::info('Text1: ' . $text1);
//     \Log::info('Text2: ' . $text2);

//     $descriptorspec = [
//         0 => ['pipe', 'r'], // stdin
//         1 => ['pipe', 'w'], // stdout
//         2 => ['pipe', 'w'], // stderr
//     ];

//     $process = proc_open($command, $descriptorspec, $pipes);

//     if (is_resource($process)) {
//         fwrite($pipes[0], $inputData);
//         fclose($pipes[0]);

//         $output = stream_get_contents($pipes[1]);
//         fclose($pipes[1]);

//         $errorOutput = stream_get_contents($pipes[2]);
//         fclose($pipes[2]);

//         $returnCode = proc_close($process);

//         \Log::info('Python script output: ' . $output);
//         \Log::info('Python return code: ' . $returnCode);

//         if ($returnCode === 0 && is_numeric(trim($output))) {
//             return floatval(trim($output));
//         }

//         \Log::error("Python similarity check failed: " . $errorOutput);
//     }

//     return 0;
// }
}