<?php

namespace App\Mail;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudentEmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $verificationUrl;

    public function __construct(Student $student)
    {
        $this->student = $student;
        // Generate the verification URL with the email verification token
        $this->verificationUrl = route('verify.studentEmail', ['token' => $student->email_verification_token]);
    }

    public function build()
    {
        return $this->view('Email.verifyStudent')
                    ->with([
                        'verificationUrl' => $this->verificationUrl,
                        'studentName' => $this->student->username,
                    ]);
    }
}