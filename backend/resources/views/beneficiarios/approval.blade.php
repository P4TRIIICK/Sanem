@extends('layouts.app')

@section('title', 'Gerenciar Status do Beneficiário')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('web.beneficiarios.index') }}" class="btn btn-outline-primary me-3" title="Voltar">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="h2 mb-0">Gerenciar Status de Beneficiário</h1>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header">
            <h5 class="card-title mb-0">Revisão de Cadastro</h5>
        </div>
        <div class="card-body p-4">
            {{-- CORREÇÃO: Adicionada verificação para garantir que $pessoa->beneficiario existe --}}
            @if($pessoa->beneficiario)
                <div class="row">
                    <div class="col-md-8">
                        <p><strong>Nome:</strong> {{ $pessoa->nome }}</p>
                        <p><strong>CPF:</strong> {{ $pessoa->cpf }}</p>
                        <p><strong>Renda Declarada:</strong> R$ {{ number_format($pessoa->beneficiario->renda ?? 0, 2, ',', '.') }}</p>
                        <p><strong>Status Atual:</strong> {{ $pessoa->beneficiario->status ?? 'Não definido' }}</p>
                    </div>
                    <div class="col-md-4 text-center">
                        @if($pessoa->beneficiario->foto_path)
                            <img src="{{ asset('storage/' . $pessoa->beneficiario->foto_path) }}" alt="Foto de {{ $pessoa->nome }}" class="img-thumbnail" width="150">
                        @else
                            <div class="border rounded bg-light d-flex align-items-center justify-content-center" style="width: 150px; height: 200px;">
                                <span>Sem Foto</span>
                            </div>
                        @endif
                    </div>
                </div>

                <hr class="my-4">
                
                <p>Por favor, revise os dados acima e tome uma ação.</p>
                
                <form action="{{ route('web.beneficiarios.processApproval', $pessoa->id) }}" method="POST">
                    @csrf
                    <div class="d-flex justify-content-end">
                        <button type="submit" name="status" value="REPROVADO" class="btn btn-danger me-2">
                            <i class="bi bi-x-circle-fill me-1"></i> Reprovar Cadastro
                        </button>
                        <button type="submit" name="status" value="APROVADO" class="btn btn-success">
                            <i class="bi bi-check-circle-fill me-1"></i> Aprovar Cadastro
                        </button>
                    </div>
                </form>
            @else
                <div class="alert alert-warning" role="alert">
                    <strong>Atenção!</strong> Esta pessoa não possui um registo de beneficiário associado. Não é possível gerir o status.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
