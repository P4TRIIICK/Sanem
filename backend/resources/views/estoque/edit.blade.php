@extends('layouts.app')

@section('title', 'Editar Item do Estoque')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('web.estoque.index') }}" class="btn btn-outline-primary me-3" title="Voltar">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="h2 mb-0">Editar Item: {{ $item->nome_item }}</h1>
    </div>

    <form action="{{ route('web.estoque.update', $item->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
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
                    <div class="col-md-5 mb-3">
                        <label for="nome_item" class="form-label">Nome do Item</label>
                        <input type="text" class="form-control" id="nome_item" name="nome_item" value="{{ old('nome_item', $item->nome_item) }}" required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="quantidade" class="form-label">Quantidade</label>
                        <input type="number" class="form-control" id="quantidade" name="quantidade" value="{{ old('quantidade', $item->quantidade) }}" required min="0">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="status" class="form-label">Status do Item</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="Disponível" @if(old('status', $item->status) == 'Disponível') selected @endif>Disponível</option>
                            <option value="Danificado" @if(old('status', $item->status) == 'Danificado') selected @endif>Danificado</option>
                            <option value="Esgotado" @if(old('status', $item->status) == 'Esgotado') selected @endif>Esgotado</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="categoria_principal" class="form-label">Categoria Principal</label>
                        <select class="form-select" id="categoria_principal" name="categoria_principal" required>
                            <option value="Roupas" @if(old('categoria_principal', $item->categoria_principal) == 'Roupas') selected @endif>Roupas</option>
                            <option value="Alimentos" @if(old('categoria_principal', $item->categoria_principal) == 'Alimentos') selected @endif>Alimentos</option>
                            <option value="Brinquedos" @if(old('categoria_principal', $item->categoria_principal) == 'Brinquedos') selected @endif>Brinquedos</option>
                            <option value="Higiene" @if(old('categoria_principal', $item->categoria_principal) == 'Higiene') selected @endif>Produtos de Higiene</option>
                            <option value="Outros" @if(old('categoria_principal', $item->categoria_principal) == 'Outros') selected @endif>Outros</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="descricao" class="form-label">Descrição / Observações</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3">{{ old('descricao', $item->descricao) }}</textarea>
                    </div>
                </div>
                
                <div class="row">
                     <div class="col-md-6 mb-3">
                        <label for="foto_item" class="form-label">Alterar Foto do Item</label>
                        <input class="form-control" type="file" id="foto_item" name="foto_item" accept="image/png, image/jpeg, image/gif">
                        @if ($item->foto_path)
                            <small class="form-text text-muted">Foto atual: <a href="{{ asset('storage/' . $item->foto_path) }}" target="_blank">Ver imagem</a>. Envie um novo ficheiro para substituir.</small>
                        @endif
                    </div>
                </div>

                <hr class="my-4">

                {{-- Seção Dinâmica - Detalhes Específicos --}}
                <div id="detalhes_especificos">
                    <h5 class="mb-3">Detalhes Específicos da Categoria</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3" id="campo_sub_categoria" style="display: none;">
                            <label for="sub_categoria" class="form-label">Sub-categoria</label>
                            <select class="form-select" id="sub_categoria" name="sub_categoria"></select>
                        </div>
                        <div class="col-md-4 mb-3" id="campo_tamanho" style="display: none;">
                            <label for="tamanho" class="form-label">Tamanho</label>
                            <input type="text" class="form-control" id="tamanho" name="tamanho" value="{{ old('tamanho', $item->detalhes['tamanho'] ?? '') }}" placeholder="P, M, G, 38, 42, etc.">
                        </div>
                        <div class="col-md-4 mb-3" id="campo_genero_roupa" style="display: none;">
                            <label for="genero_roupa" class="form-label">Gênero</label>
                            <select class="form-select" id="genero_roupa" name="genero_roupa">
                                <option value="Unissex" @if(old('genero_roupa', $item->detalhes['genero'] ?? '') == 'Unissex') selected @endif>Unissex</option>
                                <option value="Masculino" @if(old('genero_roupa', $item->detalhes['genero'] ?? '') == 'Masculino') selected @endif>Masculino</option>
                                <option value="Feminino" @if(old('genero_roupa', $item->detalhes['genero'] ?? '') == 'Feminino') selected @endif>Feminino</option>
                                <option value="Infantil" @if(old('genero_roupa', $item->detalhes['genero'] ?? '') == 'Infantil') selected @endif>Infantil</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3" id="campo_validade" style="display: none;">
                            <label for="data_validade" class="form-label">Data de Validade</label>
                            <input type="date" class="form-control" id="data_validade" name="data_validade" value="{{ old('data_validade', $item->detalhes['data_validade'] ?? '') }}">
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-footer bg-transparent border-0 text-end p-4">
                 <a href="{{ route('web.estoque.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                 <button type="submit" class="btn btn-primary" style="background-color: var(--cor-primaria); border-color: var(--cor-primaria);">Salvar Alterações</button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const subCategorias = {
        'Roupas': ['Camiseta', 'Calça', 'Casaco', 'Vestido', 'Roupa Íntima', 'Meias', 'Infantil'],
        'Brinquedos': ['Educativo', 'Pelúcia', 'Jogo de Tabuleiro', 'Eletrônico', 'Ar Livre'],
        'Higiene': ['Higiene Pessoal', 'Higiene Feminina', 'Limpeza Doméstica'],
    };

    const categoriaPrincipalSelect = document.getElementById('categoria_principal');
    const detalhesDiv = document.getElementById('detalhes_especificos');
    
    const campoSubCategoria = document.getElementById('campo_sub_categoria');
    const subCategoriaSelect = document.getElementById('sub_categoria');
    const campoTamanho = document.getElementById('campo_tamanho');
    const campoGeneroRoupa = document.getElementById('campo_genero_roupa');
    const campoValidade = document.getElementById('campo_validade');

    function atualizarCampos() {
        const categoriaSelecionada = categoriaPrincipalSelect.value;
        
        // Esconde todos os campos primeiro
        detalhesDiv.style.display = 'none';
        campoSubCategoria.style.display = 'none';
        campoTamanho.style.display = 'none';
        campoGeneroRoupa.style.display = 'none';
        campoValidade.style.display = 'none';

        if (!categoriaSelecionada) return;

        detalhesDiv.style.display = 'block';

        if (subCategorias[categoriaSelecionada]) {
            campoSubCategoria.style.display = 'block';
            subCategoriaSelect.innerHTML = '<option value="">Selecione uma sub-categoria...</option>';
            const subCategoriaSalva = "{{ old('sub_categoria', $item->detalhes['sub_categoria'] ?? '') }}";
            subCategorias[categoriaSelecionada].forEach(function(sub) {
                const option = document.createElement('option');
                option.value = sub;
                option.textContent = sub;
                if (sub === subCategoriaSalva) {
                    option.selected = true;
                }
                subCategoriaSelect.appendChild(option);
            });
        }

        if (categoriaSelecionada === 'Roupas') {
            campoTamanho.style.display = 'block';
            campoGeneroRoupa.style.display = 'block';
        } else if (categoriaSelecionada === 'Alimentos') {
            campoValidade.style.display = 'block';
        }
    }

    categoriaPrincipalSelect.addEventListener('change', atualizarCampos);
    
    // Executa a função na primeira vez que a página carrega
    atualizarCampos();
});
</script>
@endpush
