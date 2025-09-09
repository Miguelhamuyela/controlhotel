<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class VehicleDriver extends Pivot
{
    protected $table = 'vehicle_driver';

    protected $fillable = [
        'vehicleId',
        'driverId',
        'startDate',
        'endDate',
    ];
}
