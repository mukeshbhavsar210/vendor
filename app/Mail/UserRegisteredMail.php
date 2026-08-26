<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserRegisteredMail extends Mailable {
    use Queueable, SerializesModels;

    public $user;
    public $plainPassword;

    public function __construct($user, $plainPassword) {
        $this->user = $user;
        $this->plainPassword = $plainPassword;
    }

    public function build() {
        $profileUrl = url('https://testing.amdavadproperty.in/account/profile');
        $yourOrder = url('https://testing.amdavadproperty.in/account/orders');

        return $this->subject('Welcome to  - Your Account Details')
                    ->view('emails.user_registered')
                    ->with([
                        'user' => $this->user,
                        'plainPassword' => $this->plainPassword,
                        'profileUrl' => $profileUrl,
                        'yourOrder' => $yourOrder,
                    ]);
    }
}