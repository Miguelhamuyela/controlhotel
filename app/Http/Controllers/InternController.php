<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Intern;
use App\Models\Department;
use App\Models\Position;
use App\Models\Specialty;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;

class InternController extends Controller
{
    public function index()
    {
        $data = Intern::orderByDesc('id')->get();
        return view('intern.index', compact('data'));
    }

    public function create()
    {
        $departments = Department::all();
        $positions   = Position::all();
        $specialties = Specialty::all();

        return view('intern.create', compact('departments', 'positions', 'specialties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'depart'           => 'required',
            'fullName'         => 'required',
            'address'          => 'required',
            'mobile'           => 'required',
            'fatherName'      => 'required',
            'motherName'      => 'required',
            'bi'               => 'required|unique:interns',
            'birth_date'       => 'required|date|date_format:Y-m-d|before_or_equal:today|after_or_equal:' . Carbon::now()->subYears(120)->format('Y-m-d'),
            'nationality'      => 'required',
            'gender'           => 'required',
            'email'            => 'required|email|unique:interns',
            'positionId'       => 'required|exists:positions,id',
            'specialtyId'      => 'required|exists:specialties,id',
            'internshipStart'  => 'required|date|date_format:Y-m-d',
            'internshipEnd'    => 'required|date|date_format:Y-m-d|after_or_equal:internshipStart',
            'institution'      => 'required|string'
        ], [
            'birth_date.date_format'       => 'A data de nascimento deve estar no formato AAAA-MM-DD.',
            'birth_date.before_or_equal'   => 'A data de nascimento não pode ser superior à data atual.',
            'birth_date.after_or_equal'    => 'A data de nascimento informada é inválida.',
            'internshipStart.required'     => 'O início do estágio é obrigatório.',
            'internshipEnd.required'       => 'O fim do estágio é obrigatório.',
            'internshipEnd.after_or_equal' => 'O fim do estágio não pode ser anterior ao início.',
            'institution.required'         => 'A instituição de origem é obrigatória.'
        ]);

        $intern = new Intern();
        $intern->departmentId    = $request->depart;
        $intern->fullName        = $request->fullName;
        $intern->address         = $request->address;
        $intern->mobile          = $request->mobile;
        $intern->fatherName     = $request->fatherName;
        $intern->motherName     = $request->motherName;
        $intern->bi              = $request->bi;
        $intern->birth_date      = $request->birth_date;
        $intern->nationality     = $request->nationality;
        $intern->gender          = $request->gender;
        $intern->email           = $request->email;
        $intern->positionId      = $request->positionId;
        $intern->specialtyId     = $request->specialtyId;
        $intern->internshipStart = $request->internshipStart;
        $intern->internshipEnd   = $request->internshipEnd;
        $intern->institution     = $request->institution;
        $intern->save();

        return redirect()->route('intern.create')->with('msg', 'Estagiário cadastrado com sucesso');
    }

    public function show($id)
    {
        $data = Intern::findOrFail($id);
        return view('intern.show', compact('data'));
    }

    public function edit($id)
    {
        $data        = Intern::findOrFail($id);
        $departments = Department::orderByDesc('id')->get();
        $positions   = Position::all();
        $specialties = Specialty::all();

        return view('intern.edit', compact('data', 'departments', 'positions', 'specialties'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'depart'           => 'required',
            'fullName'         => 'required',
            'address'          => 'required',
            'mobile'           => 'required',
            'bi'               => 'required|unique:interns,bi,'.$id,
            'email'            => 'required|email|unique:interns,email,'.$id,
            'nationality'      => 'required',
            'internshipStart'  => 'required|date|date_format:Y-m-d',
            'internshipEnd'    => 'required|date|date_format:Y-m-d|after_or_equal:internshipStart',
            'institution'      => 'required|string'
        ], [
            'internshipStart.required'     => 'O início do estágio é obrigatório.',
            'internshipEnd.required'       => 'O fim do estágio é obrigatório.',
            'internshipEnd.after_or_equal' => 'O fim do estágio não pode ser anterior ao início.',
            'institution.required'         => 'A instituição de origem é obrigatória.'
        ]);

        $intern = Intern::findOrFail($id);
        $intern->departmentId    = $request->depart;
        $intern->fullName        = $request->fullName;
        $intern->address         = $request->address;
        $intern->mobile          = $request->mobile;
        $intern->internshipStart = $request->internshipStart;
        $intern->internshipEnd   = $request->internshipEnd;
        $intern->institution     = $request->institution;
        $intern->nationality     = $request->nationality;
        $intern->save();

        return redirect()->route('intern.edit', $id)->with('msg', 'Estagiário atualizado com sucesso');
    }

    // ========== Filtro por datas ==========
    public function filterByDate(Request $request)
    {
        // Se não vier start_date e end_date, retornamos a view de filtro vazia
        if (!$request->has('start_date') && !$request->has('end_date')) {
            return view('intern.filter');
        }

        // Validação
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        // Converter para início e fim do dia
        $start = Carbon::parse($request->start_date)->startOfDay();
        $end   = Carbon::parse($request->end_date)->endOfDay();

        // Buscar estagiários criados entre start e end
        $filtered = Intern::whereBetween('created_at', [$start, $end])
                          ->orderByDesc('id')
                          ->get();

        // Para mostrar no formulário e repassar ao PDF
        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        return view('intern.filter', [
            'filtered'  => $filtered,
            'startDate' => $startDate,
            'endDate'   => $endDate,
        ]);
    }

    public function pdfFiltered(Request $request)
    {
        // Caso o PDF demore, podemos aumentar o tempo de execução
        set_time_limit(300);

        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $start = Carbon::parse($request->start_date)->startOfDay()->format('d-m-Y');
        $end   = Carbon::parse($request->end_date)->endOfDay()->format('d-m-Y');

        $filtered = Intern::whereBetween('created_at', [$start, $end])
                          ->orderByDesc('id')
                          ->get();

        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        // Carrega a view filtered_pdf.blade.php
        $pdf = PDF::loadView('intern.filtered_pdf', compact('filtered', 'startDate', 'endDate'))
                  ->setPaper('a3', 'portrait');

        return $pdf->stream("RelatorioInterns_{$startDate}_{$endDate}.pdf");
    }

    // ========== PDF de Todos os Estagiários ==========
    public function pdfAll()
    {
        $allInterns = Intern::with(['department', 'position', 'specialty'])->get();
        $pdf = PDF::loadView('intern.intern_pdf', compact('allInterns'))
                  ->setPaper('a3', 'portrait');

        return $pdf->stream('RelatorioTodosEstagiarios.pdf');
    }

    public function destroy($id)
    {
        Intern::destroy($id);
        return redirect()->route('intern.index');
    }
}
