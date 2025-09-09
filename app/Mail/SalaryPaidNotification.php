<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\SalaryPayment;

class SalaryPaidNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;

    public function __construct(SalaryPayment $payment)
    {
        $this->payment = $payment;
    }

    public function build()
    {
        return $this->subject("Pagamento de Salário {$this->payment->paymentStatus}")
                    ->view('emails.salaryPaidNotification');
    }
}
