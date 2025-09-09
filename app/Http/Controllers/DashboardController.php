<?php

namespace App\Http\Controllers;

use App\Models\Employeee;
use App\Models\Secondment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total de funcionários (ativos + reformados)
        $totalEmployees   = Employeee::whereIn('employmentStatus', ['active', 'retired'])->count();

        // Só ativos
        $activeEmployees  = Employeee::where('employmentStatus', 'active')->count();

        // Só reformados
        $retiredEmployees = Employeee::where('employmentStatus', 'retired')->count();

        // Destacados: apenas funcionários que ainda estão 'active'
        $highlightedEmployees = Secondment::whereHas('employee', function($q) {
                $q->where('employmentStatus', 'active');
            })
            ->distinct('employeeId')
            ->count('employeeId');

        // Contratações por mês (para gráficos)
        $hiredPerMonth = Employeee::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get();

        $months    = [
            1 => 'Janeiro',   2 => 'Fevereiro', 3 => 'Março',
            4 => 'Abril',     5 => 'Maio',      6 => 'Junho',
            7 => 'Julho',     8 => 'Agosto',    9 => 'Setembro',
            10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
        ];

        $hiredData = array_fill_keys(array_values($months), 0);
        foreach ($hiredPerMonth as $data) {
            $hiredData[$months[$data->month]] = $data->count;
        }

        return view('dashboard.index', compact(
            'totalEmployees',
            'activeEmployees',
            'retiredEmployees',
            'highlightedEmployees',
            'hiredData'
        ));
    }
}
