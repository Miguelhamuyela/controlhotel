<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $email;

    /**
     * Cria uma nova instância da mensagem.
     *
     * @param string $token
     * @param string $email
     */
    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    /**
     * Constrói a mensagem do e-mail.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Redefinir Senha - RH-INFOSI')
                    ->view('emails.resetPasswordNotification');
    }
}
