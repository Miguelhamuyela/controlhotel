<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Driver;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::with('drivers')->orderByDesc('id')->get();
        return view('vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        $drivers = Driver::where('status','Active')->get();
        return view('vehicles.create', compact('drivers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'plate'           => 'required|string|unique:vehicles,plate',
            'model'           => 'required|string',
            'brand'           => 'required|string',
            'yearManufacture' => 'required|integer',
            'color'           => 'required|string',
            'loadCapacity'    => 'required|numeric',
            'status'          => 'required|in:Available,UnderMaintenance,Unavailable',
            'notes'           => 'nullable|string',
            'driverId'        => 'nullable|exists:drivers,id',
            'startDate'       => 'nullable|date',
        ]);

        $vehicle = Vehicle::create($data);

        if (!empty($data['driverId'])) {
            $start = $data['startDate'] ?? now()->toDateString();
            $vehicle->drivers()->attach($data['driverId'], [
                'startDate' => $start,
            ]);
        }

        return redirect()->route('vehicles.index')
                         ->with('msg','Viatura cadastrada com sucesso.');
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load('drivers','maintenance');
        return view('vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        $drivers = Driver::where('status','Active')->get();
        return view('vehicles.edit', compact('vehicle','drivers'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $data = $request->validate([
            'plate'           => "required|string|unique:vehicles,plate,{$vehicle->id}",
            'model'           => 'required|string',
            'brand'           => 'required|string',
            'yearManufacture' => 'required|integer',
            'color'           => 'required|string',
            'loadCapacity'    => 'required|numeric',
            'status'          => 'required|in:Available,UnderMaintenance,Unavailable',
            'notes'           => 'nullable|string',
            'driverId'        => 'nullable|exists:drivers,id',
            'startDate'       => 'nullable|date',
            'endDate'         => 'nullable|date|after_or_equal:startDate',
        ]);

        $vehicle->update($data);

        if (!empty($data['driverId'])) {
            $vehicle->drivers()
                    ->wherePivotNull('endDate')
                    ->updateExistingPivot(
                        $vehicle->drivers->pluck('id')->toArray(),
                        ['endDate' => now()->toDateString()]
                    );
           
            $start = $data['startDate'] ?? now()->toDateString();
            $vehicle->drivers()->attach($data['driverId'], ['startDate' => $start]);
        }

        return redirect()->route('vehicles.edit',$vehicle)
                         ->with('msg','Viatura atualizada.');
    }

    public function exportFilteredPDF(Request $request)
    {
        $query = Vehicle::query();
        if ($request->filled('startDate')) {
            $query->whereDate('created_at','>=',$request->startDate);
        }
        if ($request->filled('endDate')) {
            $query->whereDate('created_at','<=',$request->endDate);
        }
        $filtered = $query->orderByDesc('id')->get();

        $pdf = PDF::loadView('vehicles.vehicles_pdf', compact('filtered'))
                  ->setPaper('a4','portrait');

        return $pdf->download('Viaturas_Filtradas.pdf');
    }

    public function pdfAll(Request $request)
    {
        $query = Vehicle::query();
        if ($request->filled('startDate')) {
            $query->whereDate('created_at','>=',$request->startDate);
        }
        if ($request->filled('endDate')) {
            $query->whereDate('created_at','<=',$request->endDate);
        }
        $all = $query->orderByDesc('id')->get();

        $filename = 'Viaturas' . (
            ($request->filled('startDate')||$request->filled('endDate')) ? '_Filtradas' : ''
        ) . '.pdf';

        $pdf = PDF::loadView('vehicles.vehicles_pdf', ['filtered' => $all])
                  ->setPaper('a4','portrait');

        return $pdf->stream($filename);
    }

    public function showPdf(Vehicle $vehicle)
    {
        $vehicle->load('drivers','maintenance');
        $pdf = PDF::loadView('vehicles.vehicle_pdf_individual', compact('vehicle'))
                  ->setPaper('a4','portrait');

        return $pdf->stream("Viatura_{$vehicle->id}.pdf");
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect()->route('vehicles.index')
                         ->with('msg','Viatura excluída com sucesso.');
    }
}
