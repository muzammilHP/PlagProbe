<?php

use Illuminate\Support\Facades\Route;
use App\Models\Student;
use App\Models\Teacher;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TextAssignmentController;
use App\Http\Controllers\HandwrittenAssignmentController;
use App\Http\Controllers\PptAssignmentController;
use App\Http\Controllers\CodingAssignmentController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\ReportController;


Route::controller(AuthController::class)->group(function(){
    Route::get('student-signup', 'studentSignup')->name('student.signup');
    Route::post('student-store', 'studentStore')->name('student.store');
    Route::get('student-login', 'studentLogin')->name('student.login');
    Route::post('student-authenticate', 'studentAuthenticate')->name('student.authenticate');
    Route::get('check-studentemail', 'checkStudentEmail')->name('check.student.email');
});

Route::controller(AuthController::class)->group(function(){
    Route::get('teacher-signup', 'teacherSignup')->name('teacher.signup');
    Route::post('teacher-store', 'teacherStore')->name('teacher.store');
    Route::get('teacher-login', 'teacherLogin')->name('teacher.login');
    Route::post('teacher-authenticate', 'teacherAuthenticate')->name('teacher.authenticate');
    Route::get('check-teacheremail', 'checkTeacherEmail')->name('check.teacher.email');

});


Route::get('/', [TradeController::class, 'homepage'])->name('homepage');
Route::get('mainpage', [TradeController::class, 'mainpage'])->name('mainpage');


Route::middleware('teacher')->group(function(){
    Route::get('teacherpanel', [TradeController::class, 'teacherPanel'])->name('teacherpanel');
    Route::post('taecher-logout', [AuthController::class, 'teacherLogout'])->name('teacher.logout');

    Route::get('teacher-profile', [AuthController::class, 'Profile'])->name('teacher.profile');
    Route::get('teacher-passwordchange', [AuthController::class, 'passwordChange'])->name('teacher.passwordchange');
    Route::post('teacher-updatepassword', [AuthController::class, 'updatePassword'])->name('teacher.updatepassword');
    Route::post('teacher-updateprofile', [AuthController::class, 'updateProfile'])->name('teacher.updateprofile');
   

    Route::get('/get-classes', [ClassroomController::class, 'getClasses']);
    Route::delete('/delete-class/{id}', [ClassroomController::class, 'deleteClass']);
    Route::post('/create-class', [ClassroomController::class, 'createClass'])->name('create-class');

    Route::post('/create-assignment', [AssignmentController::class, 'createAssignment'])->name('create-assignment');
    Route::get('/get-assignments/{classCode}', [AssignmentController::class, 'getAssignments'])->name('get-assignments');
    Route::get('/get-teacher-assignments', [AssignmentController::class, 'getTeacherAssignments'])->name('get-teacher-assignments');
    Route::delete('/delete-assignment/{id}', [AssignmentController::class, 'deleteAssignment'])->name('delete-assignment');
    Route::get('/get-submitted-assignments/{classCode}/{assignmentId}', [AssignmentController::class, 'getSubmittedAssignments']);
    Route::get('/get-all-submitted-assignments/{assignmentId}', [AssignmentController::class, 'getAllSubmittedAssignments'])->name('get-all-submitted-assignments');

    Route::post('/textassignment/check-plag', [TextAssignmentController::class, 'checkPlag'])->name('textassignment.checkPlag');
    Route::post('/handassignment/check-plag', [HandwrittenAssignmentController::class, 'checkPlag'])->name('handassignment.checkPlag');
    Route::post('/pptassignment/check-plag', [PptAssignmentController::class, 'checkPlag'])->name('pptassignment.checkPlag');
    Route::post('/codingassignment/check-plag', [CodingAssignmentController::class, 'checkPlag'])->name('codingassignment.checkPlag');

    Route::post('/textassignment/class-plag', [TextAssignmentController::class, 'checkClassPlag'])->name('textassignment.classPlag');
    Route::post('/handassignment/class-plag', [HandwrittenAssignmentController::class, 'checkClassPlag'])->name('handassignment.classPlag');
    Route::post('/pptassignment/class-plag', [PptAssignmentController::class, 'checkClassPlag'])->name('pptassignment.classPlag');
    Route::post('/codingassignment/class-plag', [CodingAssignmentController::class, 'checkClassPlag'])->name('codingassignment.classPlag');

    Route::get('/plagiarism-report', [ReportController::class, 'plagReport'])->name('teacher.plagiarism.report');
    Route::get('/class-plagiarism-report', [ReportController::class, 'classPlagReport'])->name('teacher.plagiarism.class.report');
    Route::post('/send-report', [ReportController::class, 'sendEmail'])->name('report.sendEmail');
    Route::post('/download-report', [ReportController::class, 'downloadReport'])->name('report.download');
    Route::post('/save-report', [ReportController::class, 'saveReport'])->name('report.save');
    Route::delete('/delete-report/{id}', [ReportController::class, 'deleteReport'])->name('delete.report');
    Route::get('/view-report', [ReportController::class, 'viewReport'])->name('view.report');


    Route::get('/plagiarism-chart', [TradeController::class, 'generatePlagiarismReport'])->name('teacher.piechart');
});


