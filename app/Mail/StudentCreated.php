<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $student;
    public $tempPassword;
    public $school;

    public function __construct($student, $tempPassword, $school)
    {
        $this->student = $student;
        $this->tempPassword = $tempPassword;
        $this->school = $school;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to ' . $this->school->name . ' - Your Student Account',
        );
    }

    /**
     * Get the message content definition.
     */
    public function build()
    {
        return $this->subject('Welcome to ' . $this->school->name . ' - Your Student Account')
            ->markdown('emails.student_created')
            ->with([
                'student' => $this->student,
                'tempPassword' => $this->tempPassword,
                'school' => $this->school,
            ]);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}


