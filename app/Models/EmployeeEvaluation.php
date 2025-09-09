<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

// app/Models/EmployeeEvaluation.php

class EmployeeEvaluation extends Model
{
    use HasFactory;

    protected $table = 'employee_evaluations';

    protected $fillable = [
        'employeeId',
        'evaluationDate',
        'evaluator',
        'overallScore',
        'comments',
    ];

    // ⬇️ Adicione este cast:
    protected $casts = [
        'evaluationDate' => 'date',         // agora é um Carbon
        'overallScore'   => 'decimal:2',    // formata automaticamente
    ];

    public function employee()
    {
        return $this->belongsTo(Employeee::class, 'employeeId');
    }

    public static function generatePdf(EmployeeEvaluation $evaluation)
    {
        $pdf = PDF::loadView('employeeEvaluations.pdf', compact('evaluation'))
                   ->setPaper('a4','portrait');

        return $pdf->stream("Evaluation_{$evaluation->id}.pdf");
    }
}
