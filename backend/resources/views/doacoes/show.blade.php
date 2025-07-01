@extends('layouts.app')

@section('title', 'Detalhes da Doação')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('web.doacoes.index') }}" class="btn btn-outline-primary me-3" title="Voltar ao Histórico">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="h2 mb-0">Detalhes da Doação #{{ $doacao->id }}</h1>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0">
                Registada em: {{ \Carbon\Carbon::parse($doacao->data_doacao)->format('d/m/Y H:i') }}
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted">Beneficiário</h6>
                    <p class="fs-5">{{ $doacao->beneficiario?->nome ?? 'N/A' }}</p>
                    <p><strong>CPF:</strong> {{ $doacao->beneficiario?->formatted_cpf ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Funcionário Responsável</h6>
                    <p class="fs-5">{{ $doacao->funcionario?->nome ?? 'N/A' }}</p>
                </div>
            </div>
            <hr class="my-4">
            <h5 class="mb-3">Itens Doados</h5>
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Item</th>
                            <th>Categoria</th>
                            <th class="text-center">Quantidade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doacao->itens as $item)
                            <tr>
                                <td>{{ $item->nome_item }}</td>
                                <td>{{ $item->categoria_principal }}</td>
                                <td class="text-center">{{ $item->pivot->quantidade_doada }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Nenhum item encontrado para esta doação.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-end">
             <a href="javascript:window.print()" class="btn btn-secondary"><i class="bi bi-printer-fill me-1"></i> Imprimir Comprovativo</a>
        </div>
    </div>
</div>
@endsection
