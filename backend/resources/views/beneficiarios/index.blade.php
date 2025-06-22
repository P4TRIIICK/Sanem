<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beneficiários - Sanem</title>

    {{-- CSS Links (os mesmos do seu dashboard para manter a consistência) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --cor-primaria: #008080;
            --cor-acao: #E67E22;
            --cor-fundo: #f0f2f5;
            --cor-texto: #333;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--cor-fundo);
        }
        .navbar-dashboard {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .navbar-brand strong {
            color: var(--cor-primaria);
            font-weight: 700;
        }
        .dropdown-item:active {
            background-color: var(--cor-primaria);
        }
        .main-content {
            padding: 30px;
        }
        .table-custom {
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .table-custom thead {
            background-color: var(--cor-primaria);
            color: #fff;
        }
        /* Estilo para os links de paginação */
        .pagination .page-item.active .page-link {
            background-color: var(--cor-primaria);
            border-color: var(--cor-primaria);
        }
        .pagination .page-link {
            color: var(--cor-primaria);
        }
    </style>
</head>
<body>

    {{-- Barra de Navegação --}}
    <nav class="navbar navbar-expand-lg navbar-dashboard">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-heart-pulse-fill"></i>
                <strong>SANEM</strong>
            </a>
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <a href="#" class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle me-1"></i>
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Meu Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right me-2"></i>Sair
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    {{-- Conteúdo Principal da Página --}}
    <main class="main-content">
        <div class="container-fluid">

            {{-- Cabeçalho da Página --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2">Gestão de Beneficiários</h1>
                @can('gerenciar-beneficiarios')
                    <a href="{{ route('beneficiarios.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle-fill me-1"></i> Novo Beneficiário
                    </a>
                @endcan
            </div>

            {{-- Card que conterá a tabela --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-custom mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">#ID</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">CPF</th>
                                    <th scope="col">Status da Conta</th>
                                    <th scope="col">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- 1. LOOP DINÂMICO ADICIONADO AQUI --}}
                                @forelse ($beneficiarios as $beneficiario)
                                    <tr>
                                        <th scope="row">{{ $beneficiario->id }}</th>
                                        <td>{{ $beneficiario->nome }}</td>
                                        <td>{{ $beneficiario->cpf }}</td>
                                        <td>
                                            {{-- Verifica se a pessoa tem dados de beneficiário associados --}}
                                            @if($beneficiario->beneficiario)
                                                <span class="badge 
                                                    @if($beneficiario->beneficiario->status_conta == 'CONTA_APROVADA') bg-success 
                                                    @elseif($beneficiario->beneficiario->status_conta == 'CONTA_NEGADA') bg-danger
                                                    @else bg-warning text-dark @endif">
                                                    {{ $beneficiario->beneficiario->status_conta }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">Não é Beneficiário</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-info" title="Ver Detalhes"><i class="bi bi-eye-fill"></i></a>
                                            @can('gerenciar-beneficiarios')
                                                <a href="#" class="btn btn-sm btn-warning" title="Editar"><i class="bi bi-pencil-fill"></i></a>
                                            @endcan
                                            @role('Administrador')
                                                <a href="#" class="btn btn-sm btn-danger" title="Excluir"><i class="bi bi-trash-fill"></i></a>
                                            @endrole
                                        </td>
                                    </tr>
                                @empty
                                    {{-- Esta linha aparece se a coleção $beneficiarios estiver vazia --}}
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            Nenhum beneficiário encontrado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    {{-- 2. LINKS DE PAGINAÇÃO ADICIONADOS AQUI --}}
                    {{ $beneficiarios->links() }}
                </div>
            </div>

        </div>
    </main>

    {{-- JS Link --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>