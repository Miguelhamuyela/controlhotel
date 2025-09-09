<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description'];

    // Relacionamento: cada departamento possui muitos Employeee (funcionários)
    public function employeee()
    {
        return $this->hasMany(Employeee::class, 'departmentId');
    }

    public function head()
    {
        return $this->belongsTo(Employeee::class, 'department_head_id');
    }




}


