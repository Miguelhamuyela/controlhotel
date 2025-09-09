<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Statute;
use App\Models\Admin;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        return view('frontend.index', compact('departments'));
    }

    public function about()
    {
        return view('frontend.about');
    }

    public function statute()
    {
        $statute = Statute::orderBy('created_at', 'desc')->first();
        return view('frontend.statute', compact('statute'));
    }

    public function directors()
    {
        // traz todos os diretores em um array
        $directors = Admin::where('role', 'director')->get();
        return view('frontend.directors', compact('directors'));
    }

    //exibe detalhes de um único diretor
    public function showDirector($id)
    {
        $director = Admin::findOrFail($id);
        return view('frontend.director_show', compact('director'));
    }

    public function contact()
    {
        return view('frontend.contact');
    }
}
