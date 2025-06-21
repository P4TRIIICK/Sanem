<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sanem Medianeira</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --cor-primaria: #008080;
            --cor-acao: #E67E22;
            --cor-texto-claro: #f0f0f0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to top, rgba(0, 80, 80, 0.7), rgba(0, 40, 40, 0.8)), url('https://images.unsplash.com/photo-1532629345422-7515f3d16bb6?q=80&w=2070&auto=format&fit=crop') center center;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        /* --- O Card de Login Flutuante --- */
        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 40px;
            border-radius: 20px;
            position: relative;
            
            /* --- Efeito Glassmorphism (o principal) --- */
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px); /* Para Safari */
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);

            /* Animação de entrada */
            animation: fadeInScale 0.6s ease-out forwards;
        }
        
        /* --- Conteúdo dentro do card --- */
        .login-header {
            text-align: center;
            margin-bottom: 30px;
            color: var(--cor-texto-claro);
        }
        .login-header .icon {
            font-size: 48px;
        }
        .login-header h2 {
            font-weight: 700;
            margin-top: 15px;
            color: #fff;
        }
        .login-header p {
            font-weight: 300;
            opacity: 0.8;
        }
        
        /* --- Formulário --- */
        .form-label {
            color: var(--cor-texto-claro);
            font-weight: 400;
        }
        .form-control {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            border-radius: 8px;
            padding: 12px 15px;
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        .form-control:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: var(--cor-acao);
            box-shadow: 0 0 0 0.25rem rgba(230, 126, 34, 0.25);
            color: #fff;
        }
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.5);
            border: 1px solid rgba(220, 53, 69, 0.8);
            color: #fff;
        }
        
        /* --- Botão e Links --- */
        .btn-submit {
            background-color: var(--cor-acao);
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 12px 25px;
            border-radius: 8px;
            transition: all 0.3s ease;
            width: 100%;
        }
        .btn-submit:hover {
            background-color: #d35400;
            box-shadow: 0 4px 15px rgba(230, 126, 34, 0.4);
            transform: translateY(-2px);
        }
        .close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 24px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .close-btn:hover {
            color: #fff;
            transform: scale(1.1);
        }

        /* --- Animações --- */
        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>
<body>

    <div class="login-card">
        <a href="{{ route('home.public') }}" class="close-btn" title="Voltar para a Home">
            <i class="bi bi-x-lg"></i>
        </a>

        <div class="login-header">
            <div class="icon"><i class="bi bi-heart-pulse-fill"></i></div>
            <h2>SANEM</h2>
            <p>Acessar painel de gestão</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="seu@email.com">
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Senha</label>
                <input type="password" class="form-control" id="password" name="password" required placeholder="********">
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-submit">Entrar</button>
            </div>
        </form>
    </div>

</body>
</html>