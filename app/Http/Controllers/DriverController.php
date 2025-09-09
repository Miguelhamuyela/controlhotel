<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Employeee;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        $query = Driver::with('employee','licenseCategory');
        if ($request->filled('startDate')) {
            $query->whereDate('created_at','>=',$request->startDate);
        }
        if ($request->filled('endDate')) {
            $query->whereDate('created_at','<=',$request->endDate);
        }
        $drivers = $query->orderByDesc('id')->get();
        return view('drivers.index', compact('drivers'));
    }

    public function create()
    {
        $employees = Employeee::where('employmentStatus','active')->get();
        return view('drivers.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employeeId'         => 'nullable|exists:employeees,id',
            'fullName'           => 'required_without:employeeId|string|max:100',
            'bi'                 => 'required_without:employeeId|alpha_num|size:16|unique:drivers,bi',
            'licenseNumber'      => 'required|string|max:50|unique:drivers,licenseNumber',
            'licenseCategoryId'  => 'required|exists:license_categories,id',
            'licenseExpiry'      => 'required|date|after:today',
            'status'             => 'required|in:Active,Inactive',
        ], [
            'bi.size'          => 'O bilhete de identidade deve ter exatamente 16 caracteres.',
            'bi.alpha_num'     => 'O bilhete de identidade deve conter apenas letras e números.',
            'licenseNumber.max'=> 'O número da carta de condução não pode exceder 50 caracteres.',
            'licenseExpiry.after' => 'A data de validade da carta deve ser posterior a hoje.',
        ]);

        Driver::create($data);

        return redirect()->route('drivers.index')
                         ->with('msg','Motorista cadastrado com sucesso.');
    }

    public function show(Driver $driver)
    {
        $driver->load('employee','vehicles','licenseCategory');
        return view('drivers.show', compact('driver'));
    }

    public function edit(Driver $driver)
    {
        $employees = Employeee::where('employmentStatus','active')->get();
        return view('drivers.edit', compact('driver','employees'));
    }

    public function update(Request $request, Driver $driver)
    {
        $data = $request->validate([
            'employeeId'         => 'nullable|exists:employeees,id',
            'fullName'           => 'required_without:employeeId|string|max:100',
            'bi'                 => "required_without:employeeId|alpha_num|size:16|unique:drivers,bi,{$driver->id}",
            'licenseNumber'      => "required|string|max:50|unique:drivers,licenseNumber,{$driver->id}",
            'licenseCategoryId'  => 'required|exists:license_categories,id',
            'licenseExpiry'      => 'required|date|after:today',
            'status'             => 'required|in:Active,Inactive',
        ], [
            'bi.size'          => 'O bilhete de identidade deve ter exatamente 16 caracteres.',
            'bi.alpha_num'     => 'O bilhete de identidade deve conter apenas letras e números.',
            'licenseNumber.max'=> 'O número da carta de condução não pode exceder 50 caracteres.',
            'licenseExpiry.after' => 'A data de validade da carta deve ser posterior a hoje.',
        ]);

        $driver->update($data);

        return redirect()->route('drivers.edit',$driver)
                         ->with('msg','Dados do motorista atualizados com sucesso.');
    }

    public function exportFilteredPDF(Request $request)
    {
        $query = Driver::with('employee','licenseCategory');
        if ($request->filled('startDate')) {
            $query->whereDate('created_at','>=',$request->startDate);
        }
        if ($request->filled('endDate')) {
            $query->whereDate('created_at','<=',$request->endDate);
        }
        $filtered = $query->orderByDesc('id')->get();

        $pdf = PDF::loadView('drivers.drivers_pdf', compact('filtered'))
                  ->setPaper('a4','portrait');

        return $pdf->download('Motoristas_Filtrados.pdf');
    }

    public function pdfAll(Request $request)
    {
        $query = Driver::with('employee','licenseCategory');
        if ($request->filled('startDate')) {
            $query->whereDate('created_at','>=',$request->startDate);
        }
        if ($request->filled('endDate')) {
            $query->whereDate('created_at','<=',$request->endDate);
        }
        $all = $query->orderByDesc('id')->get();

        $filename = 'Motoristas' . (
            $request->filled('startDate')||$request->filled('endDate')
            ? '_Filtrados' : ''
        ) . '.pdf';

        $pdf = PDF::loadView('drivers.drivers_pdf', ['filtered'=>$all])
                  ->setPaper('a4','portrait');

        return $pdf->stream($filename);
    }

    public function showPdf(Driver $driver)
    {
        $driver->load('employee','vehicles','licenseCategory');
        $pdf = PDF::loadView('drivers.driver_pdf_individual', compact('driver'))
                  ->setPaper('a4','portrait');

        return $pdf->stream("Motorista_{$driver->id}.pdf");
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();
        return redirect()->route('drivers.index')
                         ->with('msg','Motorista excluído com sucesso.');
    }
}
