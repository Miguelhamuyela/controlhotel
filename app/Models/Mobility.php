<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mobility extends Model
{
    use HasFactory;

    protected $fillable = [
        'employeeId',
        'oldDepartmentId',
        'newDepartmentId',
        'causeOfMobility',
    ];

    // Relação: Mobility pertence a um funcionário
    public function employee()
    {
        return $this->belongsTo(Employeee::class, 'employeeId');
    }

    // Relação: Departamento antigo
    public function oldDepartment()
    {
        return $this->belongsTo(Department::class, 'oldDepartmentId');
    }

    // Relação: Novo departamento
    public function newDepartment()
    {
        return $this->belongsTo(Department::class, 'newDepartmentId');
    }
}
