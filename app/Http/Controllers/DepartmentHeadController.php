<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Employeee;
use App\Models\VacationRequest;
use App\Models\LeaveRequest;
use App\Models\Retirement;
use Carbon\Carbon;
use App\Mail\VacationResponseNotification;
use App\Mail\LeaveResponseNotification;
use App\Mail\RetirementResponseNotification;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class DepartmentHeadController extends Controller
{
    // Lista os funcionários do departamento do chefe
    public function myEmployees()
    {
        $user = Auth::user();
        if ($user->role !== 'department_head') {
            abort(403, 'Acesso negado. Só os Chefes de Departamentos têm acesso a esta página.');
        }

        $headEmployee = $user->employee;
        if (! $headEmployee) {
            return redirect()->back()->withErrors(['msg' => 'Chefe não vinculado a nenhum funcionário.']);
        }

        $departmentId = $headEmployee->departmentId;

        $employees = Employeee::where('departmentId', $departmentId)
            ->where('id', '!=', $headEmployee->id)
            ->orderBy('fullName')
            ->get();

        return view('departmentHead.myEmployees', compact('employees'));
    }

    // ------------------- PEDIDOS DE FÉRIAS -------------------

    // Exibe lista de pedidos de férias pendentes, com filtro por datas
    public function pendingVacations(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'department_head') {
            abort(403, 'Acesso negado.');
        }

        $headEmployee = $user->employee;
        if (! $headEmployee) {
            return redirect()->back()->withErrors(['msg' => 'Chefe não vinculado a nenhum funcionário.']);
        }

        $departmentId = $headEmployee->departmentId;

        // captura parâmetros de filtro
        $from = $request->input('from');
        $to   = $request->input('to');

        // query básica
        $query = VacationRequest::where('approvalStatus', 'Pendente')
            ->whereHas('employee', function ($q) use ($departmentId) {
                $q->where('departmentId', $departmentId);
            });

        // aplica filtro de datas, se houver
        if ($from && $to) {
            $query->whereBetween('created_at', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay(),
            ]);
        }

        $pendingRequests = $query->orderByDesc('id')->get();

        return view('departmentHead.pendingVacationRequests', compact('pendingRequests', 'from', 'to'));
    }

    // Aprova um pedido de férias e envia e‑mail de notificação
    public function approveVacation($id, Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'department_head') {
            abort(403, 'Acesso negada.');
        }
        $vacation = VacationRequest::findOrFail($id);
        if (! $vacation->employee || $vacation->employee->departmentId !== $user->employee->departmentId) {
            abort(403, 'Você não pode aprovar pedidos de outro departamento.');
        }

        $vacation->approvalStatus  = 'Aprovado';
        $vacation->approvalComment = $request->input('approvalComment') ?? 'Aprovado pelo chefe';
        $vacation->save();

        Mail::to($vacation->employee->email)
            ->send(new VacationResponseNotification($vacation));

        return redirect()->route('dh.pendingVacations')
            ->with('msg', 'Pedido de férias aprovado com sucesso!');
    }

    // Rejeita um pedido de férias e envia e‑mail de notificação
    public function rejectVacation($id, Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'department_head') {
            abort(403, 'Acesso negada.');
        }
        $vacation = VacationRequest::findOrFail($id);
        if (! $vacation->employee || $vacation->employee->departmentId !== $user->employee->departmentId) {
            abort(403, 'Você não pode rejeitar pedidos de outro departamento.');
        }

        $vacation->approvalStatus  = 'Recusado';
        $vacation->approvalComment = $request->input('approvalComment') ?? 'Recusado pelo chefe';
        $vacation->save();

        Mail::to($vacation->employee->email)
            ->send(new VacationResponseNotification($vacation));

        return redirect()->route('dh.pendingVacations')
            ->with('msg', 'Pedido de férias rejeitado com sucesso!');
    }

    // ------------------- PEDIDOS DE LICENÇA -------------------

    // Exibe lista de pedidos de licença pendentes, com filtro por datas
    public function pendingLeaves(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'department_head') {
            abort(403, 'Acesso negada.');
        }

        $headEmployee = $user->employee;
        if (! $headEmployee) {
            return redirect()->back()->withErrors(['msg' => 'Chefe não vinculado a nenhum funcionário.']);
        }

        $departmentId = $headEmployee->departmentId;

        $from = $request->input('from');
        $to   = $request->input('to');

        $query = LeaveRequest::where('approvalStatus', 'Pendente')
            ->whereHas('employee', function ($q) use ($departmentId) {
                $q->where('departmentId', $departmentId);
            });

        if ($from && $to) {
            $query->whereBetween('created_at', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay(),
            ]);
        }

        $pendingLeaveRequests = $query->orderByDesc('id')->get();

        return view('departmentHead.pendingLeaveRequests', compact('pendingLeaveRequests', 'from', 'to'));
    }

    // Aprova um pedido de licença e envia e‑mail de notificação
    public function approveLeave($id, Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'department_head') {
            abort(403, 'Acesso negada.');
        }
        $leave = LeaveRequest::findOrFail($id);
        if (! $leave->employee || $leave->employee->departmentId !== $user->employee->departmentId) {
            abort(403, 'Você não pode aprovar pedidos de outro departamento.');
        }

        $leave->approvalStatus  = 'Aprovado';
        $leave->approvalComment = $request->input('approvalComment') ?? 'Aprovado pelo chefe';
        $leave->save();

        Mail::to($leave->employee->email)
            ->send(new LeaveResponseNotification($leave));

        return redirect()->route('dh.pendingLeaves')
            ->with('msg', 'Pedido de licença aprovado com sucesso!');
    }

    // Rejeita um pedido de licença e envia e‑mail de notificação
    public function rejectLeave($id, Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'department_head') {
            abort(403, 'Acesso negada.');
        }
        $leave = LeaveRequest::findOrFail($id);
        if (! $leave->employee || $leave->employee->departmentId !== $user->employee->departmentId) {
            abort(403, 'Você não pode rejeitar pedidos de outro departamento.');
        }

        $leave->approvalStatus  = 'Recusado';
        $leave->approvalComment = $request->input('approvalComment') ?? 'Recusado pelo chefe';
        $leave->save();

        Mail::to($leave->employee->email)
            ->send(new LeaveResponseNotification($leave));

        return redirect()->route('dh.pendingLeaves')
            ->with('msg', 'Pedido de licença rejeitado com sucesso!');
    }

    // ------------------- PEDIDOS DE REFORMA (Retirement) -------------------

    // Exibe lista de pedidos de reforma pendentes, com filtro por datas
    public function pendingRetirements(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'department_head') {
            abort(403, 'Acesso negada.');
        }

        $headEmployee = $user->employee;
        if (! $headEmployee) {
            return redirect()->back()->withErrors(['msg' => 'Chefe não vinculado a nenhum funcionário.']);
        }

        $departmentId = $headEmployee->departmentId;

        $from = $request->input('from');
        $to   = $request->input('to');

        $query = Retirement::where('status', 'Pendente')
            ->whereHas('employee', function ($q) use ($departmentId) {
                $q->where('departmentId', $departmentId);
            });

        if ($from && $to) {
            $query->whereBetween('created_at', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay(),
            ]);
        }

        $pendingRetirements = $query->orderByDesc('id')->get();

        return view('departmentHead.pendingRetirementRequests', compact('pendingRetirements', 'from', 'to'));
    }

    // Aprova um pedido de reforma e envia e‑mail de notificação
    public function approveRetirement($id, Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'department_head') {
            abort(403, 'Acesso negada.');
        }
        $retirement = Retirement::findOrFail($id);
        if (! $retirement->employee || $retirement->employee->departmentId !== $user->employee->departmentId) {
            abort(403, 'Você não pode aprovar pedidos de outro departamento.');
        }

        $retirement->status       = 'Aprovado';
        $retirement->observations = $request->input('approvalComment') ?? 'Aprovado pelo chefe';
        $retirement->save();

        $employee = $retirement->employee;
        if ($employee) {
            $employee->employmentStatus = 'retired';
            $employee->save();
            Mail::to($employee->email)
                ->send(new RetirementResponseNotification($retirement));
        }

        return redirect()->route('dh.pendingRetirements')
            ->with('msg', 'Pedido de reforma aprovado com sucesso!');
    }

    // Rejeita um pedido de reforma e envia e‑mail de notificação
    public function rejectRetirement($id, Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'department_head') {
            abort(403, 'Acesso negada.');
        }
        $retirement = Retirement::findOrFail($id);
        if (! $retirement->employee || $retirement->employee->departmentId !== $user->employee->departmentId) {
            abort(403, 'Você não pode rejeitar pedidos de outro departamento.');
        }

        $retirement->status       = 'Rejeitado';
        $retirement->observations = $request->input('approvalComment') ?? 'Rejeitado pelo chefe';
        $retirement->save();

        Mail::to($retirement->employee->email)
            ->send(new RetirementResponseNotification($retirement));

        return redirect()->route('dh.pendingRetirements')
            ->with('msg', 'Pedido de reforma rejeitado com sucesso!');
    }

    // ------------------- MÉTODOS DE DOWNLOAD DE PDF -------------------

    // PDF de férias aprovadas por funcionário
    public function downloadEmployeeVacationPdf($employeeId)
    {
        $employee = Employeee::findOrFail($employeeId);
        $departmentHead = Auth::user()->employee;
        if ($employee->departmentId !== $departmentHead->departmentId) {
            abort(403, 'Você não pode acessar dados de funcionário de outro departamento.');
        }

        $vacations = VacationRequest::where('employeeId', $employeeId)
            ->where('approvalStatus', 'Aprovado')
            ->orderByDesc('created_at')
            ->get();

        $pdf = PDF::loadView('departmentHead.employeeVacationPdf', [
            'employee'  => $employee,
            'vacations' => $vacations,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream($employee->fullName . '_Ferias.pdf');
    }

    // PDF de licenças aprovadas por funcionário
    public function downloadEmployeeLeavePdf($employeeId)
    {
        $employee = Employeee::findOrFail($employeeId);
        $departmentHead = Auth::user()->employee;
        if ($employee->departmentId !== $departmentHead->departmentId) {
            abort(403, 'Você não pode acessar dados de funcionário de outro departamento.');
        }

        $leaves = LeaveRequest::where('employeeId', $employeeId)
            ->where('approvalStatus', 'Aprovado')
            ->orderByDesc('created_at')
            ->get();

        $pdf = PDF::loadView('departmentHead.employeeLeavePdf', [
            'employee' => $employee,
            'leaves'   => $leaves,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream($employee->fullName . '_Licenca.pdf');
    }
}
