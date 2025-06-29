@extends('layouts.app')

@section('title', 'Gestão de Funcionários - Sanem')

@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Gestão de Funcionários</h1>
        <a href="{{ route('web.funcionarios.create') }}" class="btn btn-primary" style="background-color: var(--cor-primaria); border-color: var(--cor-primaria);">
            <i class="bi bi-plus-circle-fill me-1"></i> Novo Funcionário
        </a>
    </div>

    {{-- Alertas de sucesso e erro --}}
    @if (session('success'))<div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>@endif
    @if (session('error'))<div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>@endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead style="background-color: var(--cor-primaria); color: #fff;">
                        <tr>
                            <th scope="col">#ID</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Email</th>
                            <th scope="col">Cargo</th>
                            <th scope="col" class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($funcionarios as $funcionario)
                        <tr>
                            <th scope="row">{{ $funcionario->id }}</th>
                            <td>{{ $funcionario->nome }}</td>
                            <td>{{ $funcionario->email }}</td>
                            <td>
                                @if($funcionario->hasRole('Administrador'))
                                    <span class="badge bg-danger">Administrador</span>
                                @else
                                    <span class="badge bg-info">Consultor</span>
                                @endif
                            </td>
                            <td class="text-center">
                                {{-- Lógica para botões do Admin Master --}}
                                @if(Auth::user()->id === 1)
                                    {{-- Botão Visualizar --}}
                                    <a href="{{ route('web.funcionarios.show', $funcionario->id) }}" class="btn btn-sm btn-info" title="Ver Detalhes"><i class="bi bi-eye-fill"></i></a>
                                    
                                    {{-- Botão Apagar (não aparece para o próprio Admin Master) --}}
                                    @if($funcionario->id !== 1)
                                    <form action="{{ route('web.funcionarios.destroy', $funcionario->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem a certeza que deseja excluir este funcionário?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Excluir"><i class="bi bi-trash-fill"></i></button>
                                    </form>
                                    @endif
                                @endif

                                {{-- Botão Editar (visível para todos os Admins) --}}
                                <a href="{{ route('web.funcionarios.edit', $funcionario->id) }}" class="btn btn-sm btn-warning" title="Editar"><i class="bi bi-pencil-fill"></i></a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Nenhum funcionário encontrado.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {!! $funcionarios->links() !!}
        </div>
    </div>
</div>

@endsection
