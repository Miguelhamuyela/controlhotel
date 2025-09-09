<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\SalaryPayment;
use App\Models\Employeee;
use App\Models\AttendanceRecord;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Mail\SalaryPaidNotification;

class SalaryPaymentController extends Controller
{
    public function index(Request $request)
    {
        // Construção da query base (funcionário x todos)
        $query = auth()->user()->role === 'employee'
            ? SalaryPayment::where('employeeId', auth()->user()->employee->id)
                ->with(['employee.department','employee.employeeType'])
            : SalaryPayment::with(['employee.department','employee.employeeType']);

        // Filtros de data
        if ($request->filled('startDate')) {
            $query->whereDate('paymentDate', '>=', $request->startDate);
        }
        if ($request->filled('endDate')) {
            $query->whereDate('paymentDate', '<=', $request->endDate);
        }

        // Obtenção e tradução dos status
        $salaryPayments = $query->orderByDesc('created_at')->get();
        $salaryPayments->each(function($p){
            $p->paymentStatus = $this->translateStatus($p->paymentStatus);
        });

        return view('salaryPayment.index', [
            'salaryPayments' => $salaryPayments,
            'filters'        => $request->only('startDate','endDate'),
        ]);
    }

    public function create()
    {
        $employees = Employeee::orderBy('fullName')->get();
        return view('salaryPayment.create', compact('employees'));
    }

    public function searchEmployee(Request $request)
    {
        $request->validate(['employeeSearch'=>'required|string']);
        $term = $request->employeeSearch;
        $employee = Employeee::where('id',$term)
            ->orWhere('fullName','LIKE',"%{$term}%")
            ->first();

        if (!$employee) {
            return back()
                ->withErrors(['employeeSearch'=>'Funcionário não encontrado!'])
                ->withInput();
        }

        return view('salaryPayment.create', compact('employee'));
    }

    public function calculateDiscount(Request $request)
    {
        $request->validate([
            'employeeId' => 'required|exists:employeees,id',
            'baseSalary' => 'required|numeric',
            'subsidies'  => 'required|numeric',
            'workMonth'  => 'required|date_format:Y-m',
        ]);

        $refDate    = Carbon::parse("{$request->workMonth}-01");
        $startDate  = $refDate->copy()->startOfMonth();
        $endDate    = $refDate->copy()->endOfMonth();
        $totalWeekdays = $this->countWeekdays($startDate, $endDate);

        $records = AttendanceRecord::where('employeeId', $request->employeeId)
            ->whereBetween('recordDate', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ])->get();

        $presentDays   = $records->where('status', 'Presente')->count();
        $justifiedDays = $records->whereIn('status', ['Férias','Licença','Doença','Teletrabalho'])->count();
        $absentDays    = max(0, $totalWeekdays - ($presentDays + $justifiedDays));

        $dailyRate = $totalWeekdays > 0
            ? ($request->baseSalary + $request->subsidies) / $totalWeekdays
            : 0;

        $discount = round($dailyRate * $absentDays, 2);

