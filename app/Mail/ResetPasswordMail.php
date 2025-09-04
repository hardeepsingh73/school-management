<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    /** Include the Queueable and SerializesModels traits */
    use Queueable, SerializesModels;

    public $user;
    public $token;
    /** 
     * Create a new message instance.
     *
     * @param  mixed  $user
     * @param  string  $token
     * @return void
     */
    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }
    /*     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Reset Your Password')->view('emails.password_reset')->with(['user'  => $this->user, 'token' => $this->token,]);
    }
}
