<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Employeee;

class NewSecondmentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $employee;
    public $institution;
    public $causeOfTransfer;

    public function __construct(Employeee $employee, $institution, $causeOfTransfer = '')
    {
        $this->employee = $employee;
        $this->institution = $institution;
        $this->causeOfTransfer = $causeOfTransfer;
    }

    public function build()
    {
        return $this->subject('Notificação de Destacamento - RH-INFOSI')
                    ->view('emails.newSecondmentNotification');
    }
}
