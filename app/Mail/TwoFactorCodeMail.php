<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TwoFactorCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $verify_code;

    public function __construct($verify_code)
    {
        $this->verify_code = $verify_code;
    }

    public function build()
    {
        return $this->subject('Your Two-Factor Authentication Code')
                    ->from('two-factor-auth@test.com')
                        ->view('verify-2fcode', ['code' => $this->verify_code]);
    }
}
