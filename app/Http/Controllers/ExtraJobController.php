<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExtraJob;
use App\Models\Employeee;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Validation\ValidationException;

class ExtraJobController extends Controller
{
    /**
     * Validates that the raw value contains only digits, dots, or commas,
     * without letters, symbols, or minus signs.
     */
    private function validateRawCurrency(string $rawValue, string $fieldName)
    {
        if (strpos($rawValue, '-') !== false || preg_match('/[^0-9\.,]/', $rawValue)) {
            throw ValidationException::withMessages([
                $fieldName => 'O valor só pode conter dígitos, pontos ou vírgulas, e não pode ser negativo.'
            ]);
        }
    }

    /**
     * Normalizes the string: removes dots, replaces comma with dot,
     * to correctly convert to float. If null or empty, returns 0.0.
     */
    private function normalizeCurrency($rawValue): float
    {
        if ($rawValue === null || trim($rawValue) === '') {
            return 0.0;
        }
        $cleanValue = str_replace('.', '', (string)$rawValue);
        $cleanValue = str_replace(',', '.', $cleanValue);
        return (float)$cleanValue;
    }

    public function index()
    {
        $jobs = ExtraJob::with('employees')
                        ->orderByDesc('created_at')
                        ->get();
        return view('Extras.index', compact('jobs'));
    }

    public function create()
    {
        return view('Extras.create');
    }

    public function searchEmployee(Request $request)
    {
        $query = $request->get('q');
        $employees = Employeee::with('department','admin')
                        ->where('fullName', 'LIKE', "%{$query}%")
                        ->orderBy('fullName')
                        ->limit(10)
                        ->get();

        $results = $employees->map(function($employee) {
            if ($employee->admin && $employee->admin->role === 'director') {
                $extraInfo = ucfirst($employee->admin->directorType);
            } elseif ($employee->admin && $employee->admin->role === 'department_head') {
                $extraInfo = 'Chefe de ' . ($employee->department->title ?? '—');
            } else {
                $extraInfo = 'Depto: ' . ($employee->department->title ?? '-');
            }
            return [
                'id'    => $employee->id,
                'text'  => $employee->fullName,
                'extra' => $extraInfo,
            ];
        });

        return response()->json($results);
    }

    public function store(Request $request)
    {
        // 1) Validate raw total
        $rawTotalValue = (string) $request->input('totalValue', '');
        $this->validateRawCurrency($rawTotalValue, 'totalValue');

        // 2) Validate raw bonuses
        $rawBonuses = $request->input('bonus', []);
        foreach ($rawBonuses as $employeeId => $rawValue) {
            if ($rawValue !== null) {
                $this->validateRawCurrency((string)$rawValue, "bonus.{$employeeId}");
            }
        }

        // 3) Normalize total and bonuses
        $totalValue = $this->normalizeCurrency($rawTotalValue);
        $normalizedBonuses = [];
        foreach ($request->input('bonus', []) as $employeeId => $rawValue) {
            $normalizedBonuses[$employeeId] = $this->normalizeCurrency($rawValue);
        }

        // 4) Merge for validation
        $request->merge([
            'totalValue' => $totalValue,
            'bonus'      => $normalizedBonuses
        ]);

        // 5) Laravel validations
        $request->validate([
            'title'        => 'required|string',
            'totalValue'   => 'required|numeric|min:0',
            'status'       => 'required|in:Pending,Approved,Rejected',
            'participants' => 'required|array|min:1',
            'bonus'        => 'nullable|array',
            'bonus.*'      => 'nullable|numeric|min:0',
        ], [
            'totalValue.min' => 'O valor total não pode ser negativo.',
            'bonus.*.min'    => 'Os ajustes não podem ser negativos.',
            'status.required' => 'O status é obrigatório.',
            'status.in'      => 'O status deve ser Pendente, Aprovado ou Recusado.',
        ]);

        // 6) Check if sum of adjustments <= total
        if (array_sum($normalizedBonuses) > $totalValue) {
            return back()
                ->withErrors(['bonus' => 'A soma dos ajustes não pode exceder o valor total.'])
                ->withInput();
        }

        // 7) Distribution calculation
        $participants = $request->participants;
        $actualBonuses = [];
        foreach ($participants as $employeeId) {
            $adjustment = $normalizedBonuses[$employeeId] ?? 0;
            if ($adjustment > 0) {
                $actualBonuses[$employeeId] = $adjustment;
            }
        }
        $fixedShare = array_sum($actualBonuses);
        $variableEmployees = array_diff($participants, array_keys($actualBonuses));
        $variableCount = count($variableEmployees);
        $remainingValue = $totalValue - $fixedShare;
        $baseShare = $variableCount
                               ? round($remainingValue / $variableCount, 2)
                               : 0;

        // 8) Create ExtraJob
        $job = ExtraJob::create([
            'title'      => $request->title,
            'totalValue' => $totalValue,
            'status'     => $request->status,
        ]);

        // 9) Attach participants
        foreach ($participants as $employeeId) {
            if (isset($actualBonuses[$employeeId])) {
                $assignedValue = $actualBonuses[$employeeId];
                $bonusAdjustment = $assignedValue - $baseShare;
            } else {
                $assignedValue = $baseShare;
                $bonusAdjustment = 0;
            }
            $job->employees()->attach($employeeId, [
                'bonusAdjustment' => $bonusAdjustment,
                'assignedValue'   => $assignedValue,
            ]);
        }

        return redirect()->route('extras.index')
                         ->with('msg','Trabalho Extra criado com sucesso.');
    }

