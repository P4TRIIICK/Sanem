@extends('layouts.app')

@section('title', 'Adicionar Novo Item ao Estoque')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('web.estoque.index') }}" class="btn btn-outline-primary me-3" title="Voltar">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="h2 mb-0">Adicionar Novo Item ao Estoque</h1>
    </div>

    {{-- CORREÇÃO: O 'action' do formulário agora aponta para a rota 'store' correta. --}}
    <form action="{{ route('web.estoque.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Por favor, corrija os seguintes erros:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Seção Principal --}}
                <h5 class="mb-3">Informações Gerais do Item</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nome_item" class="form-label">Nome do Item</label>
                        <input type="text" class="form-control" id="nome_item" name="nome_item" value="{{ old('nome_item') }}" required placeholder="Ex: Camiseta de algodão, Arroz (1kg), etc.">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="quantidade" class="form-label">Quantidade</label>
                        <input type="number" class="form-control" id="quantidade" name="quantidade" value="{{ old('quantidade', 1) }}" required min="1">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="categoria_principal" class="form-label">Categoria Principal</label>
                        <select class="form-select" id="categoria_principal" name="categoria_principal" required>
                            <option value="" selected disabled>Selecione...</option>
                            <option value="Roupas">Roupas</option>
                            <option value="Alimentos">Alimentos</option>
                            <option value="Brinquedos">Brinquedos</option>
                            <option value="Higiene">Produtos de Higiene</option>
                            <option value="Outros">Outros</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="descricao" class="form-label">Descrição / Observações</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3" placeholder="Qualquer detalhe relevante sobre o item, como cor, estado de conservação, marca, etc.">{{ old('descricao') }}</textarea>
                    </div>
                </div>
                
                <div class="row">
                     <div class="col-md-6 mb-3">
                        <label for="foto_item" class="form-label">Foto do Item (Opcional)</label>
                        <input class="form-control" type="file" id="foto_item" name="foto_item" accept="image/png, image/jpeg, image/gif">
                    </div>
                </div>

                <hr class="my-4">

                {{-- Seção Dinâmica - Detalhes Específicos --}}
                <div id="detalhes_especificos" style="display: none;">
                    <h5 class="mb-3">Detalhes Específicos da Categoria</h5>
                    <div class="row">
                        {{-- Campo de Sub-categoria (populado via JS) --}}
                        <div class="col-md-4 mb-3" id="campo_sub_categoria" style="display: none;">
                            <label for="sub_categoria" class="form-label">Sub-categoria</label>
                            <select class="form-select" id="sub_categoria" name="sub_categoria"></select>
                        </div>

                        {{-- Campos específicos para Roupas --}}
                        <div class="col-md-4 mb-3" id="campo_tamanho" style="display: none;">
                            <label for="tamanho" class="form-label">Tamanho</label>
                            <input type="text" class="form-control" id="tamanho" name="tamanho" placeholder="P, M, G, 38, 42, etc.">
                        </div>
                        <div class="col-md-4 mb-3" id="campo_genero_roupa" style="display: none;">
                            <label for="genero_roupa" class="form-label">Gênero</label>
                            <select class="form-select" id="genero_roupa" name="genero_roupa">
                                <option value="Unissex">Unissex</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Feminino">Feminino</option>
                                <option value="Infantil">Infantil</option>
                            </select>
                        </div>

                        {{-- Campos específicos para Alimentos --}}
                        <div class="col-md-4 mb-3" id="campo_validade" style="display: none;">
                            <label for="data_validade" class="form-label">Data de Validade</label>
                            <input type="date" class="form-control" id="data_validade" name="data_validade">
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-footer bg-transparent border-0 text-end p-4">
                 <a href="{{ route('web.estoque.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                 <button type="submit" class="btn btn-primary" style="background-color: var(--cor-primaria); border-color: var(--cor-primaria);">Adicionar ao Estoque</button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Mapeamento de categorias para sub-categorias
    const subCategorias = {
        'Roupas': ['Camiseta', 'Calça', 'Casaco', 'Vestido', 'Roupa Íntima', 'Meias', 'Infantil'],
        'Brinquedos': ['Educativo', 'Pelúcia', 'Jogo de Tabuleiro', 'Eletrônico', 'Ar Livre'],
        'Higiene': ['Higiene Pessoal', 'Higiene Feminina', 'Limpeza Doméstica'],
    };

    // Elementos do formulário
    const categoriaPrincipalSelect = document.getElementById('categoria_principal');
    const detalhesDiv = document.getElementById('detalhes_especificos');
    
    // Campos dinâmicos
    const campoSubCategoria = document.getElementById('campo_sub_categoria');
    const subCategoriaSelect = document.getElementById('sub_categoria');
    const campoTamanho = document.getElementById('campo_tamanho');
    const campoGeneroRoupa = document.getElementById('campo_genero_roupa');
    const campoValidade = document.getElementById('campo_validade');

    // Função para esconder todos os campos dinâmicos
    function esconderTodosCamposDinamicos() {
        detalhesDiv.style.display = 'none';
        campoSubCategoria.style.display = 'none';
        campoTamanho.style.display = 'none';
        campoGeneroRoupa.style.display = 'none';
        campoValidade.style.display = 'none';
    }

    // Evento que dispara quando a categoria principal é alterada
    categoriaPrincipalSelect.addEventListener('change', function () {
        esconderTodosCamposDinamicos();
        const categoriaSelecionada = this.value;

        // Mostra a seção de detalhes se uma categoria for selecionada
        if (categoriaSelecionada) {
            detalhesDiv.style.display = 'block';
        }

        // Popula as sub-categorias, se existirem
        if (subCategorias[categoriaSelecionada]) {
            campoSubCategoria.style.display = 'block';
            subCategoriaSelect.innerHTML = '<option value="">Selecione uma sub-categoria...</option>'; // Limpa e adiciona opção padrão
            subCategorias[categoriaSelecionada].forEach(function(sub) {
                const option = document.createElement('option');
                option.value = sub;
                option.textContent = sub;
                subCategoriaSelect.appendChild(option);
            });
        }

        // Mostra campos específicos baseados na categoria principal
        if (categoriaSelecionada === 'Roupas') {
            campoTamanho.style.display = 'block';
            campoGeneroRoupa.style.display = 'block';
        } else if (categoriaSelecionada === 'Alimentos') {
            campoValidade.style.display = 'block';
        }
    });
});
</script>
@endpush
