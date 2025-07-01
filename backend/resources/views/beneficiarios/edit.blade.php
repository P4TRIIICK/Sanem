@extends('layouts.app')

@section('title', 'Editar Beneficiário - Sanem')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('web.beneficiarios.index') }}" class="btn btn-outline-primary me-3" title="Voltar">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="h2 mb-0">Editar Beneficiário: {{ $pessoa->nome }}</h1>
    </div>

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

            <form action="{{ route('web.beneficiarios.update', $pessoa->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                {{-- Dados Pessoais --}}
                <h5 class="mb-3">Dados Pessoais</h5>
                <div class="row">
                    <div class="col-md-6 mb-3"><label for="nome" class="form-label">Nome Completo</label><input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', $pessoa->nome) }}" required></div>
                    <div class="col-md-6 mb-3"><label for="cpf" class="form-label">CPF</label><input type="text" class="form-control" id="cpf" name="cpf" value="{{ old('cpf', $pessoa->formatted_cpf) }}" required></div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3"><label for="rg" class="form-label">RG (Opcional)</label><input type="text" class="form-control" id="rg" name="rg" value="{{ old('rg', $pessoa->rg) }}"></div>
                    <div class="col-md-6 mb-3"><label for="nascimento" class="form-label">Data de Nascimento</label><input type="date" class="form-control" id="nascimento" name="nascimento" value="{{ old('nascimento', $pessoa->nascimento) }}" required></div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3"><label for="genero" class="form-label">Gênero</label><select class="form-select" id="genero" name="genero" required><option value="FEMININO" @if(old('genero', $pessoa->genero) == 'FEMININO') selected @endif>Feminino</option><option value="MASCULINO" @if(old('genero', $pessoa->genero) == 'MASCULINO') selected @endif>Masculino</option><option value="OUTRO" @if(old('genero', $pessoa->genero) == 'OUTRO') selected @endif>Outro</option></select></div>
                </div>

                <hr class="my-4">

                {{-- Endereço Opcional --}}
                <h5 class="mb-3">Endereço</h5>
                <div class="form-check mb-3">
                    {{-- A caixa é marcada se a pessoa já tiver um endereço --}}
                    <input class="form-check-input" type="checkbox" id="possui_endereco" name="possui_endereco" @if(old('possui_endereco', $pessoa->endereco)) checked @endif>
                    <label class="form-check-label" for="possui_endereco">
                        Beneficiário possui endereço fixo
                    </label>
                </div>

                <div id="endereco_fields">
                    <div class="row">
                        <div class="col-md-6 mb-3"><label for="logradouro" class="form-label">Logradouro (Rua, Av.)</label><input type="text" class="form-control" id="logradouro" name="logradouro" value="{{ old('logradouro', $pessoa->endereco?->logradouro) }}"></div>
                        <div class="col-md-3 mb-3"><label for="numero" class="form-label">Número</label><input type="text" class="form-control" id="numero" name="numero" value="{{ old('numero', $pessoa->endereco?->numero) }}"></div>
                        <div class="col-md-3 mb-3"><label for="cep" class="form-label">CEP</label><input type="text" class="form-control" id="cep" name="cep" value="{{ old('cep', $pessoa->endereco?->cep) }}"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3"><label for="bairro" class="form-label">Bairro</label><input type="text" class="form-control" id="bairro" name="bairro" value="{{ old('bairro', $pessoa->endereco?->bairro) }}"></div>
                        <div class="col-md-4 mb-3"><label for="cidade" class="form-label">Cidade</label><input type="text" class="form-control" id="cidade" name="cidade" value="{{ old('cidade', $pessoa->endereco?->cidade?->nome) }}"></div>
                        <div class="col-md-4 mb-3"><label for="estado" class="form-label">Estado</label><input type="text" class="form-control" id="estado" name="estado" value="{{ old('estado', $pessoa->endereco?->cidade?->estado?->nome) }}"></div>
                    </div>
                </div>

                <hr class="my-4">

                {{-- Dados Socioeconômicos --}}
                <h5 class="mb-3">Dados Socioeconômicos e Foto</h5>
                 <div class="row">
                    <div class="col-md-6 mb-3"><label for="renda" class="form-label">Renda Familiar (R$)</label><input type="number" step="0.01" class="form-control" id="renda" name="renda" placeholder="Ex: 1500.50" value="{{ old('renda', $pessoa->beneficiario?->renda) }}"></div>
                     <div class="col-md-6 mb-3">
                        <label for="foto" class="form-label">Alterar Foto 3x4</label>
                        <input class="form-control" type="file" id="foto" name="foto" accept="image/png, image/jpeg, image/gif">
                        @if($pessoa->beneficiario?->foto_path)
                            <small class="form-text text-muted">Foto atual: <a href="{{ asset('storage/' . $pessoa->beneficiario->foto_path) }}" target="_blank">Ver imagem</a>.</small>
                        @endif
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end">
                    <a href="{{ route('web.beneficiarios.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary" style="background-color: var(--cor-primaria); border-color: var(--cor-primaria);">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const possuiEnderecoCheckbox = document.getElementById('possui_endereco');
    const enderecoFieldsDiv = document.getElementById('endereco_fields');
    const enderecoInputs = enderecoFieldsDiv.querySelectorAll('input');

    function toggleEnderecoFields() {
        if (possuiEnderecoCheckbox.checked) {
            enderecoFieldsDiv.style.display = 'block';
            enderecoInputs.forEach(input => input.required = true);
        } else {
            enderecoFieldsDiv.style.display = 'none';
            enderecoInputs.forEach(input => input.required = false);
        }
    }

    possuiEnderecoCheckbox.addEventListener('change', toggleEnderecoFields);
    toggleEnderecoFields(); // Verifica o estado inicial
});
</script>
@endpush
