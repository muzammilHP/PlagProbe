<?php

namespace App\Http\Controllers;
use App\Http\Middleware\StudentMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Teacher;
use App\Models\Assignment;
use App\Models\Student;
use App\Models\Admin;
use App\Models\Classroom;
use App\Models\StudentClass;
use App\Mail\StudentLoggedInMail;
use App\Mail\TeacherLoggedInMail;
use App\Mail\StudentEmailVerification;
use App\Mail\TeacherEmailVerification;
use App\Mail\StudentSignedupMail;
use App\Mail\TeacherSignedupMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Show the student signup page.
     */
    public function studentSignup()
    {
        return view('Auth.studentSignup');
    }

    /**
     * Show the student login page.
     */
    public function studentLogin()
    {
        return view('Auth.studentLogin');
    }

    /**
     * Show the teacher signup page.
     */
    public function teacherSignup()
    {
        return view('Auth.teacherSignup');
    }

    /**
     * Show the teacher login page.
     */
    public function teacherLogin()
    {
        return view('Auth.teacherLogin');
    }

    /**
     * Store student information and register them.
     */
    public function studentStore(Request $request)
    {
        $request->validate([
            'username' => 'required|regex:/^[a-zA-Z0-9]+$/',
            'email' => 'required|email|unique:students,email',
            'contact' => 'required|numeric',
            'password' => 'required|min:8|confirmed',
        ], [
            'username.regex' => 'Username must contain only numbers and alphabets.',
            'email.email' => 'Email must be a valid email address.',
            'contact.numeric' => 'Contact must contain only numbers.',
        ]);

        $student = new Student();
        $student->username = $request->username;
        $student->email = $request->email;
        $student->contact = $request->contact;
        $student->password = Hash::make($request->password);
        $student->role = 'student';

        $student->email_verification_token = Str::random(64);

        $student->save();

        Mail::to($student->email)->send(new StudentEmailVerification($student));

        return redirect()->route('student.login')->with('success', 'Student registered successfully. Please log in');
    }

    public function checkStudentEmail(Request $request)
    {
        $exists = Student::where('email', $request->email)->exists();
        return response()->json(['exists' => $exists]);
    }

