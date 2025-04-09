<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminPasswordMail extends Mailable
{
    use SerializesModels;

    public $adminName;
    public $password;
    public $domain;
    public $email;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($adminName, $password, $domain, $email)
    {
        $this->adminName = $adminName;
        $this->password = $password;
        $this->domain = $domain;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your Admin Password')
                    ->view('emails.adminPassword')
                    ->with([
                        'adminName' => $this->adminName,
                        'password' => $this->password,
                        'domain' => $this->domain,
                        'email' => $this->email,
                    ]);
    }
}
