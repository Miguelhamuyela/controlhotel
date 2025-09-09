<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Employeee;
use App\Models\Department;

class NewMobilityNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $employee;
    public $oldDepartment;
    public $newDepartment;
    public $causeOfMobility;

    public function __construct(Employeee $employee, Department $oldDepartment = null, Department $newDepartment, $causeOfMobility = '')
    {
        $this->employee = $employee;
        $this->oldDepartment = $oldDepartment;
        $this->newDepartment = $newDepartment;
        $this->causeOfMobility = $causeOfMobility;
    }

    public function build()
    {
        return $this->subject('Notificação de Mobilidade - RH-INFOSI')
                    ->view('emails.newMobilityNotification');
    }
}
