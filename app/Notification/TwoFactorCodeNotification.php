<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\TwoFactorCodeMail;


class TwoFactorCodeNotification extends Notification
{
    use Queueable;

    public $code;

    public function __construct($code)
    {
        $this->code = $code;
    }


    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new TwoFactorCodeMail($this->code))
            ->line('Your two factor code is ' . $notifiable->two_factor_code)
            ->action('Verify', route('verify.index'))
            ->line('The code will expire in 10 minutes')
            ->line('If you did not request this, please ignore this email');
    }
}
