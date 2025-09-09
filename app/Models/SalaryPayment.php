<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'employeeId',
        'baseSalary',
        'subsidies',
        'workMonth', 
        'irtRate',
        'inssRate',
        'discount',
        'salaryAmount',
        'paymentDate',
        'paymentStatus',
        'paymentComment',
    ];

    public function employee()
    {
        return $this->belongsTo(Employeee::class, 'employeeId');
    }
}