        return response()->json([
            'absentDays' => $absentDays,
            'discount'   => $discount,
        ]);
    }

    private function countWeekdays(Carbon $start, Carbon $end)
    {
        $days    = 0;
        $current = $start->copy();
        while ($current->lte($end)) {
            if ($current->isWeekday()) {
                $days++;
            }
            $current->addDay();
        }
        return $days;
    }

    public function store(Request $request)
    {
        $this->validateRequest($request);

        $data    = $this->formatRequest($request);
        $payment = SalaryPayment::create($data);

        // Envia e-mail se status for "Completed"
        if ($payment->paymentStatus === 'Completed') {
            Mail::to($payment->employee->email)
                ->queue(new SalaryPaidNotification($payment));
        }

        return redirect()->route('salaryPayment.index')
                         ->with('msg','Pagamento de salário registrado com sucesso.');
    }

    public function show($id)
    {
        $salaryPayment = SalaryPayment::with(['employee.department','employee.employeeType'])->findOrFail($id);
        // Traduz status antes de exibir
        $salaryPayment->paymentStatus = $this->translateStatus($salaryPayment->paymentStatus);
        return view('salaryPayment.show', compact('salaryPayment'));
    }

    public function edit($id)
    {
        $salaryPayment = SalaryPayment::findOrFail($id);
        $salaryPayment->paymentStatus = $this->translateStatus($salaryPayment->paymentStatus);
        $employees     = Employeee::orderBy('fullName')->get();
        return view('salaryPayment.edit', compact('salaryPayment','employees'));
    }

    public function update(Request $request, $id)
    {
        $this->validateRequest($request);
        $payment = SalaryPayment::findOrFail($id);
        $data    = $this->formatRequest($request);
        $payment->update($data);

        // Notifica se mudou para "Completed"
        if ($payment->paymentStatus === 'Completed') {
            Mail::to($payment->employee->email)
                ->queue(new SalaryPaidNotification($payment));
        }

        return redirect()->route('salaryPayment.index')
                         ->with('msg','Pagamento de salário atualizado com sucesso.');
    }

    public function destroy($id)
    {
        SalaryPayment::destroy($id);
        return redirect()->route('salaryPayment.index')
                         ->with('msg','Pagamento de salário removido com sucesso.');
    }

    public function pdfAll()
    {
        $payments = SalaryPayment::with(['employee.department','employee.employeeType'])
            ->latest()->get();

        // Traduz status antes do PDF
        $payments->each(function($p){
            $p->paymentStatus = $this->translateStatus($p->paymentStatus);
        });

        $pdf = PDF::loadView('salaryPayment.salaryPayment_pdf', [
            'salaryPayments' => $payments
        ])->setPaper('a4','landscape');

        return $pdf->stream('RelatorioPagamentosSalarial.pdf');
    }

    public function pdfPeriod(Request $request)
    {
        $request->validate([
            'startDate'=>'required|date',
            'endDate'  =>'required|date|after_or_equal:startDate',
        ]);

        $payments = SalaryPayment::with(['employee.department','employee.employeeType'])
            ->whereBetween('paymentDate',[$request->startDate,$request->endDate])
            ->latest()->get();

        $payments->each(function($p){
            $p->paymentStatus = $this->translateStatus($p->paymentStatus);
        });

        $pdf = PDF::loadView('salaryPayment.salaryPayment_period_pdf', [
            'salaryPayments' => $payments,
            'startDate'      => $request->startDate,
            'endDate'        => $request->endDate,
        ])->setPaper('a4','landscape');

        return $pdf->stream("Pagamentos_{$request->startDate}_to_{$request->endDate}.pdf");
    }

    public function pdfByEmployee(Request $request, $employeeId)
    {
        $year  = $request->input('year', Carbon::now()->year);
        $start = Carbon::create($year,1,1)->startOfDay();
        $end   = Carbon::create($year,12,31)->endOfDay();

        $payments = SalaryPayment::with(['employee.department','employee.employeeType'])
            ->where('employeeId',$employeeId)
            ->whereBetween('paymentDate',[$start->toDateString(),$end->toDateString()])
            ->orderBy('paymentDate')
            ->get();

        $payments->each(function($p){
            $p->paymentStatus = $this->translateStatus($p->paymentStatus);
        });

        $pdf = PDF::loadView('salaryPayment.salaryPayment_employee_pdf', [
            'payments' => $payments,
            'employee' => Employeee::findOrFail($employeeId),
            'year'     => $year,
        ])->setPaper('a4','landscape');

        return $pdf->stream("Salarios_{$employeeId}_{$year}.pdf");
    }

    // requisitos da validação do salario 

    protected function validateRequest(Request $r)
    {
         $r->validate([
            'employeeId'    =>'required|exists:employeees,id',
            'workMonth'     =>'required|date_format:Y-m',
            'baseSalary'    =>'required|numeric|min:0',
            'subsidies'     =>'required|numeric|min:0',
            'irtRate'       =>'required|numeric|min:0',
            'inssRate'      =>'required|numeric|min:0',
            'discount' => [
                            'nullable',
                            function($attribute, $value, $fail) {
                                // Primeiro, limpa pontos (de milhar) e troca vírgula por ponto
                                $clean = str_replace(['.', ','], ['', '.'], $value);

                                // Se não for numérico, mensagem de formato
                                if (!is_numeric($clean)) {
                                    return $fail('O desconto deve ser um número.');
                                }

                                // Se for negativo, mensagem de valor mínimo
                                if ((float)$clean < 0) {
                                    return $fail('O desconto não pode ser negativo.');
                                }
                            },
                        ],
            'paymentDate'   =>'nullable|date',
            'paymentStatus' =>'required|in:Pending,Completed,Failed',
            'paymentComment'=>'nullable|string',
        ], [
            'baseSalary.numeric' => 'O salário básico deve ser um número.',
            'baseSalary.min'     => 'O salário básico não pode ser negativo.',
            'subsidies.numeric'  => 'O subsídio deve ser um número.',
            'subsidies.min'      => 'O subsídio não pode ser negativo.',
            'irtRate.numeric'    => 'A taxa de IRT deve ser um número.',
            'irtRate.min'        => 'A taxa de IRT não pode ser negativa.',
            'inssRate.numeric'   => 'A taxa de INSS deve ser um número.',
            'inssRate.min'       => 'A taxa de INSS não pode ser negativa.',
            'discount.numeric'   => 'O desconto deve ser um número.',
            'discount.min'       => 'O desconto não pode ser negativo.',
            
        ]);

    }

    // Formata e calcula campos
    protected function formatRequest(Request $r)
        {
            $data = $r->all();

            // converte "Y-m" para "Y-m-d"
            $data['workMonth'] = Carbon::parse($data['workMonth'] . '-01')
                                    ->toDateString();

            // Campos que vêm no formato brasileiro da permissão de uso de . para separar as casas decimais  "1.246.700,00"
            foreach (['baseSalary', 'subsidies', 'irtRate', 'inssRate', 'discount'] as $f) {
                // 1) remove separadores de milhar (pontos)
                $clean = str_replace('.', '', $data[$f]);
                // 2) troca vírgula decimal por ponto
                $clean = str_replace(',', '.', $clean);
                // 3) converte para float corretamente
                $data[$f] = floatval($clean);
            }

            // se não vier data de pagamento, usa hoje
            if (empty($data['paymentDate'])) {
                $data['paymentDate'] = Carbon::now()->toDateString();
            }

            // calcula salário líquido
            $gross   = $data['baseSalary'] + $data['subsidies'];
            $irtVal  = $gross * ($data['irtRate']  / 100);
            $inssVal = $gross * ($data['inssRate'] / 100);
            $data['salaryAmount'] = round(
                $gross - $irtVal - $inssVal - ($data['discount'] ?? 0),
                2
            );

            return $data;
        }


    // Mapeamento de status
    private function translateStatus(string $status): string
        {
            switch ($status) {
                case 'Pending':
                    return 'Pendente';
                case 'Completed':
                    return 'Concluído';
                case 'Failed':
                    return 'Falhou';
                default:
                    return $status;
            }
        }
}
