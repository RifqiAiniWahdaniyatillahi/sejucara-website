<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserActivityNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $title,
        private readonly string $message,
        private readonly string $url
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->title.' - Sejucara')
            ->greeting('Halo, '.$notifiable->name)
            ->line($this->message)
            ->line('Silakan buka website Sejucara untuk melihat detail informasi terbaru.')
            ->action('Lihat Detail', $this->url)
            ->line('Terima kasih sudah menggunakan layanan Sejucara.');
    }
}
