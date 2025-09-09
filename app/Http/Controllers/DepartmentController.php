<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function index()
    {
        $data = Department::orderByDesc('id')->get();
        return view('department.index', ['data' => $data]);
    }

    public function create()
    {
        return view('department.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => ['required', 'unique:departments,title'],
            'description' => 'nullable',
        ]);

        $data = new Department();
        $data->title       = $request->title;
        $data->description = $request->description;
        $data->save();

        return redirect('depart/create')
               ->with('msg', 'Dados Submetidos com Sucesso');
    }

    public function show($id)
    {
        $data = Department::findOrFail($id);
        return view('department.show', compact('data'));
    }

    public function employeee(Request $request)
    {
        $departmentId = $request->input('department');
        $department   = Department::with('employeee')
                          ->findOrFail($departmentId);
        return view('department.employeee', compact('department'));
    }

    public function edit($id)
    {
        $departs = Department::findOrFail($id);
        return view('department.edit', ['data' => $departs]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'       => [
                'required',
                Rule::unique('departments', 'title')->ignore($id),
            ],
            'description' => 'nullable',
        ]);

        $data = Department::findOrFail($id);
        $data->title       = $request->title;
        $data->description = $request->description;
        $data->save();

        return redirect("depart/{$id}/edit")
               ->with('msg', 'Dados Submetidos com Sucesso');
    }

    public function employeeePdf($departmentId)
    {
        $department = Department::with('employeee')
                          ->findOrFail($departmentId);
        $pdf        = PDF::loadView('department.employeee_pdf', compact('department'));
        return $pdf->stream('RelatorioDepartamento.pdf');
    }

    public function destroy($id)
    {
        Department::where('id', $id)->delete();
        return redirect('depart');
    }
}
