<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'employeeId',
        'fullName',
        'bi',
        'licenseNumber',
        'licenseCategoryId',
        'licenseExpiry',
        'status'
    ];

    public function licenseCategory()
    {
        return $this->belongsTo(LicenseCategory::class, 'licenseCategoryId');
    }
    public function employee()
    {
        return $this->belongsTo(Employeee::class, 'employeeId');
    }

    public function vehicles()
    {
        return $this->belongsToMany(
            Vehicle::class,
            'vehicle_driver',
            'driverId',
            'vehicleId'
        )
        ->withPivot('startDate','endDate')
        ->withTimestamps();
    }
}
