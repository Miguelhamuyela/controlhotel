<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return view("course.index", compact("courses"));
    }

    public function create()
    {
        return view("course.create");
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|unique:courses|max:255",
            "description" => "nullable|string", // Adicionado o campo description
        ]);

        Course::create($request->all());

        return redirect()->route("course.index")
                         ->with("msg", "Curso criado com sucesso!");
    }

    public function show(Course $course)
    {
        return view("course.show", compact("course"));
    }

    public function edit(Course $course)
    {
        return view("course.edit", compact("course"));
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            "name" => "required|max:255|unique:courses,name," . $course->id,
            "description" => "nullable|string", // Adicionado o campo description
        ]);

        $course->update($request->all());

        return redirect()->route("course.index")
                         ->with("msg", "Curso atualizado com sucesso!");
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route("course.index")
                         ->with("msg", "Curso excluído com sucesso!");
    }
}


