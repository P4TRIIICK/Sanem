<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Novo Beneficiário - Sanem</title>

    {{-- CSS Links --}}
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
        .main-content {
            padding: 30px;
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
                {{-- Dropdown do Usuário (removido por simplicidade, adicione de volta se necessário) --}}
            </div>
        </div>
    </nav>

    {{-- Conteúdo Principal da Página --}}
    <main class="main-content">
        <div class="container-fluid">
            
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('beneficiarios.index') }}" class="btn btn-outline-primary me-3" title="Voltar">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="h2 mb-0">Cadastrar Novo Beneficiário</h1>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form id="form-create-beneficiario" action="{{ route('beneficiarios.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nome" class="form-label">Nome Completo</label>
                                <input type="text" class="form-control" id="nome" name="nome" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cpf" class="form-label">CPF</label>
                                <input type="text" class="form-control" id="cpf" name="cpf" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nascimento" class="form-label">Data de Nascimento</label>
                                <input type="date" class="form-control" id="nascimento" name="nascimento">
                            </div>
                        </div>

                        <div class="row">
                             <div class="col-md-6 mb-3">
                                <label for="genero" class="form-label">Gênero</label>
                                <select class="form-select" id="genero" name="genero" required>
                                    <option value="FEMININO">Feminino</option>
                                    <option value="MASCULINO">Masculino</option>
                                    <option value="OUTRO">Outro</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Senha Provisória</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>
                        
                        {{-- Adicionando campos que faltavam do seu modelo Pessoa --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="rg" class="form-label">RG (Opcional)</label>
                                <input type="text" class="form-control" id="rg" name="rg">
                            </div>
                             <div class="col-md-6 mb-3">
                                <label for="tipo_beneficiario" class="form-label">Tipo</label>
                                <select class="form-select" id="tipo_beneficiario" name="tipo_beneficiario" required>
                                    <option value="BENEFICIARIO">Beneficiário</option>
                                    <option value="DOADOR">Doador</option>
                                    <option value="BENEFICIARIO_DOADOR">Beneficiário e Doador</option>
                                </select>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('beneficiarios.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Salvar Beneficiário</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </main>

    {{-- JS Link do Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('form-create-beneficiario').addEventListener('submit', function(event) {
            event.preventDefault(); // Previne o recarregamento da página

            const form = event.target;
            const formData = new FormData(form);
            const actionUrl = form.action;

            // Pega o token CSRF da meta tag que adicionamos no <head>
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(actionUrl, {
                method: 'POST',
                headers: {
                    // Importante para o Laravel aceitar a requisição
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json', // Esperamos uma resposta em JSON
                },
                body: formData
            })
            .then(response => {
                // Para o nosso teste com dd(), o navegador vai simplesmente mostrar o resultado.
                // Esta parte se torna mais importante quando o backend retornar um JSON de verdade.
                if (!response.ok) {
                    // Se a API retornar um erro (ex: validação), podemos tratar aqui no futuro.
                    console.error('A resposta da rede não foi OK');
                }
                // Se o dd() for executado no backend, o browser vai parar e mostrar o dump.
                // Se a requisição for um sucesso (sem dd()), podemos redirecionar o usuário.
                console.log('Requisição enviada com sucesso!');

                // Futuramente, descomente esta linha para redirecionar após o sucesso.
                // window.location.href = '{{ route("beneficiarios.index") }}' + '?success=1';
            })
            .catch(error => {
                console.error('Houve um problema com a sua requisição fetch:', error);
                alert('Ocorreu um erro ao salvar. Verifique o console.');
            });
        });
    </script>
</body>
</html>