<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceRecord;
use App\Models\Employeee;
use App\Models\VacationRequest;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class AttendanceController extends Controller
{
    /**
     * Exibe o formulário para registrar presença individual.
     */
    public function create()
    {
        $user = Auth::user();
        if ($user->role === 'department_head') {
            $departmentId = $user->employee->departmentId;
            $employees = Employeee::where('departmentId', $departmentId)
                ->where('employmentStatus', 'active')
                ->orderBy('fullName')
                ->get();
        } else {
            $employees = Employeee::where('employmentStatus', 'active')
                ->orderBy('fullName')
                ->get();
        }
        return view('attendance.create', compact('employees'));
    }

    /**
     * Armazena o registro de presença individual.
     *
     * Se o status informado for "Ausente" mas houver férias ou licença aprovados para a data, 
     * o sistema ajusta automaticamente o status para "Férias" ou "Licença" e exibe uma notificação.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employeeId' => 'required|exists:employeees,id',
            'recordDate' => 'required|date',
            'status'     => 'required|string',
        ]);

        $employeeId = $request->employeeId;
        $recordDate = Carbon::parse($request->recordDate)->format('Y-m-d');

        // Consulta se há férias aprovadas para essa data
        $vacation = VacationRequest::where('employeeId', $employeeId)
            ->where('approvalStatus', 'Aprovado')
            ->whereDate('vacationStart', '<=', $recordDate)
            ->whereDate('vacationEnd', '>=', $recordDate)
            ->first();

        // Consulta se há licença aprovada para essa data
        $leave = LeaveRequest::where('employeeId', $employeeId)
            ->where('approvalStatus', 'Aprovado')
            ->whereDate('leaveStart', '<=', $recordDate)
            ->whereDate('leaveEnd', '>=', $recordDate)
            ->first();

        $flashMessage = null;
        if ($request->status === 'Ausente') {
            if ($vacation) {
                $request->merge(['status' => 'Férias']);
                $flashMessage = "Registro ajustado automaticamente para Férias, pois o funcionário já possui férias aprovadas para esta data.";
            } elseif ($leave) {
                $request->merge(['status' => 'Licença']);
                $flashMessage = "Registro ajustado automaticamente para Licença, pois o funcionário já possui licença aprovada para esta data.";
            }
        }

        AttendanceRecord::create($request->all());

        return redirect()->route('attendance.index')
            ->with('msg', $flashMessage ?? 'Registro de presença salvo com sucesso.');
    }

    /**
     * Lista os registros de presença.
     */
    public function index(Request $request)
    {
        $query = AttendanceRecord::with('employee')->orderBy('recordDate', 'desc');
        if ($request->has('date')) {
            $query->where('recordDate', $request->date);
        }
        if ($request->has('employeeId')) {
            $query->where('employeeId', $request->employeeId);
        }
        $records = $query->get();
        return view('attendance.index', compact('records'));
    }

    /**
     * Exibe o dashboard de efetividade para o período (ex.: mês atual).
     */
    public function dashboard(Request $request)
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate   = Carbon::now()->endOfMonth();
        $totalWeekdays = $this->countWeekdays($startDate, $endDate);

        $user = Auth::user();
        if ($user->role === 'department_head') {
            $departmentId = $user->employee->departmentId;
            $employees = Employeee::where('departmentId', $departmentId)
                ->where('employmentStatus', 'active')
                ->orderBy('fullName')
                ->get();
        } else {
            $employees = Employeee::where('employmentStatus', 'active')
                ->orderBy('fullName')
                ->get();
        }

        $dashboardData = [];
        foreach ($employees as $employee) {
            $records = AttendanceRecord::where('employeeId', $employee->id)
                ->whereBetween('recordDate', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->get();

            $presentDays = $records->where('status', 'Presente')->count();
            $justifiedDays = $records->whereIn('status', ['Férias', 'Licença', 'Doença', 'Teletrabalho'])->count();
            $absentDays = $totalWeekdays - ($presentDays + $justifiedDays);
            $attendanceRate = $totalWeekdays > 0 ? round(($presentDays / $totalWeekdays) * 100, 2) : 0;

            $dashboardData[] = [
                'employeeName'  => $employee->fullName,
                'department'    => $employee->department->title ?? 'Sem Departamento',
                'totalWeekdays' => $totalWeekdays,
                'presentDays'   => $presentDays,
                'justifiedDays' => $justifiedDays,
                'absentDays'    => $absentDays,
                'attendanceRate'=> $attendanceRate,
            ];
        }

        return view('attendance.dashboard', compact('dashboardData', 'startDate', 'endDate'));
    }

    /**
     * Método para consulta AJAX: retorna se o funcionário possui férias ou licença aprovados para a data.
     */
    public function checkStatus(Request $request)
    {
        $request->validate([
            'employeeId' => 'required|exists:employeees,id',
            'recordDate' => 'required|date',
        ]);

        $employeeId = $request->employeeId;
        $recordDate = Carbon::parse($request->recordDate)->format('Y-m-d');

        $vacation = VacationRequest::where('employeeId', $employeeId)
            ->where('approvalStatus', 'Aprovado')
            ->whereDate('vacationStart', '<=', $recordDate)
            ->whereDate('vacationEnd', '>=', $recordDate)
            ->first();

        $leave = LeaveRequest::where('employeeId', $employeeId)
            ->where('approvalStatus', 'Aprovado')
            ->whereDate('leaveStart', '<=', $recordDate)
            ->whereDate('leaveEnd', '>=', $recordDate)
            ->first();

        $response = [];
        if ($vacation) {
            $response['justification'] = 'Férias';
            $response['details'] = $vacation->vacationType;
        } elseif ($leave) {
            $response['justification'] = 'Licença';
            $response['details'] = $leave->leaveType->name ?? '';
        } else {
            $response['justification'] = null;
        }

        return response()->json($response);
    }

    /**
     * Exibe o formulário para registro de presença em lote.
     * Separa os funcionários em:
     *  - Active: sem ausência justificada para a data.
     *  - Justified: com férias ou licença aprovados para a data.
     */
    public function createBatch(Request $request)
    {
        $recordDate = $request->input('recordDate', date('Y-m-d'));
        $recordDateCarbon = Carbon::parse($recordDate);

        $user = Auth::user();
        if ($user->role === 'department_head') {
            $departmentId = $user->employee->departmentId;
            $employees = Employeee::where('departmentId', $departmentId)
                ->where('employmentStatus', 'active')
                ->orderBy('fullName')
                ->get();
        } else {
            $employees = Employeee::where('employmentStatus', 'active')
                ->orderBy('fullName')
                ->get();
        }

        $activeEmployees = [];
        $justifiedEmployees = [];

        foreach ($employees as $employee) {
            $vacation = VacationRequest::where('employeeId', $employee->id)
                ->where('approvalStatus', 'Aprovado')
                ->whereDate('vacationStart', '<=', $recordDate)
                ->whereDate('vacationEnd', '>=', $recordDate)
                ->first();

            $leave = LeaveRequest::where('employeeId', $employee->id)
                ->where('approvalStatus', 'Aprovado')
                ->whereDate('leaveStart', '<=', $recordDate)
                ->whereDate('leaveEnd', '>=', $recordDate)
                ->first();

            if ($vacation) {
                $justifiedEmployees[] = [
                    'employee' => $employee,
                    'justification' => 'Férias',
                    'details' => $vacation->vacationType,
                ];
            } elseif ($leave) {
                $justifiedEmployees[] = [
                    'employee' => $employee,
                    'justification' => 'Licença',
                    'details' => $leave->leaveType->name ?? '',
                ];
            } else {
                $activeEmployees[] = $employee;
            }
        }

        return view('attendance.createBatch', compact('activeEmployees', 'justifiedEmployees', 'recordDate'));
    }

    /**
     * Processa o registro de presença em lote para os funcionários ativos.
     */
    public function storeBatch(Request $request)
    {
        $request->validate([
            'recordDate' => 'required|date',
            'attendance' => 'required|array', // array de employeeId => status
        ]);

        $recordDate = Carbon::parse($request->recordDate)->format('Y-m-d');

        foreach ($request->attendance as $employeeId => $status) {
            AttendanceRecord::create([
                'employeeId' => $employeeId,
                'recordDate' => $recordDate,
                'status'     => $status,
                'observations' => $request->observations[$employeeId] ?? null,
            ]);
        }

        return redirect()->route('attendance.index')
            ->with('msg', 'Registros de presença salvos com sucesso.');
    }

    /**
     * Função auxiliar: conta os dias úteis entre duas datas.
     */
    private function countWeekdays(Carbon $start, Carbon $end)
    {
        $days = 0;
        $current = $start->copy();
        while ($current->lte($end)) {
            if ($current->isWeekday()) {
                $days++;
            }
            $current->addDay();
        }
        return $days;
    }

    /**
     * Gera um PDF com todos os registros de presença.
     */
    public function pdfAll()
    {
        $records = AttendanceRecord::with('employee')->orderBy('recordDate', 'desc')->get();
        $pdf = PDF::loadView('attendance.attendance_pdf', compact('records'))
                ->setPaper('a4', 'portrait');
        return $pdf->stream('RelatorioPresenca.pdf');
    }
}
