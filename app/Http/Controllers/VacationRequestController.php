<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VacationRequest;
use App\Models\Employeee;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VacationRequestController extends Controller
{
    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = VacationRequest::with('employee');

        if (in_array($user->role, ['employee','intern'])) {
            $query->where('employeeId', $user->employee->id ?? 0);
        } elseif ($user->role === 'department_head') {
            $dept = $user->employee->departmentId ?? null;
            $query->whereHas('employee', function($q) use($dept) {
                $q->where('departmentId',$dept)
                  ->where('employmentStatus','active');
            });
        }

        if ($request->filled('startDate')) {
            $query->whereDate('vacationStart','>=',$request->startDate);
        }
        if ($request->filled('endDate')) {
            $query->whereDate('vacationEnd','<=',$request->endDate);
        }
        if ($request->filled('status') && $request->status !== 'Todos') {
            $query->where('approvalStatus',$request->status);
        }

        $data = $query->orderByDesc('id')->get();

        return view('vacationRequest.index', [
            'data'    => $data,
            'filters' => $request->only('startDate','endDate','status'),
        ]);
    }

    public function exportFilteredPDF(Request $request)
    {
        $user  = Auth::user();
        $query = VacationRequest::with('employee');

        if (in_array($user->role, ['employee','intern'])) {
            $query->where('employeeId', $user->employee->id ?? 0);
        } elseif ($user->role === 'department_head') {
            $dept = $user->employee->departmentId ?? null;
            $query->whereHas('employee', function($q) use($dept) {
                $q->where('departmentId',$dept)
                  ->where('employmentStatus','active');
            });
        }

        if ($request->filled('startDate')) {
            $query->whereDate('vacationStart','>=',$request->startDate);
        }
        if ($request->filled('endDate')) {
            $query->whereDate('vacationEnd','<=',$request->endDate);
        }
        if ($request->filled('status') && $request->status !== 'Todos') {
            $query->where('approvalStatus',$request->status);
        }

        $all = $query->orderByDesc('id')->get();

        $pdf = PDF::loadView('vacationRequest.pdf', ['allRequests'=>$all])
                  ->setPaper('a3','portrait');

        return $pdf->download('RelatorioPedidosFerias_Filtrados.pdf');
    }

    public function create()
    {
        $user = Auth::user();
        $vacationTypes = ['22 dias úteis','11 dias úteis'];

        if (in_array($user->role, ['admin','director','department_head'])) {
            return view('vacationRequest.createSearch', compact('vacationTypes'));
        }

        $employee = $user->employee;
        return view('vacationRequest.createEmployee', compact('employee','vacationTypes'));
    }

    public function searchEmployee(Request $request)
    {
        $request->validate(['employeeSearch'=>'required|string']);
        $term = $request->employeeSearch;

        $employee = Employeee::where('employmentStatus','active')
            ->where(fn($q)=>
                $q->where('id',$term)
                  ->orWhere('fullName','LIKE',"%$term%")
            )->first();

        if (!$employee) {
            return back()->withErrors(['employeeSearch'=>'Funcionário não encontrado!'])->withInput();
        }

        $vacationTypes = ['22 dias úteis','11 dias úteis'];
        return view('vacationRequest.createSearch', compact('employee','vacationTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employeeId'        => 'required|exists:employeees,id',
            'vacationType'      => 'required|in:22 dias úteis,11 dias úteis',
            'vacationStart'     => 'required|date',
            'manualHolidays'    => 'nullable|array',
            'manualHolidays.*'  => 'nullable|date',
            'supportDocument'   => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xlsx',
        ],[
            'vacationType.in' => 'Selecione apenas 22 dias úteis ou 11 dias úteis.'
        ]);

        $start = Carbon::parse($request->vacationStart);
        $needed = intval(explode(' ',$request->vacationType)[0]);
        $holidays = collect($request->manualHolidays ?? [])
            ->map(fn($d)=> Carbon::parse($d)->toDateString())
            ->all();

        $end = $start->copy();
        $count = 0;
        while($count < $needed) {
            $end->addDay();
            if ($end->isWeekend()) continue;
            if (in_array($end->toDateString(), $holidays)) continue;
            $count++;
        }
        if ($end->isWeekend()) {
            $end->next(Carbon::MONDAY);
        }

        $path = $orig = null;
        if ($file = $request->file('supportDocument')) {
            $orig = $file->getClientOriginalName();
            $path = $file->store('vacation_requests','public');
        }

        VacationRequest::create([
            'employeeId'       => $request->employeeId,
            'vacationType'     => $request->vacationType,
            'vacationStart'    => $start->toDateString(),
            'vacationEnd'      => $end->toDateString(),
            'reason'           => $request->reason,
            'manualHolidays'   => $holidays,
            'supportDocument'  => $path,
            'originalFileName' => $orig,
            'approvalStatus'   => 'Pendente',
            'approvalComment'  => null,
        ]);

        return redirect()->route('vacationRequest.index')
                         ->with('msg','Pedido de férias registrado com sucesso!');
    }

    public function show($id)
    {
        $data = VacationRequest::with('employee')->findOrFail($id);
        return view('vacationRequest.show', compact('data'));
    }

    public function edit($id)
    {
        $data = VacationRequest::findOrFail($id);
        $vacationTypes = ['22 dias úteis','11 dias úteis'];
        return view('vacationRequest.edit', compact('data','vacationTypes'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'vacationType'      => 'required|in:22 dias úteis,11 dias úteis',
            'vacationStart'     => 'required|date',
            'manualHolidays'    => 'nullable|array',
            'manualHolidays.*'  => 'required|date',
            'supportDocument'   => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xlsx',
        ],[
            'vacationType.in' => 'Selecione apenas 22 dias úteis ou 11 dias úteis.'
        ]);

        $start = Carbon::parse($request->vacationStart);
        $needed = intval(explode(' ',$request->vacationType)[0]);
        $holidays = collect($request->manualHolidays ?? [])
            ->map(fn($d)=> Carbon::parse($d)->toDateString())
            ->all();

        $end = $start->copy();
        $count = 0;
        while($count < $needed) {
            $end->addDay();
            if ($end->isWeekend()) continue;
            if (in_array($end->toDateString(), $holidays)) continue;
            $count++;
        }
        if ($end->isWeekend()) {
            $end->next(Carbon::MONDAY);
        }

        $vac = VacationRequest::findOrFail($id);
        $path = $vac->supportDocument;
        $orig = $vac->originalFileName;
        if ($file = $request->file('supportDocument')) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            $orig = $file->getClientOriginalName();
            $path = $file->store('vacation_requests','public');
        }

        $vac->update([
            'vacationType'     => $request->vacationType,
            'vacationStart'    => $start->toDateString(),
            'vacationEnd'      => $end->toDateString(),
            'reason'           => $request->reason,
            'manualHolidays'   => $holidays,
            'supportDocument'  => $path,
            'originalFileName' => $orig,
        ]);

        return redirect()->route('vacationRequest.edit',$id)
                         ->with('msg','Pedido de férias atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $vac = VacationRequest::findOrFail($id);
        if ($vac->supportDocument) {
            Storage::disk('public')->delete($vac->supportDocument);
        }
        $vac->delete();
        return redirect()->route('vacationRequest.index')
                         ->with('msg','Pedido de férias removido.');
    }

    public function pdfAll(Request $request)
    {
        $user  = Auth::user();
        $query = VacationRequest::with('employee');

        if (in_array($user->role, ['employee','intern'])) {
            $query->where('employeeId',$user->employee->id ?? 0);
        } elseif ($user->role === 'department_head') {
            $dept = $user->employee->departmentId ?? null;
            $query->whereHas('employee', function($q) use($dept) {
                $q->where('departmentId',$dept)
                  ->where('employmentStatus','active');
            });
        }

        if ($request->filled('startDate')) {
            $query->whereDate('vacationStart','>=',$request->startDate);
        }
        if ($request->filled('endDate')) {
            $query->whereDate('vacationEnd','<=',$request->endDate);
        }
        if ($request->filled('status') && $request->status!=='Todos') {
            $query->where('approvalStatus',$request->status);
        }

        $all = $query->orderByDesc('id')->get();

        $pdf = PDF::loadView('vacationRequest.vacationRequest_pdf',['allRequests'=>$all])
                  ->setPaper('a3','portrait');

        return $pdf->stream('RelatorioPedidosFerias.pdf');
    }

    public function departmentSummary()
    {
        $rows = VacationRequest::with('employee.department')->get();
        $summary = [];
        foreach ($rows as $r) {
            $dept = $r->employee->department->title ?? '—';
            if (!isset($summary[$dept])) {
                $summary[$dept] = ['department'=>$dept,'total'=>0,'approved'=>0,'pending'=>0,'rejected'=>0];
            }
            $summary[$dept]['total']++;
            $st = mb_strtolower($r->approvalStatus);
            if (in_array($st,['approved','aprovado']))      $summary[$dept]['approved']++;
            elseif (in_array($st,['rejected','recusado']))  $summary[$dept]['rejected']++;
            else                                            $summary[$dept]['pending']++;
        }
        return view('vacationRequest.departmentSummary',['summaryData'=>array_values($summary)]);
    }

    public function approval($departmentId)
    {
        $data = VacationRequest::with('employee')
            ->whereHas('employee', fn($q)=> $q->where('departmentId',$departmentId))
            ->orderByDesc('id')
            ->get();
        return view('vacationRequest.approval',compact('data'));
    }

    public function updateApproval(Request $request, $id)
    {
        $request->validate([
            'approvalStatus'  => 'required|in:Aprovado,Recusado,Pendente',
            'approvalComment' => 'nullable|string',
        ]);
        $vac = VacationRequest::findOrFail($id);
        $vac->approvalStatus  = $request->approvalStatus;
        $vac->approvalComment = $request->approvalComment;
        $vac->save();
        return back()->with('msg','Status atualizado com sucesso!');
    }
}
