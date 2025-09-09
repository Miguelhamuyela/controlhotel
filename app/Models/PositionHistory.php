<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PositionHistory extends Model
{
    protected $fillable = [
        'employeeId',
        'positionId',
        'startDate',
        'endDate',
    ];

    public function employee()
    {
        return $this->belongsTo(Employeee::class, 'employeeId');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'positionId');
    }
}