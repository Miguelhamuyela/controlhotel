<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'totalValue',
        'status',
    ];

    /**
     * Participants of this extra job,
     * with pivot bonusAdjustment and assignedValue.
     */
    public function employees()
    {
        return $this->belongsToMany(Employeee::class, 'extra_job_employees', 'extraJobId', 'employeeId')
                    ->withPivot('bonusAdjustment','assignedValue')
                    ->withTimestamps();
    }

    /**
     * Returns the badge color based on the status
     */
    public function getStatusBadgeColorAttribute()
    {
        switch ($this->status) {
            case 'Approved':
                return 'success';
            case 'Rejected':
                return 'danger';
            case 'Pending':
            default:
                return 'warning';
        }
    }

    /**
     * Returns the status in Portuguese for display
     */
    public function getStatusInPortugueseAttribute()
    {
        switch ($this->status) {
            case 'Pending':
                return 'Pendente';
            case 'Approved':
                return 'Aprovado';
            case 'Rejected':
                return 'Recusado';
            default:
                return 'Pendente';
        }
    }
}


