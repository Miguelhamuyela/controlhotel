<?php

namespace App\Http\Controllers;

use App\Models\EmployeeCategory;
use Illuminate\Http\Request;

class EmployeeCategoryController extends Controller
{
    public function index()
    {
        $employeeCategories = EmployeeCategory::all();
        return view("employeeCategory.index", compact("employeeCategories"));
    }

    public function create()
    {
        return view("employeeCategory.create");
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|unique:employee_categories|max:255",
            "description" => "nullable|string", 
        ]);

        EmployeeCategory::create($request->all());

        return redirect()->route("employeeCategory.index")
                         ->with("msg", "Categoria de Funcionário criada com sucesso!");
    }

    public function show(EmployeeCategory $employeeCategory)
    {
        return view("employeeCategory.show", compact("employeeCategory"));
    }

    public function edit(EmployeeCategory $employeeCategory)
    {
        return view("employeeCategory.edit", compact("employeeCategory"));
    }

    public function update(Request $request, EmployeeCategory $employeeCategory)
    {
        $request->validate([
            "name" => "required|max:255|unique:employee_categories,name," . $employeeCategory->id,
            "description" => "nullable|string",
        ]);

        $employeeCategory->update($request->all());

        return redirect()->route("employeeCategory.index")
                         ->with("msg", "Categoria de Funcionário atualizada com sucesso!");
    }

    public function destroy(EmployeeCategory $employeeCategory)
    {
        $employeeCategory->delete();
        return redirect()->route("employeeCategory.index")
                         ->with("msg", "Categoria de Funcionário excluída com sucesso!");
    }
}


