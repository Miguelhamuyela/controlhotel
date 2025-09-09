<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LicenseCategory extends Model
{
    protected $fillable = ['name','description'];

    public function drivers()
    {
        return $this->hasMany(Driver::class, 'licenseCategoryId');
    }
}
