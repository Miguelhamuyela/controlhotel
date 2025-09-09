<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'plate','model','brand','yearManufacture','color',
        'loadCapacity','totalMileage','lastMaintenanceDate',
        'status','notes'
    ];

    public function maintenance()
    {
        return $this->hasMany(Maintenance::class, 'vehicleId');
    }

    public function drivers()
    {
        return $this->belongsToMany(
            Driver::class,
            'vehicle_driver',
            'vehicleId',
            'driverId'
        )
        ->withPivot('startDate','endDate')
        ->withTimestamps();
    }
}
