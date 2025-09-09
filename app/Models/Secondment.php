<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Secondment extends Model
{
    use HasFactory;

    protected $fillable = [
        'employeeId',
        'causeOfTransfer',
        'institution',
        'supportDocument',
        'originalFileName',
    ];

    public function employee()
    {
        return $this->belongsTo(Employeee::class, 'employeeId');
    }
}
