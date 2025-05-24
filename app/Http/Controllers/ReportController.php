<?php

namespace App\Http\Controllers;

use App\Models\HandwrittenAssignment;
use App\Models\TextAssignment;
use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

// use Spatie\PdfToImage\Pdf;
use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\IOFactory;
use thiagoalessio\TesseractOCR\TesseractOCR;
use App\Mail\ReportMail;
use Illuminate\Support\Facades\Mail;


class ReportController extends Controller
{
    public function plagReport()
    {
        $results = session('results');
        $showModal = session('showModal', false);
        $selectedSimilarity = session('selected_similarity', 0);
        $selectedOriginality = session('selected_originality', 0);

        $studentName = session('studentName');
        $studentEmail = session('studentEmail');
        $studentId = session('studentId');
        $course = session('course');
        $assignmentTitle = session('assignmentTitle');
        $reportId = session('reportId');
        $assignmentId = session('assignmentId');

        $reportHtml = view('Main.report-template', [
            'studentName' => $studentName,
            'studentId' => $studentId,
            'course' => $course,
            'assignmentTitle' => $assignmentTitle,
            'selectedSimilarity' => $selectedSimilarity,
            'selectedOriginality' => $selectedOriginality,
            'results' => $results,
            'studentEmail' => $studentEmail,
        ])->render();

        return view('Main.plagiarism-result', compact(
            'results',
            'showModal',
            'studentName',
            'studentEmail',
            'studentId',
            'selectedSimilarity',
            'selectedOriginality',
            'course',
            'assignmentTitle',
            'reportId',
            'reportHtml',
            'assignmentId'
        ));
    }


    public function sendEmail(Request $request)
{
    $studentEmail = $request->input('student_email');
    $studentName = $request->input('student_name');
    $htmlReport = base64_decode($request->input('report_data'));
    
    if (!$studentEmail) {
        return back()->with('error', 'Student email not provided.');
    }
    
    Mail::to($request->input('student_email'))->send(new ReportMail($htmlReport));

    return back()->with('success', 'Report shared with student via email!');
}

    public function classPlagReport()
{
    $clusters = session('classClusters');

    \Log::info('Class Clusters Data:', $clusters);

    if (!$clusters) {
        return redirect()->back()->with('error', 'No clustering data found.');
    }

    return view('Main.class-plagiarism-result', compact('clusters'));
}

public function downloadReport(Request $request)
{
    $studentName = Str::slug($request->input('student_name', 'student'));
    $htmlReport = base64_decode($request->input('report_data'));

    $pdf = Pdf::loadHTML($htmlReport);
    
    $fileName = "{$studentName}_report.pdf";


    return $pdf->download($fileName);

}

public function saveReport(Request $request)
{
    $studentName = $request->input('student_name');
    $studentId = $request->input('student_id');
    $studentEmail = $request->input('student_email');
    $htmlReport = base64_decode($request->input('report_data'));
    $assignmentId = $request->input('assignment_id');
    // $existingReport = Report::where('student_email', $studentEmail)
    //                         ->where('assignment_id', $assignmentId)
    //                         ->first();

    // if ($existingReport) {
    //     return back()->with('error', 'A report for this student and assignment already exists.');
    // }

    $fileName = 'plagiarism_reports/report_' . time() . '.html';
    Storage::put($fileName, $htmlReport);

    $teacherId = Auth::guard('teacher')->id();
    $assignment = Assignment::with('classroom')->find($assignmentId);
    $classroom = $assignment->classroom ?? null;

   $courseName = $classroom->course_name ?? 'Unknown';
$section = $classroom->section_name ?? 'Unknown';
\Log::info('Assignment Class Code: ' . $assignment->class_code);
\Log::info('Classroom: ', (array) $classroom);

\Log::info('Assignment ID from request: ' . $assignmentId);

    Report::create([
        'teacher_id' => $teacherId,
        'student_id' => $studentId,
        'student_name' => $studentName,
        'student_email' => $studentEmail,
        'file_path' => $fileName,
        'course_name' => $courseName,
        'assignment_id' => $assignmentId,
        'section' => $section,
    ]);

    return back()->with('success', 'Report saved successfully!')
    ->with('saved_assignment_id', $assignmentId)
    ->with('saved_student_email', $studentEmail);
}

public function viewReport(Request $request)
{
        $path = $request->query('path');

        if (Storage::exists($path)) {
            $reportHtml = Storage::get($path);
            return response($reportHtml)->header('Content-Type', 'text/html');
        }

        abort(404, 'Report not found.');
}

public function deleteReport($id)
{
    $report = Report::findOrFail($id);

    if ($report->teacher_id !== Auth::guard('teacher')->id()) {
        abort(403, 'Unauthorized action.');
    }

    if (Storage::exists($report->file_path)) {
        Storage::delete($report->file_path);
    }


    $report->delete();

    return back()->with('success', 'Report deleted successfully!');
}
}
