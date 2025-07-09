<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SellerApplicationStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $status;
    protected $reason;

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
            return $this->buildApprovedEmail();
        } else {
            return $this->buildRejectedEmail();
        }
    }

    protected function buildApprovedEmail()
    {
        return (new MailMessage)
            ->subject('🎉 Your UTMThrift Seller Application Has Been Approved!')
            ->greeting('Congratulations!')
            ->line('We are pleased to inform you that your application to become a seller on UTMThrift has been approved.')
            ->line('You can now:')
            ->line('✅ List your items for sale')
            ->line('✅ Manage your inventory')
            ->line('If you have any questions, our support team is happy to help.')
            ->salutation('Welcome to the UTMThrift Seller Community!');
    }

    protected function buildRejectedEmail()
    {
        $mail = (new MailMessage)
            ->subject('Your UTMThrift Seller Application Status')
            ->greeting('Hello,')
            ->line('Thank you for your interest in becoming a UTMThrift seller.')
            ->line('After careful review, we regret to inform you that your application has not been approved at this time.');

        if ($this->reason) {
            $mail->line('**Reason for rejection:**')
                 ->line($this->reason)
                 ->line('');
        }

        $mail->line('You may:')
            ->line('🔄 Reapply in the future')
            ->line('📧 Contact us if you believe this was a mistake')
            ->action('Contact Support', url('/contact'))
            ->line('We appreciate your understanding and encourage you to try again in the future.')
            ->salutation('Best regards,<br>The UTMThrift Team');

        return $mail;
    }
}