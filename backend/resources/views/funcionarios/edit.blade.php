@extends('layouts.app')

@section('title', 'Editar Funcionário')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('web.funcionarios.index') }}" class="btn btn-outline-primary me-3" title="Voltar">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="h2 mb-0">Editar Funcionário: {{ $funcionario->nome }}</h1>
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

            <form action="{{ route('web.funcionarios.update', $funcionario->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                {{-- Dados Pessoais --}}
                <h5 class="mb-3">Dados Pessoais</h5>
                <div class="row">
                    <div class="col-md-6 mb-3"><label for="nome" class="form-label">Nome Completo</label><input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', $funcionario->nome) }}" required></div>
                    <div class="col-md-6 mb-3"><label for="cpf" class="form-label">CPF</label><input type="text" class="form-control" id="cpf" name="cpf" value="{{ old('cpf', $funcionario->formatted_cpf) }}" required></div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3"><label for="rg" class="form-label">RG (Opcional)</label><input type="text" class="form-control" id="rg" name="rg" value="{{ old('rg', $funcionario->rg) }}"></div>
                    <div class="col-md-6 mb-3"><label for="nascimento" class="form-label">Data de Nascimento</label><input type="date" class="form-control" id="nascimento" name="nascimento" value="{{ old('nascimento', $funcionario->nascimento) }}" required></div>
                </div>

                <hr class="my-4">

                {{-- Contato e Endereço --}}
                <h5 class="mb-3">Contato e Endereço</h5>
                <div class="row">
                    <div class="col-md-6 mb-3"><label for="logradouro" class="form-label">Logradouro (Rua, Av.)</label><input type="text" class="form-control" id="logradouro" name="logradouro" value="{{ old('logradouro', $funcionario->endereco?->logradouro) }}" required></div>
                    <div class="col-md-3 mb-3"><label for="numero" class="form-label">Número</label><input type="text" class="form-control" id="numero" name="numero" value="{{ old('numero', $funcionario->endereco?->numero) }}"></div>
                    <div class="col-md-3 mb-3"><label for="cep" class="form-label">CEP</label><input type="text" class="form-control" id="cep" name="cep" value="{{ old('cep', $funcionario->endereco?->cep) }}"></div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3"><label for="bairro" class="form-label">Bairro</label><input type="text" class="form-control" id="bairro" name="bairro" value="{{ old('bairro', $funcionario->endereco?->bairro) }}" required></div>
                    <div class="col-md-4 mb-3"><label for="cidade" class="form-label">Cidade</label><input type="text" class="form-control" id="cidade" name="cidade" value="{{ old('cidade', $funcionario->endereco?->cidade?->nome) }}" required></div>
                    <div class="col-md-4 mb-3"><label for="estado" class="form-label">Estado</label><input type="text" class="form-control" id="estado" name="estado" value="{{ old('estado', $funcionario->endereco?->cidade?->estado?->nome) }}" required></div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3"><label for="telefone" class="form-label">Telefone</label><input type="tel" class="form-control" id="telefone" name="telefone" placeholder="(XX) XXXXX-XXXX" value="{{ old('telefone', $funcionario->telefones->first()?->numero) }}"></div>
                </div>

                <hr class="my-4">

                {{-- Acesso e Dados Profissionais --}}
                <h5 class="mb-3">Acesso e Dados Profissionais</h5>
                <div class="row">
                    <div class="col-md-6 mb-3"><label for="email" class="form-label">Email de Acesso</label><input type="email" class="form-control" id="email" name="email" value="{{ old('email', $funcionario->email) }}" required></div>
                    <div class="col-md-6 mb-3"><label for="password" class="form-label">Nova Senha (Opcional)</label><input type="password" class="form-control" id="password" name="password" placeholder="Deixe em branco para não alterar"></div>
                </div>
                 <div class="row align-items-center">
                    <div class="col-md-4 mb-3"><label for="role" class="form-label">Cargo / Função</label><select class="form-select" id="role" name="role" required><option value="Consultor" @if($funcionario->hasRole('Consultor')) selected @endif>Consultor</option><option value="Administrador" @if($funcionario->hasRole('Administrador')) selected @endif>Administrador</option></select></div>
                    <div class="col-md-4 mb-3"><label for="salario" class="form-label">Salário (R$)</label><input type="number" step="0.01" class="form-control" id="salario" name="salario" placeholder="Ex: 3500.50" value="{{ old('salario', $funcionario->funcionario?->salario) }}"></div>
                    <div class="col-md-4 mb-3"><label for="data_contratacao" class="form-label">Data de Contratação</label><input type="date" class="form-control" id="data_contratacao" name="data_contratacao" value="{{ old('data_contratacao', $funcionario->funcionario?->data_contratacao) }}" required></div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="foto" class="form-label">Foto de Perfil (Opcional)</label>
                        <input class="form-control" type="file" id="foto" name="foto" accept="image/png, image/jpeg, image/gif">
                        @if ($funcionario->foto_path)
                            <small class="form-text text-muted">Foto atual: <a href="{{ asset('storage/' . $funcionario->foto_path) }}" target="_blank">Ver imagem</a>. Envie um novo ficheiro para substituir.</small>
                        @endif
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end">
                    <a href="{{ route('web.funcionarios.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary" style="background-color: var(--cor-primaria); border-color: var(--cor-primaria);">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
