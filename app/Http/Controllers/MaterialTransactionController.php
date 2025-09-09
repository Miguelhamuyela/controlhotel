<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialTransaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialTransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:manage-inventory']);
    }

    public function index(Request $request, $category = null)
    {
        $categories = Auth::user()->role === 'admin'
            ? ['infraestrutura', 'servicos_gerais']
            : [$category];

        $query = MaterialTransaction::whereHas('material', fn ($q) =>
            $q->whereIn('Category', $categories)
        )->with(['material.type', 'department', 'creator']);

        if ($request->filled('startDate')) {
            $query->whereDate('TransactionDate', '>=', $request->startDate);
        }
        if ($request->filled('endDate')) {
            $query->whereDate('TransactionDate', '<=', $request->endDate);
        }
        if ($request->filled('type')) {
            $query->where('TransactionType', $request->type);
        }

        $txs = $query->orderByDesc('TransactionDate')->get();

        return view('material_transactions.index', compact('txs', 'category'));
    }

    protected function form($category, $type)
{
    // Se for admin, pega category da query string
    if (Auth::user()->role === 'admin') {
        $category = request()->get('category');
    }

    // Decide que materiais buscar
    if (Auth::user()->role === 'admin') {
        if ($category) {
            // só da categoria escolhida
            $materials = Material::where('Category', $category)
                                 ->with('type')
                                 ->get();
        } else {
            // sem categoria selecionada, não traz nada
            $materials = collect();
        }
    } else {
        // para chefes continua a lógica anterior
        $materials = Material::where('Category', $category)
                             ->with('type')
                             ->get();
    }

    return view('material_transactions.create', compact('materials','category','type'));
}

    public function createIn($category = null)
    {
        return $this->form($category, 'in');
    }

    public function createOut($category = null)
    {
        return $this->form($category, 'out');
    }

    protected function storeTx(Request $r, $category, $type)
{
    $data = $r->validate([
        'MaterialId'            => 'required|exists:materials,id',
        'TransactionDate'       => 'required|date',
        'Quantity'              => 'required|integer|min:1',
        'OriginOrDestination'   => 'required|string',
        'DocumentationPath'     => 'nullable|file|mimes:jpg,png,pdf|max:5120',
        'Notes'                 => 'nullable|string',
    ]);

    $material = Material::findOrFail($data['MaterialId']);

    // Valida se o material pertence à categoria correta se não for admin
    if (Auth::user()->role !== 'admin' && $material->Category !== $category) {
        abort(403, 'Você não tem permissão para movimentar este material.');
    }

    $delta = $type === 'in' ? $data['Quantity'] : -$data['Quantity'];
    $material->increment('CurrentStock', $delta);

    if ($r->hasFile('DocumentationPath')) {
        $data['DocumentationPath'] = $r->file('DocumentationPath')
            ->store('material_docs', 'public');
    }

    // Define os campos de rastreamento dependendo do tipo de usuário
    if (Auth::user()->role !== 'admin') {
        $employee = Auth::user()->employee;
        $data += [
            'TransactionType' => $type,
            'DepartmentId'    => $employee->departmentId,
            'CreatedBy'       => $employee->id,
        ];
    } else {
        $data += [
            'TransactionType' => $type,
            'DepartmentId'    => null,
            'CreatedBy'       => null,
        ];
    }

    MaterialTransaction::create($data);

    // Redireciona corretamente conforme o tipo de usuário
    if (Auth::user()->role === 'admin') {
        return redirect()
            ->route('admin.materials.transactions.index')
            ->with('msg', 'Transação registrada com sucesso.');
    } else {
        return redirect()
            ->route('materials.transactions.index', ['category' => $category])
            ->with('msg', 'Transação registrada com sucesso.');
    }
}


    public function storeIn(Request $r, $category = null)
    {
        return $this->storeTx($r, $category, 'in');
    }

    public function storeOut(Request $r, $category = null)
    {
        return $this->storeTx($r, $category, 'out');
    }

    public function reportIn($category = null)
    {
        $builder = MaterialTransaction::where('TransactionType', 'in')
            ->with(['material.type', 'department', 'creator']);

        $builder->whereHas('material', fn ($q) =>
            Auth::user()->role === 'admin'
                ? $q->whereIn('Category', ['infraestrutura', 'servicos_gerais'])
                : $q->where('Category', $category)
        );

        $txs = $builder->orderByDesc('TransactionDate')->get();

        return Pdf::loadView('material_transactions.report-in', compact('txs', 'category'))
            ->setPaper('a4', 'landscape')
            ->stream("Entradas.pdf");
    }

    public function reportOut($category = null)
    {
        $builder = MaterialTransaction::where('TransactionType', 'out')
            ->with(['material.type', 'department', 'creator']);

        $builder->whereHas('material', fn ($q) =>
            Auth::user()->role === 'admin'
                ? $q->whereIn('Category', ['infraestrutura', 'servicos_gerais'])
                : $q->where('Category', $category)
        );

        $txs = $builder->orderByDesc('TransactionDate')->get();

        return Pdf::loadView('material_transactions.report-out', compact('txs', 'category'))
            ->setPaper('a4', 'landscape')
            ->stream("Saidas.pdf");
    }

    public function reportAll($category = null)
    {
        $builder = MaterialTransaction::with(['material.type', 'department', 'creator']);

        $builder->whereHas('material', fn ($q) =>
            Auth::user()->role === 'admin'
                ? $q->whereIn('Category', ['infraestrutura', 'servicos_gerais'])
                : $q->where('Category', $category)
        );

        $txs = $builder->orderByDesc('TransactionDate')->get();

        return Pdf::loadView('material_transactions.report-all', compact('txs', 'category'))
            ->setPaper('a4', 'landscape')
            ->stream("TodasTransacoes.pdf");
    }
}
