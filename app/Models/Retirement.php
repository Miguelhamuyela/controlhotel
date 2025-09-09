<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'employeeId',
        'requestDate',
        'retirementDate',
        'status',
        'observations'
    ];

    public function employee()
    {
        return $this->belongsTo(Employeee::class, 'employeeId');
    }
}
