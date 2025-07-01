@extends('layouts.app')

@section('title', 'Gestão de Estoque - Sanem')

@section('content')

<div class="container-fluid">

    {{-- Cabeçalho da Página --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Gestão de Estoque</h1>
        <a href="{{ route('web.estoque.create') }}" class="btn btn-primary" style="background-color: var(--cor-primaria); border-color: var(--cor-primaria);">
            <i class="bi bi-plus-circle-fill me-1"></i> Adicionar Item
        </a>
    </div>

    {{-- Formulário de Pesquisa --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('web.estoque.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Pesquisar por nome do item..." value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="bi bi-search"></i> Pesquisar
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Card com a Tabela de Itens --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead style="background-color: var(--cor-primaria); color: #fff;">
                        <tr>
                            <th scope="col">#ID</th>
                            <th scope="col">Item</th>
                            <th scope="col">Categoria</th>
                            <th scope="col">Quantidade</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($itens as $item)
                            <tr>
                                <th scope="row">{{ $item->id }}</th>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->foto_path)
                                            <img src="{{ asset('storage/' . $item->foto_path) }}" alt="{{ $item->nome_item }}" class="me-3" style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px;">
                                        @endif
                                        <div>
                                            <strong>{{ $item->nome_item }}</strong>
                                            @if($item->detalhes && !empty($item->detalhes['sub_categoria']))
                                                <small class="d-block text-muted">{{ $item->detalhes['sub_categoria'] }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $item->categoria_principal }}</td>
                                <td>{{ $item->quantidade }}</td>
                                <td>
                                    {{-- CORREÇÃO: Lógica para exibir o novo status com cores --}}
                                    @php
                                        $badgeClass = match($item->status) {
                                            'Disponível' => 'bg-success',
                                            'Danificado' => 'bg-warning text-dark',
                                            'Esgotado' => 'bg-danger',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $item->status }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('web.estoque.show', $item->id) }}" class="btn btn-sm btn-info" title="Ver Detalhes"><i class="bi bi-eye-fill"></i></a>
                                    <a href="{{ route('web.estoque.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Editar"><i class="bi bi-pencil-fill"></i></a>
                                    <form action="{{ route('web.estoque.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem a certeza que deseja excluir este item?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Excluir"><i class="bi bi-trash-fill"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Nenhum item encontrado no estoque.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {!! $itens->appends(request()->query())->links() !!}
        </div>
    </div>
</div>

@endsection
