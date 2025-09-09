<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InternEvaluation;
use App\Models\Intern;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class InternEvaluationController extends Controller
{
    public function index()
    {
        // Lista todas as avaliações de estagiários, carregando também o relacionamento com Intern
        $evaluations = InternEvaluation::with('intern')->orderByDesc('id')->get();
        return view('internEvaluation.index', compact('evaluations'));
    }

    public function create()
    {
        // Formulário para criar uma nova avaliação
        return view('internEvaluation.create');
    }

    // Método para buscar um estagiário por ID ou Nome
    public function searchIntern(Request $request)
    {
        $request->validate([
            'internSearch' => 'required|string',
        ]);

        $term = $request->internSearch;
        $intern = Intern::where('id', $term)
            ->orWhere('fullName', 'LIKE', "%$term%")
            ->first();

        if (!$intern) {
            return redirect()->back()
                             ->withErrors(['internSearch' => 'Estagiário não encontrado!'])
                             ->withInput();
        }

        return view('internEvaluation.create', [
            'intern' => $intern,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'internId'                  => 'required|integer|exists:interns,id',
            'pontualidade'              => 'required|string',
            'trabalhoEquipe'            => 'required|string',
            'autodidacta'               => 'required|string',
            'disciplina'                => 'required|string',
            'focoResultado'             => 'required|string',
            'comunicacao'               => 'required|string',
            'apresentacao'              => 'required|string',
            'evaluationComment'         => 'nullable|string',
            'programaEstagio'           => 'nullable|string',
            'projectos'                 => 'nullable|string',
            'atividadesDesenvolvidas'   => 'nullable|string',
            'evaluationStatus'          => 'required|in:Pendente,Aprovado,Recusado',
        ]);

        $data = $request->all();
        
        if (!isset($data['evaluationStatus']) || empty($data['evaluationStatus'])) {
            $data['evaluationStatus'] = 'Pendente';
        }

        InternEvaluation::create($data);

        return redirect()->route('internEvaluation.index')
                         ->with('msg', 'Avaliação registrada com sucesso!');
    }

    public function show($id)
    {
        $evaluation = InternEvaluation::with('intern')->findOrFail($id);
        return view('internEvaluation.show', compact('evaluation'));
    }

    public function edit($id)
    {
        $evaluation = InternEvaluation::findOrFail($id);
        return view('internEvaluation.edit', compact('evaluation'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pontualidade'              => 'required|string',
            'trabalhoEquipe'            => 'required|string',
            'autodidacta'               => 'required|string',
            'disciplina'                => 'required|string',
            'focoResultado'             => 'required|string',
            'comunicacao'               => 'required|string',
            'apresentacao'              => 'required|string',
            'evaluationComment'         => 'nullable|string',
            'evaluationStatus'          => 'required|in:Pendente,Aprovado,Recusado',
            'programaEstagio'           => 'nullable|string',
            'projectos'                 => 'nullable|string',
            'atividadesDesenvolvidas'   => 'nullable|string',
        ]);

        $evaluation = InternEvaluation::findOrFail($id);
        $evaluation->update($request->all());

        return redirect()->route('internEvaluation.show', $id)
                         ->with('msg', 'Avaliação atualizada com sucesso!');
    }

    public function destroy($id)
    {
        InternEvaluation::destroy($id);
        return redirect()->route('internEvaluation.index')
                         ->with('msg', 'Avaliação removida com sucesso!');
    }

    public function pdf($id)
    {
        $evaluation = InternEvaluation::with('intern')->findOrFail($id);
        $pdf = PDF::loadView('internEvaluation.internEvaluation_pdf', compact('evaluation'))
                  ->setPaper('a4', 'portrait');

        return $pdf->stream('RelatorioAvaliacaoEstagiario.pdf');
    }

    public function pdfAll()
    {
        $evaluations = InternEvaluation::with('intern')->get();
        $pdf = PDF::loadView('internEvaluation.internEvaluation_pdf_all', compact('evaluations'))
                  ->setPaper('a4', 'portrait');

        return $pdf->stream('RelatorioTodasAvaliacoesEstagiarios.pdf');
    }
}
