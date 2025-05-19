<?php

namespace App\Mail;

use App\Models\Teacher;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TeacherEmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    public $teacher;
    public $verificationUrl;

    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
        // Generate the verification URL with the email verification token
        $this->verificationUrl = route('verify.teacherEmail', ['token' => $teacher->email_verification_token]);
    }

    public function build()
    {
        return $this->view('Email.verifyTeacher')
                    ->with([
                        'verificationUrl' => $this->verificationUrl,
                        'teacherName' => $this->teacher->username,
                    ]);
    }
}