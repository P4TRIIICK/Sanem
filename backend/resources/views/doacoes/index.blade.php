<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Doações - Sanem</title>
    {{-- Estilos do Bootstrap e Fontes --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    {{-- Estilos CSS da sua dashboard --}}
    <style>
        :root { --cor-primaria: #008080; --cor-acao: #E67E22; --cor-fundo: #f4f8fa; --sidebar-bg: #212529; --sidebar-link-color: rgba(255, 255, 255, 0.7); --sidebar-link-hover: #fff; --sidebar-link-active: #fff; }
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
        .user-dropdown .nav-link { border-left: none; }
        .user-dropdown .nav-link.active, .user-dropdown .nav-link:hover { background: none; }
    </style>
</head>
<body>
    {{-- Menu Lateral (Sidebar) --}}
    <div class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('dashboard') }}" class="fs-4"><i class="bi bi-heart-pulse-fill"></i> <strong>SANEM</strong></a>
        </div>
        <ul class="nav flex-column mt-3">
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->is('beneficiarios*') ? 'active' : '' }}" href="/beneficiarios"><i class="bi bi-people-fill"></i> Beneficiários</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->is('doacoes*') ? 'active' : '' }}" href="{{ route('doacoes.index') }}"><i class="bi bi-gift-fill"></i> Doações</a></li>
            <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-box-seam-fill"></i> Estoque</a></li>
            <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-person-rolodex"></i> Funcionários</a></li>
        </ul>
        <div class="user-dropdown">{{-- Dropdown do usuário --}}</div>
    </div>

    {{-- Conteúdo Principal --}}
    <main class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2">Lista de Doações</h1>
            <a href="{{ route('doacoes.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle-fill me-2"></i>Registrar Nova Doação</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th><th>Doador</th><th>Data</th><th>Status</th><th>Entrega</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($doacoes as $doacao)
                            <tr>
                                <td>{{ $doacao->id }}</td>
                                <td>{{ $doacao->doador->nome ?? 'N/A' }}</td>
                                <td>{{ $doacao->data_doacao->format('d/m/Y') }}</td>
                                <td><span class="badge bg-success-subtle text-success-emphasis rounded-pill">{{ $doacao->status_doacao }}</span></td>
                                <td><span class="badge bg-warning-subtle text-warning-emphasis rounded-pill">{{ $doacao->status_entrega }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4">Nenhuma doação encontrada.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">{{ $doacoes->links() }}</div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
