@extends('layouts.app')

@section('title', 'Histórico de Doações - Sanem')

@section('content')

<div class="container-fluid">

    {{-- Cabeçalho da Página --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Histórico de Doações</h1>
    </div>

    {{-- Card de Filtros e Relatórios --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('web.doacoes.index') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md-4">
                    <label for="data_filtro" class="form-label">Filtrar por data específica:</label>
                    <input type="date" name="data_filtro" class="form-control" value="{{ request('data_filtro') }}">
                </div>
                <div class="col-md-auto mt-auto">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="bi bi-search"></i> Filtrar
                    </button>
                    <a href="{{ route('web.doacoes.index') }}" class="btn btn-outline-light text-dark">Limpar</a>
                </div>
                <div class="col-md text-md-end mt-auto">
                    <a href="{{ route('web.relatorios.doacoes.mensal') }}" class="btn btn-success" target="_blank">
                        <i class="bi bi-download me-1"></i> Gerar Relatório do Mês
                    </a>
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

    {{-- Card com a Tabela de Doações --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead style="background-color: var(--cor-primaria); color: #fff;">
                        <tr>
                            <th scope="col">#ID</th>
                            <th scope="col">Beneficiário</th>
                            <th scope="col">Funcionário</th>
                            <th scope="col">Data da Doação</th>
                            <th scope="col">Total de Itens</th>
                            <th scope="col" class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($doacoes as $doacao)
                            <tr>
                                <th scope="row">{{ $doacao->id }}</th>
                                <td>{{ $doacao->beneficiario?->nome ?? 'Beneficiário Removido' }}</td>
                                <td>{{ $doacao->funcionario?->nome ?? 'Funcionário Removido' }}</td>
                                <td>{{ \Carbon\Carbon::parse($doacao->data_doacao)->format('d/m/Y') }}</td>
                                <td>{{ $doacao->itens->sum('pivot.quantidade_doada') }}</td>
                                <td class="text-center">
                                    {{-- CORREÇÃO: Passando o ID da doação para a rota --}}
                                    <a href="{{ route('web.doacoes.show', $doacao->id) }}" class="btn btn-sm btn-info" title="Ver Detalhes da Doação"><i class="bi bi-eye-fill"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Nenhuma doação encontrada para a data selecionada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {!! $doacoes->appends(request()->query())->links() !!}
        </div>
    </div>
</div>

@endsection
