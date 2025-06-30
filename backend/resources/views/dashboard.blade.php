<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sanem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --cor-primaria: #008080;
            --cor-acao: #E67E22;
<<<<<<< Updated upstream
            --cor-fundo: #f4f8fa; /* Tom de cinza/azul mais claro */
=======
            --cor-fundo: #f4f8fa;
>>>>>>> Stashed changes
            --sidebar-bg: #212529;
            --sidebar-link-color: rgba(255, 255, 255, 0.7);
            --sidebar-link-hover: #fff;
            --sidebar-link-active: #fff;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--cor-fundo);
        }
        .sidebar {
            width: 280px;
            background-color: var(--sidebar-bg);
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            padding-top: 20px;
            z-index: 1000;
        }
        .sidebar .nav-link {
            color: var(--sidebar-link-color);
            padding: 12px 20px;
            font-size: 1rem;
            display: flex;
            align-items: center;
            border-left: 3px solid transparent;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link i {
            margin-right: 15px;
            font-size: 1.2rem;
            width: 20px;
            text-align: center;
        }
        .sidebar .nav-link:hover {
            color: var(--sidebar-link-hover);
            background-color: rgba(255, 255, 255, 0.05);
        }
        .sidebar .nav-link.active {
            color: var(--sidebar-link-active);
            background-color: var(--cor-primaria);
            border-left: 3px solid var(--cor-acao);
        }
        .sidebar-header {
            padding: 0 20px 20px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .sidebar-header a {
            color: #fff;
            text-decoration: none;
            font-weight: 700;
        }
        .main-content {
<<<<<<< Updated upstream
            margin-left: 280px; /* Mesma largura do sidebar */
=======
            margin-left: 280px;
>>>>>>> Stashed changes
            padding: 30px;
        }
        .user-dropdown {
            position: absolute;
            bottom: 0;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.2);
        }
        .user-dropdown .nav-link {
<<<<<<< Updated upstream
             border-left: none; /* Remover borda do link do usuário */
=======
            border-left: none;
>>>>>>> Stashed changes
        }
        .user-dropdown .nav-link.active, .user-dropdown .nav-link:hover {
            background: none;
        }
<<<<<<< Updated upstream
        /* Estilos dos Cards */
=======
>>>>>>> Stashed changes
        .stat-card { background-color: #fff; border: none; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); transition: all 0.3s ease; height: 100%; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .stat-card .card-body { display: flex; align-items: center; }
        .stat-card .icon { font-size: 3rem; opacity: 0.3; margin-right: 20px; }
        .stat-card .value { font-size: 2rem; font-weight: 700; color: var(--cor-primaria); }
        .stat-card .label { font-size: 0.9rem; color: #6c757d; }
        .icon-doacoes { color: var(--cor-acao); } .icon-beneficiarios { color: #3498db; }
        .icon-estoque { color: #2ecc71; } .icon-usuarios { color: #e74c3c; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('dashboard') }}" class="fs-4">
                <i class="bi bi-heart-pulse-fill"></i> <strong>SANEM</strong>
            </a>
        </div>
        
        <ul class="nav flex-column mt-3">
            @can('acessar-dashboard')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>
            </li>
            @endcan

            @can('gerenciar-beneficiarios')
            <li class="nav-item">
            <a class="nav-link {{ request()->is('beneficiarios*') ? 'active' : '' }}"
                href="/beneficiarios">
                <i class="bi bi-people-fill"></i> Beneficiários
            </a>
            </li>
            @endcan
            
            @can('gerenciar-doacoes')
            <li class="nav-item">
<<<<<<< Updated upstream
                <a class="nav-link" href="#"> {{-- Substituir '#' pela rota de doações --}}
=======
                {{-- ESTA É A ÚNICA ALTERAÇÃO REAL, O LINK CORRIGIDO --}}
                <a class="nav-link {{ request()->is('doacoes*') ? 'active' : '' }}" href="{{ route('doacoes.index') }}">
>>>>>>> Stashed changes
                    <i class="bi bi-gift-fill"></i> Doações
                </a>
            </li>
            @endcan

            @can('gerenciar-estoque')
            <li class="nav-item">
<<<<<<< Updated upstream
                <a class="nav-link" href="#"> {{-- Substituir '#' pela rota de estoque --}}
=======
                <a class="nav-link" href="#">
>>>>>>> Stashed changes
                    <i class="bi bi-box-seam-fill"></i> Estoque
                </a>
            </li>
            @endcan

            @role('Administrador')
            <li class="nav-item">
<<<<<<< Updated upstream
                <a class="nav-link" href="{{ route('web.funcionarios.index') }}">
=======
                <a class="nav-link" href="#">
>>>>>>> Stashed changes
                    <i class="bi bi-person-rolodex"></i> Funcionários
                </a>
            </li>
            @endrole
        </ul>

        <div class="user-dropdown">
            <div class="dropdown">
                <a href="#" class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
<<<<<<< Updated upstream
                    <i class="bi bi-person-circle"></i> {{ ($user ?? Auth::user())->name }}
=======
                    <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
>>>>>>> Stashed changes
                </a>
                <ul class="dropdown-menu dropdown-menu-dark">
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

    <main class="main-content">
        <div class="container-fluid">
            @can('acessar-dashboard')
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h2">Painel de Controle</h1>
                    @can('gerenciar-doacoes')
<<<<<<< Updated upstream
                        <a href="#" class="btn btn-primary">
                            <i class="bi bi-plus-circle-fill me-2"></i>Registrar Nova Doação
=======
                        <a href="{{ route('doacoes.create') }}" class="btn btn-primary">
                           <i class="bi bi-plus-circle-fill me-2"></i>Registrar Nova Doação
>>>>>>> Stashed changes
                        </a>
                    @endcan
                </div>

                <div class="row g-4">
<<<<<<< Updated upstream
=======
                    {{-- ESTE TRECHO FOI RESTAURADO --}}
>>>>>>> Stashed changes
                    @can('gerenciar-doacoes')
                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card"><div class="card-body"><div class="icon icon-doacoes"><i class="bi bi-gift-fill"></i></div><div><div class="value">32</div><div class="label">Doações no Mês</div></div></div></div>
                    </div>
                    @endcan

                    @can('gerenciar-beneficiarios')
                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card"><div class="card-body"><div class="icon icon-beneficiarios"><i class="bi bi-people-fill"></i></div><div><div class="value">124</div><div class="label">Beneficiários Ativos</div></div></div></div>
                    </div>
                    @endcan

                    @can('gerenciar-estoque')
                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card"><div class="card-body"><div class="icon icon-estoque"><i class="bi bi-box-seam-fill"></i></div><div><div class="value">458</div><div class="label">Itens em Estoque</div></div></div></div>
                    </div>
                    @endcan
                    
                    @role('Administrador')
                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card"><div class="card-body"><div class="icon icon-usuarios"><i class="bi bi-shield-lock-fill"></i></div><div><div class="value">3</div><div class="label">Gerenciar Usuários</div></div></div></div>
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

            @else
                <div class="alert alert-info">
<<<<<<< Updated upstream
                    <h1 class="display-6">Bem-vindo(a), {{ ($user ?? Auth::user())->name }}!</h1>
=======
                    <h1 class="display-6">Bem-vindo(a), {{ Auth::user()->name }}!</h1>
>>>>>>> Stashed changes
                    <p class="lead">Obrigado por fazer parte da família Sanem. Esta é a sua área pessoal.</p>
                    <hr>
                    @can('registrar-propria-doacao')
                        <a href="#" class="btn btn-success"><i class="bi bi-plus-circle me-1"></i> Quero Fazer uma Doação</a>
                    @endcan
                </div>
            @endcan
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
<<<<<<< Updated upstream
</html>
=======
</html>
>>>>>>> Stashed changes
