<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaterialType extends Model
{
    protected $fillable = [
        'name',
        'category',
        'description'];

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class, 'materialTypeId');
    }
}

