<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mobility;
use App\Models\Employeee;
use App\Models\Department;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewMobilityNotification;

class MobilityController extends Controller
{
    public function index()
    {
        $data = Mobility::with(['employee', 'oldDepartment', 'newDepartment'])
                        ->orderByDesc('id')
                        ->get();
        return view('mobility.index', compact('data'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('mobility.create', compact('departments'));
    }

    /**
     * Busca um funcionário por ID ou Nome (somente funcionários ativos).
     */
    public function searchEmployee(Request $request)
    {
        $request->validate([
            'employeeSearch' => 'required|string',
        ]);

        $term = $request->employeeSearch;
        $employee = Employeee::where('employmentStatus', 'active')
            ->where(function($q) use ($term) {
                $q->where('id', $term)
                  ->orWhere('fullName', 'LIKE', "%{$term}%");
            })->first();

        if (!$employee) {
            return redirect()->back()
                ->withErrors(['employeeSearch' => 'Funcionário não encontrado!'])
                ->withInput();
        }

        $oldDepartment = $employee->department;
        $departments = Department::all();

        return view('mobility.create', [
            'departments'   => $departments,
            'employee'      => $employee,
            'oldDepartment' => $oldDepartment,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'employeeId'      => 'required|integer|exists:employeees,id',
            'oldDepartmentId' => 'nullable|integer|exists:departments,id',
            'newDepartmentId' => 'required|integer|exists:departments,id',
            'causeOfMobility' => 'nullable|string',
        ]);

        // Cria o registro de mobilidade
        $mobility = Mobility::create([
            'employeeId'      => $request->employeeId,
            'oldDepartmentId' => $request->oldDepartmentId,
            'newDepartmentId' => $request->newDepartmentId,
            'causeOfMobility' => $request->causeOfMobility,
        ]);

        // Atualiza o departamento do funcionário
        $employee = Employeee::find($request->employeeId);
        $oldDepartment = $employee->department;
        $employee->departmentId = $request->newDepartmentId;
        $employee->save();

        // Envia o e-mail notificando a mobilidade
        $newDepartment = Department::find($request->newDepartmentId);
        Mail::to($employee->email)->send(new NewMobilityNotification($employee, $oldDepartment, $newDepartment, $request->causeOfMobility));

        return redirect()->route('mobility.index')
                         ->with('msg', 'Mobilidade registrada com sucesso e e-mail enviado!');
    }

    public function pdfAll()
    {
        $allMobility = Mobility::with(['employee', 'oldDepartment', 'newDepartment'])
                                ->orderByDesc('id')
                                ->get();

        $pdf = PDF::loadView('mobility.mobility_pdf', compact('allMobility'))
                  ->setPaper('a3', 'portrait');

        return $pdf->stream('RelatorioMobilidades.pdf');
    }

    public function destroy($id)
    {
        Mobility::destroy($id);
        return redirect()->route('mobility.index');
    }
}
