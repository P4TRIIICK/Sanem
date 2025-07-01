@extends('layouts.app')

@section('title', 'Detalhes do Item de Estoque')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('web.estoque.index') }}" class="btn btn-outline-primary me-3" title="Voltar">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="h2 mb-0">Detalhes do Item: {{ $item->nome_item }}</h1>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="row">
                {{-- Coluna da Foto --}}
                <div class="col-md-4 text-center">
                    @if($item->foto_path)
                        <img src="{{ asset('storage/' . $item->foto_path) }}" alt="Foto de {{ $item->nome_item }}" class="img-thumbnail mb-3" style="max-width: 100%;">
                    @else
                        <div class="border rounded bg-light d-flex align-items-center justify-content-center mb-3" style="width: 100%; height: 250px;">
                            <i class="bi bi-box-seam" style="font-size: 5rem; color: #ccc;"></i>
                        </div>
                    @endif
                </div>

                {{-- Coluna de Detalhes --}}
                <div class="col-md-8">
                    <h5 class="mb-3">Informações Gerais</h5>
                    <p><strong>Nome do Item:</strong> {{ $item->nome_item }}</p>
                    <p><strong>Quantidade em Estoque:</strong> {{ $item->quantidade }}</p>
                    <p><strong>Categoria Principal:</strong> {{ $item->categoria_principal }}</p>
                    <p><strong>Descrição:</strong> {{ $item->descricao ?? 'Nenhuma descrição fornecida.' }}</p>

                    <hr class="my-4">

                    <h5 class="mb-3">Detalhes Específicos</h5>
                    {{-- CORREÇÃO: Verifica se $item->detalhes não é nulo antes de usar array_filter --}}
                    @if($item->detalhes && !empty(array_filter($item->detalhes)))
                        @if(!empty($item->detalhes['sub_categoria']))
                            <p><strong>Sub-categoria:</strong> {{ $item->detalhes['sub_categoria'] }}</p>
                        @endif
                        @if(!empty($item->detalhes['tamanho']))
                            <p><strong>Tamanho:</strong> {{ $item->detalhes['tamanho'] }}</p>
                        @endif
                        @if(!empty($item->detalhes['genero']))
                            <p><strong>Gênero:</strong> {{ $item->detalhes['genero'] }}</p>
                        @endif
                        @if(!empty($item->detalhes['data_validade']))
                            <p><strong>Data de Validade:</strong> {{ \Carbon\Carbon::parse($item->detalhes['data_validade'])->format('d/m/Y') }}</p>
                        @endif
                    @else
                        <p class="text-muted">Nenhum detalhe específico fornecido.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
