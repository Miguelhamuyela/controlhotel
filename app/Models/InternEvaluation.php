<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternEvaluation extends Model
{
    use HasFactory;

    // Se a tabela não segue o padrão de pluralização do Laravel, definimos explicitamente:
    protected $table = 'intern_evaluations';

    protected $fillable = [
        'internId',
        'evaluationStatus',
        'evaluationComment',
        'pontualidade',
        'trabalhoEquipe',
        'autodidacta',
        'disciplina',
        'focoResultado',
        'comunicacao',
        'apresentacao',
        'programaEstagio',
        'projectos',
        'atividadesDesenvolvidas',
    ];

    public function intern()
    {
        return $this->belongsTo(Intern::class, 'internId');
    }
}
