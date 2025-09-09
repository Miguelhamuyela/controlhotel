<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialType;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','can:manage-inventory']);
    }

    public function index(Request $request)
    {
        $category  = $request->get('category');
        $query     = Material::with('type');
        if(auth()->user()->role !== 'admin') {
            // chefes só veem sua categoria
            $query->where('Category', $category);
        } else if ($category) {
            // admin filtra se passou ?category=
            $query->where('Category', $category);
        }
        $materials = $query->get();

        return view('materials.index', compact('materials','category'));
    }

    public function create(Request $request)
    {
        $category = $request->get('category');
        // só traz tipos daquela categoria
        $types    = MaterialType::where('category', $category)
                                ->orderBy('name')->get();

        return view('materials.create', compact('category','types'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'Category'           => 'required|in:infraestrutura,servicos_gerais',
            'materialTypeId'     => 'required|exists:material_types,id',
            'Name'               => 'required|string',
            'SerialNumber'       => 'required|string|unique:materials,SerialNumber',
            'Model'              => 'required|string',
            'ManufactureDate'    => 'required|date',
            'SupplierName'       => 'required|string',
            'SupplierIdentifier' => 'required|string',
            'EntryDate'          => 'required|date',
            'CurrentStock'       => 'required|integer|min:0',
            'Notes'              => 'nullable|string',
        ]);

        Material::create($data);

        return redirect()
            ->route('materials.index', ['category' => $data['Category']])
            ->with('msg','Material cadastrado com sucesso.');
    }

    public function show($id, Request $request)
    {
        $material = Material::findOrFail($id);
        $category = $request->get('category', $material->Category);

        return view('materials.show', compact('material','category'));
    }

    public function edit($id, Request $request)
    {
        $material = Material::findOrFail($id);
        $category = $request->get('category', $material->Category);
        // tipos só da mesma categoria
        $types    = MaterialType::where('category', $category)
                                ->orderBy('name')->get();

        return view('materials.edit', compact('material','category','types'));
    }

    public function update(Request $request, $id)
    {
        $material = Material::findOrFail($id);

        $data = $request->validate([
            'materialTypeId'   => 'required|exists:material_types,id',
            'Name'             => 'required|string',
            'Model'            => 'required|string',
            'ManufactureDate'  => 'required|date',
            'Notes'            => 'nullable|string',
        ]);

        $material->update($data);

        return redirect()
            ->route('materials.index', ['category' => $material->Category])
            ->with('msg','Material atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $material = Material::findOrFail($id);
        $category = $material->Category;
        $material->delete();

        return redirect()
            ->route('materials.index', ['category' => $category])
            ->with('msg','Material removido com sucesso.');
    }
}
