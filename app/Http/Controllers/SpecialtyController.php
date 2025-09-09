<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Specialty;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;

class SpecialtyController extends Controller
{
    public function index()
    {
        $data           = Specialty::orderByDesc('id')->get();
        $allSpecialties = Specialty::orderBy('name')->get();
        return view('specialty.index', compact('data', 'allSpecialties'));
    }

    public function create()
    {
        return view('specialty.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'unique:specialties,name'],
        ]);

        $data = new Specialty();
        $data->name        = $request->name;
        $data->description = $request->description;
        $data->save();

        return redirect('specialties/create')
               ->with('msg', 'Especialidade criada!');
    }

    public function show($id)
    {
        $data = Specialty::findOrFail($id);
        return view('specialty.show', ['data' => $data]);
    }

    public function edit($id)
    {
        $data = Specialty::findOrFail($id);
        return view('specialty.edit', ['data' => $data]);
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => [
                'required',
                Rule::unique('specialties', 'name')->ignore($id),
            ],
        ]);

        $data = Specialty::findOrFail($id);
        $data->name        = $request->name;
        $data->description = $request->description;
        $data->save();

        // Aqui fizemos a correção: usei o route helper para apontar para /specialties/{id}/edit
        return redirect()
               ->route('specialties.edit', $id)
               ->with('msg', 'Especialidade atualizada!');
    }

    public function employeee(Request $request)
    {
        $specialtyId = $request->input('specialty');
        $specialty   = Specialty::with(['employees.department', 'employees.position'])
                          ->findOrFail($specialtyId);
        return view('specialty.employeee', compact('specialty'));
    }

    public function pdf($specialtyId)
    {
        $specialty = Specialty::with(['employees.department', 'employees.position'])
                         ->findOrFail($specialtyId);
        $pdf       = PDF::loadView('specialty.employeee_pdf', compact('specialty'));
        return $pdf->stream('RelatorioEspecialidade.pdf');
    }

    public function destroy($id)
    {
        Specialty::where('id', $id)->delete();
        return redirect('specialties');
    }
}