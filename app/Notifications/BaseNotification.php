<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BaseNotification extends Notification
{
    public function via(object $notifiable)
    {
        return ['mail'];
    }

    public function toMail(object $notifiable)
    {
        return (new MailMessage)
            ->markdown('emails.default'); // Path to your default template
    }
}
