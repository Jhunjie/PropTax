<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $status)
    {
        //
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(User $notifiable): MailMessage
    {
        if ($this->status === 'approved') {
            return (new MailMessage)
                ->subject('Your account has been approved')
                ->greeting("Hi {$notifiable->name},")
                ->line('Good news — your account has been verified and approved by an administrator.')
                ->line('You can now log in and view the properties linked to your account.')
                ->action('Log in', route('login'));
        }

        return (new MailMessage)
            ->subject('Your account verification was not approved')
            ->greeting("Hi {$notifiable->name},")
            ->line('An administrator reviewed your account and was unable to approve your verification.')
            ->line('Please visit the admin office in person for details and next steps.');
    }
}