public function studentAuthenticate(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // if (auth('teacher')->check()) {
    //     auth('teacher')->logout();
    // }

    $student = Student::where('email', $request->email)->first();

    if ($student && Hash::check($request->password, $student->password)) {
        // Log the user in with the 'student' guard
        if (!$student->email_verified_at) {
            return redirect()->route('student.login')->with('error', 'Your email is not verified. Please check your email');
        }

        Auth::guard('student')->login($student);

        Mail::to($student->email)->send(new StudentLoggedInMail($student));

        // Debugging logs to confirm
        Log::info('User Logged In: '   . $student->email);

        // Redirect to the student panel
        return redirect()->route('studentpanel');
    }

    // If authentication fails, redirect back with an error
    Log::info('Authentication Failed for: ' . $request->email);
    return redirect()->route('student.login')->with('error', 'Invalid credentials');
}
    /**
     * Store teacher information and register them.
     */
    public function studentLogout(Request $request)
    {

        Auth::guard('student')->logout();
    $request->session()->forget('student_logged_in');
    
    $request->session()->regenerateToken();

        return redirect()->route('student.login')->with('success', 'You have successfully logged out');
    }



    public function teacherStore(Request $request)
    {
        $request->validate([
            'username' => 'required|regex:/^[a-zA-Z0-9]+$/',
            'email' => 'required|email|unique:teachers,email',
            'contact' => 'required|numeric',
            'password' => 'required|min:8|confirmed',
        ], [
            'username.regex' => 'Username must contain only numbers and alphabets.',
            'email.email' => 'Email must be a valid email address.',
            'contact.numeric' => 'Contact must contain only numbers.',
        ]);

        $teacher = new Teacher();
        $teacher->username = $request->username;
        $teacher->email = $request->email;
        $teacher->contact = $request->contact;
        $teacher->password = Hash::make($request->password);

        $teacher->email_verification_token = Str::random(64);
        $teacher->save();

        Mail::to($teacher->email)->send(new TeacherEmailVerification($teacher));

        return redirect()->route('teacher.login')->with('success', 'Teacher registered successfully. Please log in');
    }
    public function checkTeacherEmail(Request $request)
{
    $exists = Teacher::where('email', $request->email)->exists();
    return response()->json(['exists' => $exists]);
}

    /**
     * Authenticate the teacher and log them in.
     */
    public function teacherAuthenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // if (auth('student')->check()) {
        //     auth('student')->logout();
        // }

        $teacher = Teacher::where('email', $request->email)->first();

        if ($teacher && Hash::check($request->password, $teacher->password)) {

            if (!$teacher->email_verified_at) {
                return redirect()->route('teacher.login')->with('error', 'Your email is not verified. Please check your email');
            }
            Auth::guard('teacher')->login($teacher);

            Mail::to($teacher->email)->send(new TeacherLoggedInMail($teacher));

            return redirect()->route('teacherpanel');
        }

        return redirect()->route('teacher.login')->with('error', 'Invalid credentials');
    }

    public function teacherLogout(Request $request)
    {
    Auth::guard('teacher')->logout();
    $request->session()->forget('teacher_logged_in');
    $request->session()->regenerateToken();
    return redirect()->route('teacher.login')->with('success', 'You have successfully logged out');
    }

    public function Profile() {
        if (auth('student')->check()) {
            $user = auth('student')->user();
            $userType = 'student';
        } 
        
        else {
            $user = auth('teacher')->user();
            $userType = 'teacher';
        }
    
        return view('Auth.profile', compact('user', 'userType'));
    }
    
    public function updateProfile(Request $request) {

        if (auth('student')->check()) {
            $user = auth('student')->user();
        } 
        else {
            $user = auth('teacher')->user();
        }
    
        $request->validate([
            'contact' => 'numeric',
            'username' => 'required|regex:/^[a-zA-z0-9]+$/',
            'email' => 'email',
        ],[
            'username.regex' => 'Username must contain only numbers and alphabets.',
            'email.email' => 'Email must be a valid email address.',
            'contact.numeric' => 'Contact must contain only numbers.',
        ]);
    
        $user->username = $request->username;
        $user->email = $request->email;
        $user->contact = $request->contact;
    
        $user->save();
    
        return redirect()->back()->with('success', 'Profile updated successfully');;
    }
    
    public function passwordChange() {
        if (auth('student')->check()) {
            $user = auth('student')->user();
            $userType = 'student';
        } 
        
        else {
            $user = auth('teacher')->user();
            $userType = 'teacher';
        }
    
        return view('Auth.passwordchange', compact('user', 'userType'));
    }

    public function updatePassword(Request $request) {
        $request->validate([
            'newPassword' => 'required|min:8|confirmed',
        ]);
    
        if (auth('student')->check()) {
            $user = auth('student')->user();
            $redirectRoute = 'student.profile';
        } 
        else {
            $user = auth('teacher')->user();
            $redirectRoute = 'teacher.profile';
        }

        $user->password = bcrypt($request->newPassword);
        $user->save();
    
        return redirect()->route($redirectRoute)->with('success', 'Password updated successfully');
    }

public function adminLogin()
{
    return view('Admin.admin-login');
}


public function adminAuthenticate(Request $request)
{
    $request->validate([
        'username' => 'required|string',
        'password' => 'required',
    ]);

    $admin = Admin::where('username', $request->username)->first();

    if ($admin && Hash::check($request->password, $admin->password)) {
        Auth::guard('admin')->login($admin);
        return redirect()->route('admin.dashboard')->with('success', 'Welcome to the admin panel');
    }

    return redirect()->route('admin.login')->with('error', 'Invalid credentials');
}

public function adminLogout(Request $request)
{
    Auth::guard('admin')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('admin.login')->with('success', 'You have successfully logged out');
}

public function deleteStudent($id)
{
    $student = Student::find($id);
    if (!$student) {
        return response()->json(['message' => 'Student not found'], 404);
    }

    $student->delete();
    return response()->json(['message' => 'Student deleted successfully']);
}

public function editStudent($id)
{
    $student = Student::find($id);
    if (!$student) {
        return redirect()->back()->with('error', 'Student not found');
    }

    return view('Admin.edit-student', compact('student'));
}

public function deleteTeacher($id)
{
    $teacher = Teacher::find($id);
    if (!$teacher) {
        return response()->json(['message' => 'Teacher not found'], 404);
    }

    $teacher->delete();
    return response()->json(['message' => 'Teacher deleted successfully']);
}

public function deleteClass($id)
{
    $class = Classroom::find($id);
    if (!$class) {
        return response()->json(['message' => 'Class not found'], 404);
    }

    $class->delete();
    return response()->json(['message' => 'Class deleted successfully']);
}

public function deleteReport($id)
{
    $report = Report::find($id);
    if (!$report) {
        return response()->json(['message' => 'Report not found'], 404);
    }

    if (Storage::exists($report->file_path)) {
        Storage::delete($report->file_path);
    }

    $report->delete();
    return response()->json(['message' => 'Report deleted successfully']);
}
}
