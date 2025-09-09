<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Secondment;
use App\Models\Employeee;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewSecondmentNotification;

class SecondmentController extends Controller
{
    /**
     * Lista todos os destacamentos de funcionários ATIVOS.
     */
    public function index()
    {
        $data = Secondment::with('employee')
            ->whereHas('employee', function($q) {
                $q->where('employmentStatus', 'active');
            })
            ->orderByDesc('id')
            ->get();

        return view('secondment.index', compact('data'));
    }

    /**
     * Exibe formulário de criação de destacamento.
     */
    public function create()
    {
        return view('secondment.create');
    }

    /**
     * Busca funcionário por ID ou nome.
     */
    public function searchEmployee(Request $request)
    {
        $request->validate([
            'employeeSearch' => 'required|string',
        ]);

        $term = $request->employeeSearch;
        $employee = Employeee::where('id', $term)
            ->orWhere('fullName', 'LIKE', "%{$term}%")
            ->first();

        if (!$employee) {
            return redirect()->back()
                             ->withErrors(['employeeSearch' => 'Funcionário não encontrado!'])
                             ->withInput();
        }

        return view('secondment.create', compact('employee'));
    }

    /**
     * Armazena novo destacamento e notifica por e-mail.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employeeId'      => 'required|integer|exists:employeees,id',
            'causeOfTransfer' => 'nullable|string',
            'institution'     => 'required|string',
            'supportDocument' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx',
        ]);

        $data = $request->all();

        if ($request->hasFile('supportDocument')) {
            $file            = $request->file('supportDocument');
            $filename        = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('/uploads/secondments');
            $file->move($destinationPath, $filename);
            $data['supportDocument']  = $filename;
            $data['originalFileName'] = $file->getClientOriginalName();
        }

        $secondment = Secondment::create([
            'employeeId'       => $data['employeeId'],
            'causeOfTransfer'  => $data['causeOfTransfer'] ?? null,
            'institution'      => $data['institution'],
            'supportDocument'  => $data['supportDocument'] ?? null,
            'originalFileName' => $data['originalFileName'] ?? null,
        ]);

        // Notifica por e-mail
        $employee = Employeee::find($data['employeeId']);
        Mail::to($employee->email)
            ->send(new NewSecondmentNotification($employee, $data['institution'], $data['causeOfTransfer'] ?? ''));

        return redirect()->route('secondment.index')
                         ->with('msg', 'Destacamento registrado com sucesso e e-mail enviado!');
    }

    /**
     * Exibe detalhes de um destacamento.
     */
    public function show($id)
    {
        $data = Secondment::with('employee')->findOrFail($id);
        return view('secondment.show', compact('data'));
    }

    /**
     * Formulário de edição de destacamento.
     */
    public function edit($id)
    {
        $data = Secondment::findOrFail($id);
        return view('secondment.edit', compact('data'));
    }

    /**
     * Atualiza um destacamento existente.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'causeOfTransfer' => 'nullable|string',
            'institution'     => 'required|string',
            'supportDocument' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx',
        ]);

        $secondment = Secondment::findOrFail($id);
        $data       = $request->all();

        if ($request->hasFile('supportDocument')) {
            $file            = $request->file('supportDocument');
            $filename        = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('/uploads/secondments');
            $file->move($destinationPath, $filename);
            $data['supportDocument']  = $filename;
            $data['originalFileName'] = $file->getClientOriginalName();
        } else {
            $data['supportDocument']  = $secondment->supportDocument;
            $data['originalFileName'] = $secondment->originalFileName;
        }

        $secondment->update([
            'causeOfTransfer'  => $data['causeOfTransfer'] ?? null,
            'institution'      => $data['institution'],
            'supportDocument'  => $data['supportDocument'],
            'originalFileName' => $data['originalFileName'],
        ]);

        return redirect()->route('secondment.edit', $id)
                         ->with('msg', 'Destacamento atualizado com sucesso!');
    }

    /**
     * Remove destacamento.
     */
    public function destroy($id)
    {
        Secondment::destroy($id);
        return redirect()->route('secondment.index');
    }

    /**
     * Gera PDF com TODOS os destacamentos (mesmo dos aposentados).
     */
    public function pdfAll()
    {
        $allSecondments = Secondment::with('employee')->get();
        $pdf = PDF::loadView('secondment.secondment_pdf', compact('allSecondments'))
                  ->setPaper('a3', 'portrait');

        return $pdf->stream('RelatorioDestacamentos.pdf');
    }
}
