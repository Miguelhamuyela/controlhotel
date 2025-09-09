<?php

namespace App\Http\Controllers;

use App\Models\EmployeeEvaluation;
use App\Models\Employeee;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class EmployeeEvaluationController extends Controller
{
    // se você quiser manter só auth, e não a gate, remova qualquer can:department_head do construtor

    public function index()
    {
        $evaluations = EmployeeEvaluation::with('employee')
                             ->orderByDesc('id')
                             ->get();

        return view('employeeEvaluations.index', compact('evaluations'));
    }

    public function create()
    {
        // nome padrão do avaliador = usuário atual
        $currentUserName = auth()->user()->employee->fullName;
        return view('employeeEvaluations.create', compact('currentUserName'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employeeId'     => 'required|exists:employeees,id',
            'evaluationDate' => 'required|date',
            'evaluator'      => 'required|string|max:100',
            'overallScore'   => 'required|numeric|between:0,10',
            'comments'       => 'nullable|string',
        ]);

        EmployeeEvaluation::create($data);

        return redirect()
               ->route('employeeEvaluations.index')
               ->with('msg','Avaliação criada com sucesso!');
    }

    public function show(EmployeeEvaluation $employeeEvaluation)
    {
        return view('employeeEvaluations.show', [
            'evaluation' => $employeeEvaluation->load('employee')
        ]);
    }

    public function edit(EmployeeEvaluation $employeeEvaluation)
    {
        // pré-popula avaliador
        $currentUserName = auth()->user()->employee->fullName;
        return view('employeeEvaluations.edit', [
            'evaluation'      => $employeeEvaluation,
            'currentUserName'=> $currentUserName,
        ]);
    }

    public function update(Request $request, EmployeeEvaluation $employeeEvaluation)
    {
        $data = $request->validate([
            'employeeId'     => 'required|exists:employeees,id',
            'evaluationDate' => 'required|date',
            'evaluator'      => 'required|string|max:100',
            'overallScore'   => 'required|numeric|between:0,10',
            'comments'       => 'nullable|string',
        ]);

        $employeeEvaluation->update($data);

        return redirect()
               ->route('employeeEvaluations.index')
               ->with('msg','Avaliação atualizada com sucesso!');
    }

    public function destroy(EmployeeEvaluation $employeeEvaluation)
    {
        $employeeEvaluation->delete();
        return redirect()
               ->route('employeeEvaluations.index')
               ->with('msg','Avaliação removida com sucesso!');
    }

    public function pdf(EmployeeEvaluation $employeeEvaluation)
    {
        return EmployeeEvaluation::generatePdf(
            $employeeEvaluation->load('employee')
        );
    }

    public function pdfAll()
    {
        $evaluations = EmployeeEvaluation::with('employee')->get();
        $pdf = PDF::loadView('employeeEvaluations.pdfAll', compact('evaluations'))
                   ->setPaper('a4','landscape');
        return $pdf->stream("All_Evaluations.pdf");
    }

    /**
     * AJAX: busca funcionários pelo nome
     */
        public function searchEmployee(Request $request)
{
    $q = $request->get('q');
    $emps = Employeee::where('fullName', 'LIKE', "%{$q}%")
            ->orderBy('fullName')
            ->limit(15)
            ->get(['id','fullName']);

    return response()->json($emps);
}

}