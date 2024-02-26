<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Models\VerificationCode;
use App\Notifications\BaseNotification;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendTwoFactorCode extends BaseNotification
{
    use Queueable;

    protected $verificationCode;

    public function __construct(VerificationCode $verificationCode)
    {
        $this->verificationCode = $verificationCode;
    }
    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line("Your two-factor code is {$this->verificationCode->code}")
            ->action('Verify Here', 'asd')
            ->line('The code will expire in 2 minutes')
            ->line('If you didn\'t request this, please ignore.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
