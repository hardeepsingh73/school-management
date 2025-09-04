<?php

namespace App\Notifications;

use App\Mail\ResetPasswordMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    /** Include the Queueable trait to enable queuing of the notification */
    use Queueable;
    /*     * Create a new notification instance.
     *
     * @param string $token
     */
    public function __construct(public readonly string $token) {}
    /*     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }
    /*     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // Delegate to your custom Mailable
        return (new ResetPasswordMail($notifiable, $this->token))->to($notifiable->email);
    }
}
