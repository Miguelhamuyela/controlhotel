<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterialType;

class MaterialTypeController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category');
        $types = MaterialType::when($category, fn($q) => $q->where('category', $category))
                             ->orderByDesc('id')
                             ->get();

        return view('material_types.index', compact('types','category'));
    }

    public function create(Request $request)
    {
        $category = $request->get('category');
        return view('material_types.create', compact('category'));
    }

    public function store(Request $request)
    {
        $category = $request->get('category');
        $request->validate([
            'name'        => 'required|string',
            'description' => 'nullable|string',
        ]);

        MaterialType::create([
            'category'    => $category,
            'name'        => $request->name,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('material-types.index', ['category' => $category])
            ->with('msg','Tipo de material criado com sucesso em '.ucfirst($category).'!');
    }

    public function show($id, Request $request)
    {
        $category = $request->get('category');
        $type = MaterialType::findOrFail($id);
        return view('material_types.show', compact('type','category'));
    }

    public function edit($id, Request $request)
    {
        $category = $request->get('category');
        $type = MaterialType::findOrFail($id);
        return view('material_types.edit', compact('type','category'));
    }

    public function update(Request $request, $id)
    {
        $category = $request->get('category');
        $request->validate([
            'name'        => 'required|string',
            'description' => 'nullable|string',
        ]);

        $type = MaterialType::findOrFail($id);
        $type->update($request->only('name','description'));

        return redirect()
            ->route('material-types.index', ['category' => $category])
            ->with('msg','Tipo de material atualizado com sucesso em '.ucfirst($category).'!');
    }

    public function destroy($id, Request $request)
    {
        $category = $request->get('category');
        MaterialType::destroy($id);

        return redirect()
            ->route('material-types.index', ['category' => $category])
            ->with('msg','Tipo de material removido com sucesso de '.ucfirst($category).'!');
    }
}