Route::middleware('student')->group(function () {
    Route::get('studentpanel', [TradeController::class, 'studentpanel'])->name('studentpanel');
    Route::post('upload-text-assignment', [TextAssignmentController::class, 'uploadText']);
    Route::get('/get-text-uploaded-files', [TextAssignmentController::class, 'getUploadedFiles'])->name('get.uploaded.textfiles');
    Route::delete('/delete-text-file/{fileId}', [TextAssignmentController::class, 'deleteFile'])->name('delete.textfile');
    Route::post('check-text-plagiarism', [TextAssignmentController::class, 'checkPlagiarism']);

    Route::post('upload-handwritten-assignment', [HandwrittenAssignmentController::class, 'uploadHandwritten']);
    Route::get('/get-hand-uploaded-files', [HandwrittenAssignmentController::class, 'getUploadedFiles'])->name('get.uploaded.handfiles');
    Route::delete('/delete-hand-file/{fileId}', [HandwrittenAssignmentController::class, 'deleteFile'])->name('delete.handfile');
    Route::post('check-hand-plagiarism', [HandwrittenAssignmentController::class, 'checkPlagiarism']);
    Route::post('student-logout', [AuthController::class, 'studentLogout'])->name('student.logout');
    
    Route::get('student-profile', [AuthController::class, 'Profile'])->name('student.profile');
    Route::get('student-passwordchange', [AuthController::class, 'passwordChange'])->name('student.passwordchange');
    Route::post('student-updatepassword', [AuthController::class, 'updatePassword'])->name('student.updatepassword');
    Route::post('student-updateprofile', [AuthController::class, 'updateProfile'])->name('student.updateprofile');

    Route::post('/join-class', [ClassroomController::class, 'joinClass']);
    Route::get('/get-student-classes', [ClassroomController::class, 'getStudentClasses'])->name('get-student-classes');
    Route::delete('/delete-student-class/{id}', [ClassroomController::class, 'deleteStudentClass']);

    Route::get('/get-student-assignments/{classCode}', [AssignmentController::class, 'getStudentAssignments'])->name('get-student-assignments');

    Route::post('/upload-assignment', [AssignmentController::class, 'uploadAssignment']);
    Route::get('/get-pending-assignments', [AssignmentController::class, 'getPendingAssignments']);

    Route::get('/uploaded-history', [AssignmentController::class, 'getUploadedHistory']);


});

Route::get('/admin-login', [AuthController::class, 'adminLogin'])->name('admin.login');
Route::post('/admin-authenticate', [AuthController::class, 'adminAuthenticate'])->name('admin.authenticate');
Route::post('/admin-logout', [AuthController::class, 'adminLogout'])->name('admin.logout');
Route::get('/admin-dashboard', [TradeController::class, 'adminDashboard'])->name('admin.dashboard')->middleware('admin');
Route::delete('/admin/students/{id}', [AuthController::class, 'deleteStudent'])->name('admin.students.delete');
Route::get('/admin/students/{id}/edit', [AuthController::class, 'editStudent'])->name('admin.students.edit');
Route::delete('/admin/teachers/{id}', [AuthController::class, 'deleteTeacher'])->name('admin.teachers.delete');
Route::delete('/admin/classes/{id}', [AdminController::class, 'deleteClass'])->name('admin.classes.delete');
Route::delete('/admin/reports/{id}', [AdminController::class, 'deleteReport'])->name('admin.reports.delete');


Route::get('/verify-student-email/{token}', function ($token) {
    $student = Student::where('email_verification_token', $token)->first();

    if ($student) {
        $student->email_verified_at = now();
        $student->email_verification_token = null; 
        $student->save();

        return redirect()->route('student.login')->with('success', 'Your email has been verified successfully!');
    }

    return redirect()->route('student.login')->with('error', 'Invalid or expired verification link.');
})->name('verify.studentEmail');

Route::get('/verify-teacher-email/{token}', function ($token) {
    $teacher = Teacher::where('email_verification_token', $token)->first();

    if ($teacher) {
        $teacher->email_verified_at = now();
        $teacher->email_verification_token = null; 
        $teacher->save();

        return redirect()->route('teacher.login')->with('success', 'Your email has been verified successfully!');
    }

    return redirect()->route('teacher.login')->with('error', 'Invalid or expired verification link.');
})->name('verify.teacherEmail');

Route::get('/view-assignment/{filename}', function ($filename) {
    $path = storage_path("app/assignments/Assignment 1/" . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
});

Route::get('/email-report', function () {
    return view('Main.emailreport');
});
Route::get('/admin', function () {
    return view('Main.admin_template');
});







