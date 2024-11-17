<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\TwoFactorCodeMail;


class TwoFactorCodeNotification extends Notification
{
    use Queueable;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }


    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new TwoFactorCodeMail($this->user));
    }
}
