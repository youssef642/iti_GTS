<?php

namespace App\Mail;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $messageContent;

    public function __construct(JobApplication $application)
    {
        $this->application = $application;

        // تخصيص الرسالة حسب الحالة
        if ($application->status === 'accepted') {
            $this->messageContent = "Congratulations! You've been accepted for the job: {$application->jobPost->title}. We'll contact you with further steps.";
        } elseif ($application->status === 'rejected') {
            $this->messageContent = "We regret to inform you that your application for the job: {$application->jobPost->title} was not successful. We wish you the best in your job search.";
        } else {
            $this->messageContent = "Your application status has been updated.";
        }
    }

 public function build()
{
    return $this->subject('Job Application Status Update')
                ->html("
                    <h2>Hello {$this->application->student->name},</h2>
                    <p>{$this->messageContent}</p>
                    <p>Best regards,<br>{$this->application->jobPost->company->name}</p>
                ");
}
}
