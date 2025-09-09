@extends('layouts.admin.layout')
@section('title','Estoque — '.ucfirst($category))

@section('content')
<div class="card mb-4">
  <div class="card-header bg-secondary text-white d-flex justify-content-between">
    <span><i class="fas fa-boxes me-2"></i>Estoque — {{ ucfirst($category) }}</span>
    <div>
      <a href="{{ route('materials.create',['category'=>$category]) }}" class="btn btn-sm btn-primary">
        <i class="bi bi-plus-circle"></i> Novo Material
      </a>
      <a href="{{ route('materials.transactions.in',['category'=>$category]) }}" class="btn btn-sm btn-success">
        <i class="bi bi-arrow-down-circle"></i> Entrada
      </a>
      <a href="{{ route('materials.transactions.out',['category'=>$category]) }}" class="btn btn-sm btn-warning">
        <i class="bi bi-arrow-up-circle"></i> Saída
      </a>
      <a href="{{ route('materials.transactions.index',['category'=>$category]) }}" class="btn btn-sm btn-info">
        <i class="bi bi-clock-history"></i> Histórico
      </a>
    </div>
  </div>
  <div class="card-body">
    @if(session('msg'))
      <div class="alert alert-success">{{ session('msg') }}</div>
    @endif
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Nome</th>
          <th>Tipo</th>
          <th>Modelo</th>
          <th>Fabricado</th>
          <th>Estoque</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        @forelse($materials as $m)
          <tr>
            <td>{{ $m->Name }}</td>
            <td>{{ $m->type->name }}</td>
            <td>{{ $m->Model }}</td>
            <td>{{ \Carbon\Carbon::parse($m->ManufactureDate)->format('d/m/Y') }}</td>
            <td>{{ $m->CurrentStock }}</td>
            <td class="text-center">
              <a href="{{ route('materials.show',['material'=>$m->id,'category'=>$category]) }}" class="btn btn-info btn-sm"><i class="bi bi-eye"></i></a>
              <a href="{{ route('materials.edit',['material'=>$m->id,'category'=>$category]) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
              <a href="#" data-url="{{ route('materials.destroy',$m->id) }}" class="btn btn-danger btn-sm delete-btn"><i class="bi bi-trash"></i></a>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="text-center">Nenhum material cadastrado.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
