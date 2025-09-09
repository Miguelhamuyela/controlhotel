<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\VacationRequest;

class VacationResponseNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $vacation;

    public function __construct(VacationRequest $vacation)
    {
        $this->vacation = $vacation;
    }
    

    public function build()
    {
        return $this->subject('Resposta ao Pedido de Férias')
                    ->view('emails.vacationResponseNotification');
    }
}
