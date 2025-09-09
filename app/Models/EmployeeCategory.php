<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function employees()
    {
        return $this->hasMany(Employeee::class, 'employee_category_id');
    }
}


