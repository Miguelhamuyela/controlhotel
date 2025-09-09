<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Employeee;
use App\Models\Material;
use App\Models\Department;


class MaterialTransaction extends Model
{
    protected $table = 'material_transactions';

    protected $fillable = [
        'MaterialId',
        'TransactionType',
        'TransactionDate',
        'Quantity',
        'OriginOrDestination',
        'DocumentationPath',
        'Notes',
        'DepartmentId',
        'CreatedBy',
    ];

    /**
     * Material desta transação.
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'MaterialId');
    }

    /**
     * Departamento de destino/origem.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'DepartmentId');
    }

    /**
     * Funcionário que registrou (agora Employeee).
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Employeee::class, 'CreatedBy');
    }
    
}
