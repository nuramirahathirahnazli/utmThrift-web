<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SellerApplicationStatusNotification extends Notification
{
    use Queueable;

    protected $status;

    public function __construct($status)
    {
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $message = $this->status === 'approved'
            ? 'Congratulations! Your application to become a seller has been approved.'
            : 'Sorry, your application to become a seller has been rejected.';

        return (new MailMessage)
                    ->subject('Seller Application Status')
                    ->line($message)
                    ->line('Thank you for using UTMThrift!');
    }
}
