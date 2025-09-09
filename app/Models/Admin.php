<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Admin extends Authenticatable implements CanResetPasswordContract
{
    use Notifiable, CanResetPassword;

    protected $fillable = [
        'employeeId',
        'role',
        'email',
        'password',
        'directorType',
        'directorName',
        'directorPhoto',
        'photo',
        'biography',     // novo campo para biografia
        'linkedin',      // novo campo para LinkedIn
        'department_id', // para chefes de departamento
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function employee()
    {
        return $this->belongsTo(Employeee::class, 'employeeId');
    }
}
