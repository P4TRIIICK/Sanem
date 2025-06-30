<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Doação - Sanem</title>
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
        .item-doacao { border-bottom: 1px solid #eee; padding-bottom: 1rem; margin-bottom: 1rem; }
        .item-doacao:last-child { border-bottom: none; }
    </style>
</head>
<body>
    {{-- Menu Lateral (Sidebar) --}}
    <div class="sidebar">
        <div class="sidebar-header"><a href="{{ route('dashboard') }}" class="fs-4"><i class="bi bi-heart-pulse-fill"></i> <strong>SANEM</strong></a></div>
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
        <h1 class="h2 mb-4">Registrar Nova Doação</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Ocorreram erros:</strong>
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <form action="{{ route('doacoes.store') }}" method="POST" class="card shadow-sm"><div class="card-body">
            @csrf
            <div class="row g-3 mb-4">
                <div class="col-md-4"><label for="pessoa_id" class="form-label">Doador</label><select id="pessoa_id" name="pessoa_id" class="form-select"><option value="">Selecione...</option>@foreach($doadores as $doador)<option value="{{ $doador->id }}">{{ $doador->nome }}</option>@endforeach</select></div>
                <div class="col-md-4"><label for="data_doacao" class="form-label">Data da Doação</label><input type="date" id="data_doacao" name="data_doacao" value="{{ date('Y-m-d') }}" class="form-control"></div>
                <div class="col-md-4"><label for="status_doacao" class="form-label">Status da Doação</label><select id="status_doacao" name="status_doacao" class="form-select"><option>RECEBIDO</option><option>EM_ANALISE</option></select></div>
            </div>
            <hr>
            <h5 class="mt-4">Itens da Doação</h5>
            <div id="itens-container">
                <div class="row g-3 align-items-end item-doacao">
                    <div class="col-md-6"><label class="form-label">Produto</label><select name="itens[0][produto_id]" class="form-select">@foreach($produtos as $produto)<option value="{{ $produto->id }}">{{ $produto->nome }}</option>@endforeach</select></div>
                    <div class="col-md-4"><label class="form-label">Quantidade</label><input type="number" name="itens[0][quantidade]" class="form-control" min="1" value="1"></div>
                </div>
            </div>
            <button type="button" id="add-item-btn" class="btn btn-outline-secondary mt-3"><i class="bi bi-plus-circle me-1"></i> Adicionar item</button>
            <div class="mt-4 text-end"><button type="submit" class="btn btn-success btn-lg">Salvar Doação</button></div>
        </div></form>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('itens-container');
        const addItemBtn = document.getElementById('add-item-btn');
        let itemIndex = 1;

        const produtoOptions = `@foreach($produtos as $produto)<option value="{{ $produto->id }}">{{ $produto->nome }}</option>@endforeach`;

        addItemBtn.addEventListener('click', function () {
            const newItemRow = document.createElement('div');
            newItemRow.classList.add('row', 'g-3', 'align-items-end', 'item-doacao');
            newItemRow.innerHTML = `
                <div class="col-md-6"><select name="itens[${itemIndex}][produto_id]" class="form-select">${produtoOptions}</select></div>
                <div class="col-md-4"><input type="number" name="itens[${itemIndex}][quantidade]" class="form-control" min="1" value="1"></div>
                <div class="col-md-2"><button type="button" class="btn btn-danger w-100 remove-item-btn">Remover</button></div>
            `;
            container.appendChild(newItemRow);
            itemIndex++;
        });

        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-item-btn')) {
                e.target.closest('.item-doacao').remove();
            }
        });
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
