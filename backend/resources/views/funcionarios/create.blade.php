@extends('layouts.app')

@section('title', 'Novo Funcionário - Sanem')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('web.funcionarios.index') }}" class="btn btn-outline-primary me-3" title="Voltar">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="h2 mb-0">Cadastrar Novo Funcionário</h1>
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

            <form action="{{ route('web.funcionarios.store') }}" method="POST">
                @csrf
                
                {{-- Dados Pessoais --}}
                <h5 class="mb-3">Dados Pessoais</h5>
                <div class="row">
                    <div class="col-md-6 mb-3"><label for="nome" class="form-label">Nome Completo</label><input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome') }}" required></div>
                    <div class="col-md-6 mb-3"><label for="cpf" class="form-label">CPF</label><input type="text" class="form-control" id="cpf" name="cpf" value="{{ old('cpf') }}" required></div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3"><label for="rg" class="form-label">RG (Opcional)</label><input type="text" class="form-control" id="rg" name="rg" value="{{ old('rg') }}"></div>
                    <div class="col-md-6 mb-3"><label for="nascimento" class="form-label">Data de Nascimento</label><input type="date" class="form-control" id="nascimento" name="nascimento" value="{{ old('nascimento') }}" required></div>
                </div>

                <hr class="my-4">

                {{-- Contato e Endereço --}}
                <h5 class="mb-3">Contato e Endereço</h5>
                 <div class="row">
                    {{-- CORREÇÃO: Adicionados campos de endereço que faltavam --}}
                    <div class="col-md-6 mb-3"><label for="logradouro" class="form-label">Logradouro (Rua, Av.)</label><input type="text" class="form-control" id="logradouro" name="logradouro" value="{{ old('logradouro') }}" required></div>
                    <div class="col-md-3 mb-3"><label for="numero" class="form-label">Número</label><input type="text" class="form-control" id="numero" name="numero" value="{{ old('numero') }}"></div>
                    <div class="col-md-3 mb-3"><label for="cep" class="form-label">CEP</label><input type="text" class="form-control" id="cep" name="cep" value="{{ old('cep') }}"></div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3"><label for="bairro" class="form-label">Bairro</label><input type="text" class="form-control" id="bairro" name="bairro" value="{{ old('bairro') }}" required></div>
                    <div class="col-md-4 mb-3"><label for="cidade" class="form-label">Cidade</label><input type="text" class="form-control" id="cidade" name="cidade" value="{{ old('cidade') }}" required></div>
                    <div class="col-md-4 mb-3"><label for="estado" class="form-label">Estado</label><input type="text" class="form-control" id="estado" name="estado" value="{{ old('estado') }}" required></div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3"><label for="telefone" class="form-label">Telefone (Opcional)</label><input type="tel" class="form-control" id="telefone" name="telefone" placeholder="(XX) XXXXX-XXXX" value="{{ old('telefone') }}"></div>
                </div>

                <hr class="my-4">

                {{-- Acesso e Dados Profissionais --}}
                <h5 class="mb-3">Acesso e Dados Profissionais</h5>
                <div class="row">
                    <div class="col-md-6 mb-3"><label for="email" class="form-label">Email de Acesso</label><input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required></div>
                    <div class="col-md-6 mb-3"><label for="password" class="form-label">Senha Provisória</label><input type="password" class="form-control" id="password" name="password" required></div>
                </div>
                 <div class="row">
                    <div class="col-md-4 mb-3"><label for="role" class="form-label">Cargo / Função</label><select class="form-select" id="role" name="role" required><option value="" disabled selected>Selecione um cargo</option><option value="Consultor" @if(old('role') == 'Consultor') selected @endif>Consultor</option><option value="Administrador" @if(old('role') == 'Administrador') selected @endif>Administrador</option></select></div>
                    <div class="col-md-4 mb-3"><label for="salario" class="form-label">Salário (R$)</label><input type="number" step="0.01" class="form-control" id="salario" name="salario" placeholder="Ex: 3500.50" value="{{ old('salario') }}"></div>
                    <div class="col-md-4 mb-3"><label for="data_contratacao" class="form-label">Data de Contratação</label><input type="date" class="form-control" id="data_contratacao" name="data_contratacao" value="{{ old('data_contratacao', date('Y-m-d')) }}" required></div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end">
                    <a href="{{ route('web.funcionarios.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary" style="background-color: var(--cor-primaria); border-color: var(--cor-primaria);">Salvar Funcionário</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
