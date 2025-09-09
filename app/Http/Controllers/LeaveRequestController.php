<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\Employeee;
use App\Models\Department;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = LeaveRequest::with(['employee','department','leaveType']);

        // filtros
        if ($request->filled('startDate')) {
            $query->whereDate('leaveStart', '>=', $request->startDate);
        }
        if ($request->filled('endDate')) {
            $query->whereDate('leaveEnd', '<=', $request->endDate);
        }
        if ($request->filled('status') && $request->status !== 'Todos') {
            $query->where('approvalStatus', $request->status);
        }

        $data = $query->orderByDesc('id')->get();

        return view('leaveRequest.index', [
            'data'    => $data,
            'filters' => [
                'startDate' => $request->startDate,
                'endDate'   => $request->endDate,
                'status'    => $request->status,
            ],
        ]);
    }

    public function exportFilteredPDF(Request $request)
    {
        $query = LeaveRequest::with(['employee','department','leaveType']);

        if ($request->filled('startDate')) {
            $query->whereDate('leaveStart', '>=', $request->startDate);
        }
        if ($request->filled('endDate')) {
            $query->whereDate('leaveEnd', '<=', $request->endDate);
        }
        if ($request->filled('status') && $request->status !== 'Todos') {
            $query->where('approvalStatus', $request->status);
        }

        $filtered = $query->orderByDesc('id')->get();

        $pdf = PDF::loadView('leaveRequest.leaveRequest_pdf', ['allLeaveRequests' => $filtered])
                  ->setPaper('a3', 'landscape');

        return $pdf->download('RelatorioPedidosLicenca_Filtrados.pdf');
    }

    // ... demais métodos originais (create, searchEmployee, store, show, edit, update, destroy, pdfAll) permanecem inalterados



    /**
     * Exibe o formulário para criar um novo pedido de licença.
     */
    public function create()
    {
        $user = Auth::user();
        $leaveTypes = LeaveType::all();
        if (in_array($user->role, ['admin', 'director', 'department_head'])) {
            $departments = Department::all();
            return view('leaveRequest.create', compact('departments', 'leaveTypes'));
        } else {
            $employee = $user->employee;
            return view('leaveRequest.createEmployee', compact('employee', 'leaveTypes'));
        }
    }

    /**
     * Busca um funcionário por ID ou Nome.
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
            })
            ->first();
        if (!$employee) {
            return redirect()->back()
                ->withErrors(['employeeSearch' => 'Funcionário não encontrado!'])
                ->withInput();
        }
        $currentDepartment = $employee->department;
        $departments = Department::all();
        $leaveTypes = LeaveType::all();
        return view('leaveRequest.create', [
            'departments'       => $departments,
            'leaveTypes'        => $leaveTypes,
            'employee'          => $employee,
            'currentDepartment' => $currentDepartment,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'employeeId'   => 'required|integer|exists:employeees,id',
            'departmentId' => 'required|integer|exists:departments,id',
            'leaveTypeId'  => 'required|integer|exists:leave_types,id',
            'leaveStart'   => 'required|date',
            'leaveEnd'     => 'required|date|after_or_equal:leaveStart',
            'reason'       => 'nullable|string',
        ]);

        $data = $request->all();
        $data['approvalStatus'] = 'Pendente';
        $data['approvalComment'] = null;

        LeaveRequest::create($data);

        return redirect()->route('leaveRequest.index')
                         ->with('msg', 'Pedido de licença registrado com sucesso!');
    }

    public function show($id)
    {
        $data = LeaveRequest::with(['employee', 'department', 'leaveType'])->findOrFail($id);
        return view('leaveRequest.show', compact('data'));
    }

    public function edit($id)
    {
        $data = LeaveRequest::findOrFail($id);
        $departments = Department::all();
        $leaveTypes = LeaveType::all();
        return view('leaveRequest.edit', compact('data', 'departments', 'leaveTypes'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'leaveTypeId' => 'required|integer|exists:leave_types,id',
            'leaveStart'  => 'required|date',
            'leaveEnd'    => 'required|date|after_or_equal:leaveStart',
            'reason'      => 'nullable|string',
        ]);
        $leaveRequest = LeaveRequest::findOrFail($id);
        $leaveRequest->update($request->all());
        return redirect()->route('leaveRequest.index')
                         ->with('msg', 'Pedido de licença atualizado com sucesso!');
    }

    public function destroy($id)
    {
        LeaveRequest::destroy($id);
        return redirect()->route('leaveRequest.index');
    }

    /**
     * Gera um PDF com todos os pedidos de licença.
     */
    public function pdfAll(Request $request)
    {
        $query = LeaveRequest::with(['employee','department','leaveType']);

        if ($request->filled('startDate')) {
            $query->whereDate('leaveStart', '>=', $request->startDate);
        }
        if ($request->filled('endDate')) {
            $query->whereDate('leaveEnd', '<=', $request->endDate);
        }
        if ($request->filled('status') && $request->status !== 'Todos') {
            $query->where('approvalStatus', $request->status);
        }

        $allLeaveRequests = $query->orderByDesc('id')->get();

        $pdf = PDF::loadView('leaveRequest.leaveRequest_pdf', compact('allLeaveRequests'))
                  ->setPaper('a3', 'landscape');

        $filename = 'RelatorioPedidosLicenca'
                  . (($request->filled('startDate') || $request->filled('status')) ? '_Filtrado' : '')
                  . '.pdf';

        return $pdf->stream($filename);
    }
}
