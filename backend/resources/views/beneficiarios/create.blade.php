@extends('layouts.app')

@section('title', 'Novo Beneficiário - Sanem')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        {{-- CORREÇÃO: Usando o novo nome da rota para voltar --}}
        <a href="{{ route('web.beneficiarios.index') }}" class="btn btn-outline-primary me-3" title="Voltar">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="h2 mb-0">Cadastrar Novo Beneficiário</h1>
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

            {{-- CORREÇÃO: Usando o novo nome da rota para salvar --}}
            <form action="{{ route('web.beneficiarios.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <h5 class="mb-3">Dados Pessoais</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nome" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="cpf" class="form-label">CPF</label>
                        <input type="text" class="form-control" id="cpf" name="cpf" value="{{ old('cpf') }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nascimento" class="form-label">Data de Nascimento</label>
                        <input type="date" class="form-control" id="nascimento" name="nascimento" value="{{ old('nascimento') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="rg" class="form-label">RG (Opcional)</label>
                        <input type="text" class="form-control" id="rg" name="rg" value="{{ old('rg') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="genero" class="form-label">Gênero</label>
                        <select class="form-select" id="genero" name="genero" required>
                            <option value="FEMININO" {{ old('genero') == 'FEMININO' ? 'selected' : '' }}>Feminino</option>
                            <option value="MASCULINO" {{ old('genero') == 'MASCULINO' ? 'selected' : '' }}>Masculino</option>
                            <option value="OUTRO" {{ old('genero') == 'OUTRO' ? 'selected' : '' }}>Outro</option>
                        </select>
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="mb-3">Dados Socioeconômicos</h5>
                 <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="renda" class="form-label">Renda Familiar (R$)</label>
                        <input type="number" step="0.01" class="form-control" id="renda" name="renda" placeholder="Ex: 1500.50" value="{{ old('renda') }}">
                    </div>
                     <div class="col-md-6 mb-3">
                        <label for="foto" class="form-label">Foto 3x4 do Beneficiário</label>
                        <input class="form-control" type="file" id="foto" name="foto" accept="image/png, image/jpeg, image/gif">
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end">
                    {{-- CORREÇÃO: Usando o novo nome da rota para cancelar --}}
                    <a href="{{ route('web.beneficiarios.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary" style="background-color: var(--cor-primaria); border-color: var(--cor-primaria);">
                        Salvar Beneficiário
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
