<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Retirement;

class RetirementResponseNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $retirement;

    public function __construct(Retirement $retirement)
    {
        $this->retirement = $retirement;
    }

    public function build()
    {
        return $this->subject('Resposta ao Pedido de Reforma')
                    ->view('emails.retirementResponseNotification');
    }
}
