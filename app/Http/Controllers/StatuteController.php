<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Statute;

class StatuteController extends Controller
{
    /**
     * Exibe a lista de estatutos.
     */
    public function index()
    {
        $statutes = Statute::orderBy('created_at', 'desc')->get();
        return view('statutes.index', compact('statutes'));
    }

    /**
     * Exibe o formulário para criar um novo estatuto.
     */
    public function create()
    {
        return view('statutes.create');
    }

    /**
     * Armazena o novo estatuto.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'document'    => 'nullable|file|mimes:pdf,doc,docx,jpeg,png',
        ]);

        $statute = new Statute();
        $statute->title = $request->title;
        $statute->description = $request->description;

        if ($request->hasFile('document')) {
            $fileName = time() . '.' . $request->document->getClientOriginalExtension();
            $request->document->move(public_path('uploads/statutes'), $fileName);
            $statute->document = $fileName;
        }
        $statute->save();

        return redirect()->route('statutes.index')->with('msg', 'Estatuto criado com sucesso!');
    }

    /**
     * Exibe os detalhes de um estatuto.
     */
    public function show($id)
    {
        $statute = Statute::findOrFail($id);
        return view('statutes.show', compact('statute'));
    }

    /**
     * Exibe o formulário para editar um estatuto.
     */
    public function edit($id)
    {
        $statute = Statute::findOrFail($id);
        return view('statutes.edit', compact('statute'));
    }

    /**
     * Atualiza os dados do estatuto.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'document'    => 'nullable|file|mimes:pdf,doc,docx,jpeg,png',
        ]);

        $statute = Statute::findOrFail($id);
        $statute->title = $request->title;
        $statute->description = $request->description;

        if ($request->hasFile('document')) {
            // Se existir um arquivo antigo, você pode deletá-lo aqui, se necessário
            $fileName = time() . '.' . $request->document->getClientOriginalExtension();
            $request->document->move(public_path('uploads/statutes'), $fileName);
            $statute->document = $fileName;
        }
        $statute->save();

        return redirect()->route('statutes.index')->with('msg', 'Estatuto atualizado com sucesso!');
    }

    /**
     * Remove um estatuto.
     */
    public function destroy($id)
    {
        $statute = Statute::findOrFail($id);
        // Opcional: excluir o arquivo físico, se existir
        $statute->delete();
        return redirect()->route('statutes.index')->with('msg', 'Estatuto removido com sucesso!');
    }
}
