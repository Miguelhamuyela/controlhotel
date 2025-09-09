<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $table = 'maintenance';

    protected $fillable = [
        'vehicleId',
        'type',
        'maintenanceDate',
        'cost', 
        'invoice_pre',
         'invoice_post',
         'description'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicleId');
    }
}
