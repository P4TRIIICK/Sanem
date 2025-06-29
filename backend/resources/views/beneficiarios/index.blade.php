@extends('layouts.app')

@section('title', 'Beneficiários - Sanem')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Gestão de Beneficiários</h1>
        <a href="{{ route('web.beneficiarios.create') }}" class="btn btn-primary" style="background-color: var(--cor-primaria); border-color: var(--cor-primaria);">
            <i class="bi bi-plus-circle-fill me-1"></i> Novo Beneficiário
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead style="background-color: var(--cor-primaria); color: #fff;">
                        <tr>
                            <th scope="col">#ID</th>
                            <th scope="col">Nome</th>
                            <th scope="col">CPF</th>
                            <th scope="col">Status do Auxílio</th>
                            <th scope="col" class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($beneficiarios as $pessoa)
                            <tr>
                                <th scope="row">{{ $pessoa->id }}</th>
                                <td>{{ $pessoa->nome }}</td>
                                <td>{{ $pessoa->cpf }}</td>
                                <td>
                                    @if($pessoa->beneficiario)
                                        @php
                                            $status = $pessoa->beneficiario->status;
                                            $badgeClass = '';
                                            $statusText = '';

                                            switch ($status) {
                                                case 'APROVADO':
                                                    $badgeClass = 'bg-success';
                                                    $statusText = 'Aprovado';
                                                    break;
                                                case 'REPROVADO':
                                                    $badgeClass = 'bg-danger';
                                                    $statusText = 'Reprovado';
                                                    break;
                                                default:
                                                    $badgeClass = 'bg-warning text-dark';
                                                    $statusText = 'Pendente';
                                            }
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                                    @else
                                        <span class="badge bg-secondary">Não é Beneficiário</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{-- Botão de Gerenciar Status --}}
                                    @if($pessoa->beneficiario)
                                        @can('aprovar-beneficiarios')
                                            <a href="{{ route('web.beneficiarios.approvalForm', $pessoa->id) }}" class="btn btn-sm btn-secondary" title="Gerenciar Status">
                                                <i class="bi bi-gear-fill"></i>
                                            </a>
                                        @endcan
                                    @endif
                                    
                                    {{-- BOTÃO DE VISUALIZAR REMOVIDO --}}
                                    
                                    @can('gerenciar-beneficiarios')
                                        {{-- Botão de Editar agora aponta para a rota de edição --}}
                                        <a href="{{ route('web.beneficiarios.edit', $pessoa->id) }}" class="btn btn-sm btn-warning" title="Editar"><i class="bi bi-pencil-fill"></i></a>
                                        
                                        <form action="{{ route('web.beneficiarios.destroy', $pessoa->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este beneficiário?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Excluir"><i class="bi bi-trash-fill"></i></button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Nenhum beneficiário encontrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {!! $beneficiarios->links() !!}
        </div>
    </div>
</div>

@endsection
