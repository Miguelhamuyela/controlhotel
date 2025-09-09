<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\LeaveRequest;

class LeaveResponseNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $leave;

    public function __construct(LeaveRequest $leave)
    {
        $this->leave = $leave;
    }

    public function build()
    {
        return $this->subject('Resposta ao Pedido de Licença')
                    ->view('emails.leaveResponseNotification');
    }
}
