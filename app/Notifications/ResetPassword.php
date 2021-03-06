<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

class ResetPassword extends ResetPasswordNotification
{
    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line(__('You are receiving this email because we received a password reset request for your account.'))
            ->action(__('Reset Password'), url(config('app.url') . route('password.reset', $this->token, false)))
            ->line(__('If you did not request a password reset, no further action is required.'));
    }
}