    public function show($id)
    {
        $job = ExtraJob::with('employees')->findOrFail($id);
        return view('Extras.show', compact('job'));
    }

    public function edit($id)
    {
        $job = ExtraJob::with('employees')->findOrFail($id);
        return view('Extras.edit', compact('job'));
    }

    public function update(Request $request, $id)
    {
        // Same steps as store()

        $rawTotalValue = (string) $request->input('totalValue', '');
        $this->validateRawCurrency($rawTotalValue, 'totalValue');

        $rawBonuses = $request->input('bonus', []);
        foreach ($rawBonuses as $employeeId => $rawValue) {
            if ($rawValue !== null) {
                $this->validateRawCurrency((string)$rawValue, "bonus.{$employeeId}");
            }
        }

        $totalValue = $this->normalizeCurrency($rawTotalValue);
        $normalizedBonuses = [];
        foreach ($request->input('bonus', []) as $employeeId => $rawValue) {
            $normalizedBonuses[$employeeId] = $this->normalizeCurrency($rawValue);
        }

        $request->merge([
            'totalValue' => $totalValue,
            'bonus'      => $normalizedBonuses
        ]);

        $request->validate([
            'title'        => 'required|string',
            'totalValue'   => 'required|numeric|min:0',
            'status'       => 'required|in:Pending,Approved,Rejected',
            'participants' => 'required|array|min:1',
            'bonus'        => 'nullable|array',
            'bonus.*'      => 'nullable|numeric|min:0',
        ], [
            'totalValue.min' => 'O valor total não pode ser negativo.',
            'bonus.*.min'    => 'Os ajustes não podem ser negativos.',
            'status.required' => 'O status é obrigatório.',
            'status.in'      => 'O status deve ser Pendente, Aprovado ou Recusado.',
        ]);

        if (array_sum($normalizedBonuses) > $totalValue) {
            return back()
                ->withErrors(['bonus' => 'A soma dos ajustes não pode exceder o valor total.'])
                ->withInput();
        }

        $participants = $request->participants;
        $actualBonuses = [];
        foreach ($participants as $employeeId) {
            $adjustment = $normalizedBonuses[$employeeId] ?? 0;
            if ($adjustment > 0) {
                $actualBonuses[$employeeId] = $adjustment;
            }
        }
        $fixedShare = array_sum($actualBonuses);
        $variableEmployees = array_diff($participants, array_keys($actualBonuses));
        $variableCount = count($variableEmployees);
        $remainingValue = $totalValue - $fixedShare;
        $baseShare = $variableCount
                               ? round($remainingValue / $variableCount, 2)
                               : 0;

        $job = ExtraJob::findOrFail($id);
        $job->update([
            'title'      => $request->title,
            'totalValue' => $totalValue,
            'status'     => $request->status,
        ]);
        
        // Remove all existing participants and add new ones
        $job->employees()->detach();
        
        foreach ($participants as $employeeId) {
            if (isset($actualBonuses[$employeeId])) {
                $assignedValue = $actualBonuses[$employeeId];
                $bonusAdjustment = $assignedValue - $baseShare;
            } else {
                $assignedValue = $baseShare;
                $bonusAdjustment = 0;
            }
            $job->employees()->attach($employeeId, [
                'bonusAdjustment' => $bonusAdjustment,
                'assignedValue'   => $assignedValue,
            ]);
        }

        return redirect()->route('extras.index')
                         ->with('msg','Trabalho Extra atualizado.');
    }

    public function destroy($id)
    {
        ExtraJob::destroy($id);
        return redirect()->route('extras.index')
                         ->with('msg','Trabalho Extra removido.');
    }

    public function pdfAll()
    {
        $jobs = ExtraJob::with('employees')->orderByDesc('created_at')->get();
        $pdf  = PDF::loadView('Extras.extraJobs_pdf', compact('jobs'))
                   ->setPaper('a4','landscape');
        return $pdf->stream('ExtraJobs_All.pdf');
    }

    public function pdfShow($id)
    {
        $job = ExtraJob::with('employees')->findOrFail($id);
        $pdf = PDF::loadView('Extras.extraJob_pdf', compact('job'))
                  ->setPaper('a4','landscape');
        return $pdf->stream("ExtraJob_{$job->id}.pdf");
    }
}

