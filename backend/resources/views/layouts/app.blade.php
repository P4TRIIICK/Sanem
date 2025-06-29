<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sanem')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --cor-primaria: #008080;
            --cor-acao: #E67E22;
            --cor-fundo: #f4f8fa;
            --sidebar-bg: #212529;
            --sidebar-link-color: rgba(255, 255, 255, 0.7);
            --sidebar-link-hover: #fff;
            --sidebar-link-active: #fff;
        }
        body { font-family: 'Poppins', sans-serif; background-color: var(--cor-fundo); }
        .sidebar { width: 280px; background-color: var(--sidebar-bg); color: #fff; position: fixed; top: 0; left: 0; height: 100vh; padding-top: 20px; z-index: 1000; }
        .sidebar .nav-link { color: var(--sidebar-link-color); padding: 12px 20px; font-size: 1rem; display: flex; align-items: center; border-left: 3px solid transparent; transition: all 0.3s ease; }
        .sidebar .nav-link i { margin-right: 15px; font-size: 1.2rem; width: 20px; text-align: center; }
        .sidebar .nav-link:hover { color: var(--sidebar-link-hover); background-color: rgba(255, 255, 255, 0.05); }
        .sidebar .nav-link.active { color: var(--sidebar-link-active); background-color: var(--cor-primaria); border-left: 3px solid var(--cor-acao); }
        .sidebar-header { padding: 0 20px 20px 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        .sidebar-header a { color: #fff; text-decoration: none; font-weight: 700; }
        .main-content { margin-left: 280px; padding: 30px; }
        .user-dropdown { position: absolute; bottom: 0; width: 100%; background-color: rgba(0, 0, 0, 0.2); }
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
           <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('beneficiarios*') ? 'active' : '' }}" href="{{ route('web.beneficiarios.index') }}">
                    <i class="bi bi-people-fill"></i> Beneficiários
                </a>
            </li>
             <li class="nav-item">
                {{-- CORREÇÃO: O link agora usa o nome de rota 'web.estoque.index' --}}
                <a class="nav-link {{ request()->is('estoque*') ? 'active' : '' }}" href="{{ route('web.estoque.index') }}">
                    <i class="bi bi-box-seam-fill"></i> Estoque
                </a>
            </li>
            
            @role('Administrador')
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('funcionarios*') ? 'active' : '' }}" href="{{ route('web.funcionarios.index') }}">
                        <i class="bi bi-person-rolodex"></i> Funcionários
                    </a>
                </li>
            @endrole
        </ul>
        <div class="user-dropdown">
           <div class="dropdown">
                <a href="#" class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
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
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
