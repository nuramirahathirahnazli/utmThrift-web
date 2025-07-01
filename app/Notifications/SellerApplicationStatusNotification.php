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

    public function __construct($status, $reason = null)
    {
        $this->status = $status;
        $this->reason = $reason;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        if ($this->status === 'approved') {
            return (new MailMessage)
                ->subject('Seller Application Approved')
                ->line('🎉 Congratulations! Your application to become a seller has been approved.')
                ->line('You can now start listing your items on UTMThrift.')
                ->line('Thank you for using UTMThrift!');
        } else {
            return (new MailMessage)
                ->subject('Seller Application Rejected')
                ->line('❌ Unfortunately, your application to become a seller has been rejected.')
                ->when($this->reason, fn($mail) => $mail->line('Reason: ' . $this->reason))
                ->line('If you believe this was a mistake, please contact admin.')
                ->line('Thank you for using UTMThrift.');
        }
    }

}
