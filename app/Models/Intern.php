<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intern extends Model
{
    use HasFactory;

    protected $fillable = [
        'departmentId',
        'fullName',
        'address',
        'mobile',
        'fatherName',
        'motherName',
        'bi',
        'birth_date',
        'nationality',
        'gender',
        'email',
        'positionId',
        'specialtyId',
        'internshipStart',
        'internshipEnd',
        'institution'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'departmentId');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'positionId');
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class, 'specialtyId');
    }
}

