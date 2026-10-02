<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    public function __construct(
        public readonly string $url,
        public readonly int $expiresInMinutes,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reset your '.config('app.name').' password')
            ->line('We received a request to reset the password for your account.')
            ->action('Reset password', $this->url)
            ->line("This link expires in {$this->expiresInMinutes} minutes.")
            ->line('If you did not ask for this, you can ignore this e-mail.');
    }
}
