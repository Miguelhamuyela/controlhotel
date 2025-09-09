<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveType;

class LeaveTypeController extends Controller
{
    public function index()
    {
        $data = LeaveType::orderByDesc('id')->get();
        return view('leaveType.index', compact('data'));
    }

    public function create()
    {
        return view('leaveType.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        LeaveType::create($request->only('name', 'description'));

        return redirect()->route('leaveType.index')
                         ->with('msg', 'Tipo de licença criado com sucesso!');
    }

    public function show($id)
    {
        $data = LeaveType::findOrFail($id);
        return view('leaveType.show', compact('data'));
    }

    public function edit($id)
    {
        $data = LeaveType::findOrFail($id);
        return view('leaveType.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $data = LeaveType::findOrFail($id);
        $data->update($request->only('name', 'description'));

        return redirect()->route('leaveType.edit', $id)
                         ->with('msg', 'Tipo de licença atualizado com sucesso!');
    }

    public function destroy($id)
    {
        LeaveType::destroy($id);
        return redirect()->route('leaveType.index')
                         ->with('msg', 'Tipo de licença removido com sucesso!');
    }
}
