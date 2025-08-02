<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $payment;
    public $application;

    public function __construct($payment, $application)
    {
        $this->payment = $payment;
        $this->application = $application;
    }

    
    public function build()
    {
        return $this->subject('Payment Confirmation')
            ->html("
            <h2>Payment Successful</h2>
            <p>Dear {$this->application->student->name},</p>
            <p>You have received a payment of <strong>{$this->payment->amount} {$this->payment->currency}</strong> 
            for your work on the job: <strong>{$this->application->jobPost->title}</strong>.</p>
            <p>Payment ID: {$this->payment->payment_intent_id}</p>
            <p>Status: {$this->payment->status}</p>
            <br>
            <p>Thank you,<br>" . config('app.name') . "</p>
        ");
    }
}
