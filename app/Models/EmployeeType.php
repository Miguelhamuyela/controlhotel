<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'paymentDelayDays', //campo para controle do atraso dos dias entre o Efetivo e o Contratado(meramente opcional)
    ];
}
