<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $resetLink; 

    /**
     * Create a new message instance.
     *
     * @param string $resetLink
     * @return void
     */
    public function __construct(string $resetLink)
    {
        $this->resetLink = $resetLink; 
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.password_reset') 
                    ->with([
                        'resetLink' => $this->resetLink, 
                    ])
                    ->subject('Réinitialisation de votre mot de passe'); 
    }
}