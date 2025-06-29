@extends('layouts.app')

@section('title', 'Detalhes do Funcionário')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('web.funcionarios.index') }}" class="btn btn-outline-primary me-3" title="Voltar">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="h2 mb-0">Detalhes de: {{ $funcionario->nome }}</h1>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="row">
                {{-- CORREÇÃO: Coluna para Foto e Dados Profissionais --}}
                <div class="col-md-4 text-center">
                    @if($funcionario->foto_path)
                        <img src="{{ asset('storage/' . $funcionario->foto_path) }}" alt="Foto de {{ $funcionario->nome }}" class="img-thumbnail rounded-circle mb-3" width="150" height="150" style="object-fit: cover;">
                    @else
                        {{-- Placeholder se não houver foto --}}
                        <div class="border rounded-circle bg-light d-flex align-items-center justify-content-center mb-3" style="width: 150px; height: 150px;">
                            <i class="bi bi-person-fill" style="font-size: 4rem; color: #ccc;"></i>
                        </div>
                    @endif
                    
                    <h5 class="mb-3 mt-3">Dados Profissionais</h5>
                    @if($funcionario->funcionario)
                    <p><strong>Cargo:</strong> {{ $funcionario->funcionario->nivel_acesso }}</p>
                    <p><strong>Salário:</strong> R$ {{ number_format($funcionario->funcionario->salario ?? 0, 2, ',', '.') }}</p>
                    <p><strong>Data de Contratação:</strong> {{ \Carbon\Carbon::parse($funcionario->funcionario->data_contratacao)->format('d/m/Y') }}</p>
                    @else
                    <p class="text-muted">Sem dados profissionais registados.</p>
                    @endif
                </div>

                {{-- Coluna de Dados Pessoais e de Contato --}}
                <div class="col-md-8 border-start ps-4">
                    <h5 class="mb-3">Dados Pessoais</h5>
                    <p><strong>Nome Completo:</strong> {{ $funcionario->nome }}</p>
                    <p><strong>CPF:</strong> {{ $funcionario->formatted_cpf }}</p>
                    <p><strong>RG:</strong> {{ $funcionario->rg ?? 'Não informado' }}</p>
                    <p><strong>Data de Nascimento:</strong> {{ \Carbon\Carbon::parse($funcionario->nascimento)->format('d/m/Y') }}</p>

                    <hr class="my-4">
                    <h5 class="mb-3">Contato e Endereço</h5>
                    <p><strong>Email de Acesso:</strong> {{ $funcionario->email }}</p>
                    <p><strong>Telefone:</strong> {{ $funcionario->telefones->first()->numero ?? 'Não informado' }}</p>
                    <p><strong>Endereço:</strong> 
                        {{ $funcionario->endereco->logradouro ?? '' }}, {{ $funcionario->endereco->numero ?? 's/n' }} - 
                        {{ $funcionario->endereco->bairro ?? '' }}, 
                        {{ $funcionario->endereco->cidade->nome ?? '' }} - {{ $funcionario->endereco->cidade->estado->nome ?? '' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
