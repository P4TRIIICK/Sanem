@extends('layouts.app')

@section('title', 'Dashboard - Sanem')

@section('content')

<style>
    /* Estilos dos Cards */
    .stat-card { background-color: #fff; border: none; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); transition: all 0.3s ease; height: 100%; text-decoration: none; display: block; }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    .stat-card .card-body { display: flex; align-items: center; color: var(--cor-texto); }
    .stat-card .icon { font-size: 3rem; opacity: 0.3; margin-right: 20px; }
    .stat-card .value { font-size: 2rem; font-weight: 700; color: var(--cor-primaria); }
    .stat-card .label { font-size: 0.9rem; color: #6c757d; }
    .icon-doacoes { color: var(--cor-acao); } .icon-beneficiarios { color: #3498db; }
    .icon-estoque { color: #2ecc71; } .icon-usuarios { color: #e74c3c; }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Painel de Controle</h1>
        @can('gerenciar-doacoes')
            {{-- CORREÇÃO: O link agora aponta para a rota de criação de doações --}}
            <a href="{{ route('web.doacoes.create') }}" class="btn btn-primary" style="background-color: var(--cor-primaria); border-color: var(--cor-primaria);">
                <i class="bi bi-plus-circle-fill me-2"></i>Registrar Nova Doação
            </a>
        @endcan
    </div>

    <div class="row g-4">
        @can('gerenciar-doacoes')
        <div class="col-xl-3 col-md-6">
            {{-- O link do card agora aponta para o histórico de doações --}}
            <a href="{{ route('web.doacoes.index') }}" class="stat-card"><div class="card-body"><div class="icon icon-doacoes"><i class="bi bi-gift-fill"></i></div><div><div class="value">{{$totalDoacoesMes ?? 0}}</div><div class="label">Doações no Mês</div></div></div></a>
        </div>
        @endcan

        @can('gerenciar-beneficiarios')
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('web.beneficiarios.index') }}" class="stat-card"><div class="card-body"><div class="icon icon-beneficiarios"><i class="bi bi-people-fill"></i></div><div><div class="value">{{ $totalBeneficiarios ?? 0 }}</div><div class="label">Beneficiários Ativos</div></div></div></a>
        </div>
        @endcan

        @can('gerenciar-estoque')
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('web.estoque.index') }}" class="stat-card"><div class="card-body"><div class="icon icon-estoque"><i class="bi bi-box-seam-fill"></i></div><div><div class="value">{{ $totalItensEstoque ?? 0 }}</div><div class="label">Itens em Estoque</div></div></div></a>
        </div>
        @endcan
        
        @role('Administrador')
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('web.funcionarios.index') }}" class="stat-card"><div class="card-body"><div class="icon icon-usuarios"><i class="bi bi-shield-lock-fill"></i></div><div><div class="value">{{ $totalFuncionarios ?? 0 }}</div><div class="label">Gerenciar Funcionários</div></div></div></a>
        </div>
        @endrole
    </div>

    @can('ver-relatorios')
    <div class="row g-4 mt-3">
        <div class="col-lg-7">
            <div class="card" style="height: 400px;"><div class="card-header">Doações por Categoria (Últimos 6 Meses)</div><div class="card-body d-flex justify-content-center align-items-center text-muted">(Área para o Gráfico)</div></div>
        </div>
        <div class="col-lg-5">
             <div class="card" style="height: 400px;"><div class="card-header">Últimas Doações Recebidas</div><div class="card-body d-flex justify-content-center align-items-center text-muted">(Área para a Tabela de Doações)</div></div>
        </div>
    </div>
    @endcan
</div>

@endsection
