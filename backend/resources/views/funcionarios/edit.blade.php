@extends('layouts.app')

@section('title', 'Editar Funcionário')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('web.funcionarios.index') }}" class="btn btn-outline-primary me-3" title="Voltar">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="h2 mb-0">Editar Funcionário</h1>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h5 class="card-title mb-4">Editando: {{ $funcionario->nome }}</h5>
            
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

            <form action="{{ route('web.funcionarios.update', $funcionario->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $funcionario->email) }}" required>
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="mb-3">Alterar Senha (Opcional)</h5>
                <p class="text-muted">Deixe os campos abaixo em branco se não desejar alterar a senha.</p>
                 <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Nova Senha</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                     <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                        <input class="form-control" type="password" id="password_confirmation" name="password_confirmation">
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end">
                    <a href="{{ route('web.funcionarios.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary" style="background-color: var(--cor-primaria); border-color: var(--cor-primaria);">
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
