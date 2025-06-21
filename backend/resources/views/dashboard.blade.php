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
            --cor-fundo: #f0f2f5;
            --cor-texto: #333;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--cor-fundo);
        }

        /* --- Navbar do Dashboard --- */
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

        /* --- Conteúdo Principal --- */
        .main-content {
            padding: 30px;
        }

        /* --- Cards de Estatísticas (KPIs) --- */
        .stat-card {
            background-color: #fff;
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .stat-card .card-body {
            display: flex;
            align-items: center;
        }
        .stat-card .icon {
            font-size: 3rem;
            opacity: 0.3;
            margin-right: 20px;
        }
        .stat-card .value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--cor-primaria);
        }
        .stat-card .label {
            font-size: 0.9rem;
            color: #6c757d;
        }
        /* Cores específicas para os ícones dos cards */
        .icon-doacoes { color: var(--cor-acao); }
        .icon-beneficiarios { color: #3498db; }
        .icon-estoque { color: #2ecc71; }
        .icon-voluntarios { color: #9b59b6; }
    </style>
</head>
<body>

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
                        {{ ($user ?? Auth::user())->name }}
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

    <main class="main-content">
        <div class="container-fluid">
            <h1 class="h2 mb-4">Painel de Controle</h1>

            <div class="row g-4">
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card">
                        <div class="card-body">
                            <div class="icon icon-doacoes"><i class="bi bi-gift-fill"></i></div>
                            <div>
                                <div class="value">32</div>
                                <div class="label">Doações no Mês</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card">
                        <div class="card-body">
                            <div class="icon icon-beneficiarios"><i class="bi bi-people-fill"></i></div>
                            <div>
                                <div class="value">124</div>
                                <div class="label">Beneficiários Ativos</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card">
                        <div class="card-body">
                            <div class="icon icon-estoque"><i class="bi bi-box-seam-fill"></i></div>
                            <div>
                                <div class="value">458</div>
                                <div class="label">Itens em Estoque</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card">
                        <div class="card-body">
                            <div class="icon icon-voluntarios"><i class="bi bi-person-heart"></i></div>
                            <div>
                                <div class="value">47</div>
                                <div class="label">Voluntários</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-3">
                <div class="col-lg-7">
                    <div class="card" style="height: 400px;">
                        <div class="card-header">Doações por Categoria (Últimos 6 Meses)</div>
                        <div class="card-body d-flex justify-content-center align-items-center text-muted">
                            (Área para o Gráfico)
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                     <div class="card" style="height: 400px;">
                        <div class="card-header">Últimas Doações Recebidas</div>
                        <div class="card-body d-flex justify-content-center align-items-center text-muted">
                            (Área para a Tabela de Doações)
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>