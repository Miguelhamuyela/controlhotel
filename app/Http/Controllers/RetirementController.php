<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Retirement;
use App\Models\Employeee;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Auth;

class RetirementController extends Controller
{
    public function index(Request $request)
    {
        $query = Retirement::with('employee');

        // filtros
        if ($request->filled('startDate')) {
            $query->whereDate('requestDate', '>=', $request->startDate);
        }
        if ($request->filled('endDate')) {
            $query->whereDate('requestDate', '<=', $request->endDate);
        }
        if ($request->filled('status') && $request->status !== 'Todos') {
            $query->where('status', $request->status);

        }
       

        $retirements = $query->orderByDesc('id')->get();

        return view('retirement.index', [
            'retirements' => $retirements,
            'filters'     => [
                'startDate' => $request->startDate,
                'endDate'   => $request->endDate,
                'status'    => $request->status,
            ],
        ]);
    }

    public function exportFilteredPDF(Request $request)
    {
        $query = Retirement::with('employee');

        if ($request->filled('startDate')) {
            $query->whereDate('requestDate', '>=', $request->startDate);
        }
        if ($request->filled('endDate')) {
            $query->whereDate('requestDate', '<=', $request->endDate);
        }
        if ($request->filled('status') && $request->status !== 'Todos') {
            $query->where('status', $request->status);
        }

        $filtered = $query->orderByDesc('id')->get();

        $pdf = PDF::loadView('retirement.retirement_pdf', ['allRetirements' => $filtered])
                  ->setPaper('a4', 'portrait');

        return $pdf->download('RelatorioReformas_Filtradas.pdf');
    }

    /**
     * Exibe o formulário para criar um novo pedido de reforma.
     * Admin/Diretor: exibe formulário de busca para selecionar funcionário.
     * Funcionário: utiliza seus próprios dados.
     */
    public function create()
    {
        $user = Auth::user();
        if (in_array($user->role, ['admin', 'director'])) {
            return view('retirement.createSearch');
        } else {
            $employee = $user->employee;
            return view('retirement.createEmployee', compact('employee'));
        }
    }

    /**
     * Busca um funcionário por ID ou Nome – para Admin/Diretor.
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

        return view('retirement.createSearch', ['employee' => $employee]);
    }

    /**
     * Armazena o pedido de reforma.
     * Se o status for aprovado, atualiza o status do funcionário para "retired".
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'employee') {
            $employeeId = $user->employee->id ?? null;
        } else {
            $request->validate([
                'employeeId' => 'required|exists:employeees,id',
            ]);
            $employeeId = $request->employeeId;
        }

        $request->validate([
            'requestDate'    => 'nullable|date',
            'retirementDate' => 'nullable|date|after_or_equal:requestDate',
            'status'         => 'required|in:Pendente,Aprovado,Rejeitado',
            'observations'   => 'nullable|string',
        ]);

        $data = $request->all();
        $data['employeeId'] = $employeeId;
        if (empty($data['requestDate'])) {
            $data['requestDate'] = Carbon::now()->format('Y-m-d');
        }

        $retirement = Retirement::create($data);

        // Se o pedido for aprovado, atualiza o status do funcionário para "retired"
        if (strtolower($data['status']) === 'aprovado') {
            $employee = Employeee::find($employeeId);
            if ($employee) {
                $employee->employmentStatus = 'retired';
                $employee->save();
            }
        }

        return redirect()->route('retirements.index')
                         ->with('msg', 'Pedido de reforma registrado com sucesso.');
    }

    /**
     * Exibe os detalhes do pedido de reforma.
     */
    public function show($id)
    {
        $retirement = Retirement::with('employee')->findOrFail($id);
        return view('retirement.show', compact('retirement'));
    }

    /**
     * Exibe o formulário para editar um pedido de reforma.
     */
    public function edit($id)
    {
        $retirement = Retirement::findOrFail($id);
        return view('retirement.edit', compact('retirement'));
    }

    /**
     * Atualiza o pedido de reforma.
     * Se o status for aprovado, atualiza o status do funcionário para "retired".
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'requestDate'    => 'nullable|date',
            'retirementDate' => 'nullable|date|after_or_equal:requestDate',
            'status'         => 'required|in:Pendente,Aprovado,Rejeitado',
            'observations'   => 'nullable|string',
        ]);

        $retirement = Retirement::findOrFail($id);
        $data = $request->all();
        $retirement->update($data);

        if (strtolower($data['status']) === 'aprovado') {
            $employee = Employeee::find($retirement->employeeId);
            if ($employee) {
                $employee->employmentStatus = 'retired';
                $employee->save();
            }
        }

        return redirect()->route('retirements.index')
                         ->with('msg', 'Pedido de reforma atualizado com sucesso.');
    }

    /**
     * Remove o pedido de reforma.
     * Se o pedido for removido, opcionalmente reverte o status do funcionário para "active".
     */
    public function destroy($id)
    {
        $retirement = Retirement::findOrFail($id);
        $employeeId = $retirement->employeeId;
        $retirement->delete();

        // Reverte o status para ativo, se desejado
        $employee = Employeee::find($employeeId);
        if ($employee) {
            $employee->employmentStatus = 'active';
            $employee->save();
        }

        return redirect()->route('retirements.index')
                         ->with('msg', 'Pedido de reforma removido com sucesso.');
    }

    /**
     * Gera um PDF com todos os pedidos de reforma.
     */
    public function pdfAll(Request $request)
    {
        $query = Retirement::with('employee');

        if ($request->filled('startDate')) {
            $query->whereDate('requestDate', '>=', $request->startDate);
        }
        if ($request->filled('endDate')) {
            $query->whereDate('requestDate', '<=', $request->endDate);
        }
        if ($request->filled('status') && $request->status !== 'Todos') {
            $query->where('status', $request->status);
        }

        $allRetirements = $query->orderByDesc('id')->get();

        $pdf = PDF::loadView('retirement.retirement_pdf', compact('allRetirements'))
                  ->setPaper('a4', 'portrait');

        $filename = 'RelatorioReformas'
                  . (($request->filled('startDate') || $request->filled('status')) ? '_Filtrado' : '')
                  . '.pdf';

        return $pdf->stream($filename);
    }
}
