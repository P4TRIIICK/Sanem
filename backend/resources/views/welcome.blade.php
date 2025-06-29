<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sanem - Sociedade de Amparo aos Necessitados de Medianeira</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /*--------------------------------------------------------------
        # Configurações Gerais e Paleta de Cores
        --------------------------------------------------------------*/
        :root {
            --cor-primaria: #008080; /* Teal / Verde-azulado */
            --cor-secundaria: #f4f4f4; /* Cinza bem claro */
            --cor-acao: #E67E22; /* Laranja Queimado */
            --cor-texto: #333;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--cor-texto);
            line-height: 1.7;
        }

        h1, h2, h3, h4, h5, h6 {
            font-weight: 600;
            color: var(--cor-primaria);
        }

        .section-title {
            text-align: center;
            padding-bottom: 40px;
        }
        .section-title h2 {
            font-size: 32px;
            font-weight: 700;
            position: relative;
        }
        .section-title h2::after {
            content: '';
            position: absolute;
            display: block;
            width: 50px;
            height: 3px;
            background: var(--cor-acao);
            bottom: -10px;
            left: calc(50% - 25px);
        }

        /*--------------------------------------------------------------
        # Navbar (Menu Superior)
        --------------------------------------------------------------*/
        .navbar {
            transition: all 0.3s ease-in-out;
        }
        .navbar-brand strong {
            font-weight: 700;
        }
        .navbar.scrolled {
            background-color: #fff;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        .btn-login {
            background-color: var(--cor-acao);
            border-color: var(--cor-acao);
            color: #fff;
            font-weight: 600;
            padding: 8px 25px;
            border-radius: 50px;
            transition: 0.3s;
        }
        .btn-login:hover {
            background-color: #d35400;
            border-color: #d35400;
            transform: translateY(-2px);
        }

        /*--------------------------------------------------------------
        # Seção Hero (Principal)
        --------------------------------------------------------------*/
        #hero {
            width: 100%;
            height: 90vh;
            background: linear-gradient(to bottom, rgba(0, 128, 128, 0.8), rgba(0, 128, 128, 0.6)), url('https://images.unsplash.com/photo-1532629345422-7515f3d16bb6?q=80&w=2070&auto=format&fit=crop') center center;
            background-size: cover;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        #hero h1 {
            font-size: 48px;
            font-weight: 700;
            line-height: 56px;
            color: #fff;
        }
        #hero p {
            font-size: 20px;
            font-weight: 300;
            margin: 10px 0 30px 0;
        }
        .btn-hero {
            font-size: 16px;
            font-weight: 600;
            padding: 12px 40px;
            border-radius: 50px;
            background: var(--cor-acao);
            border: 2px solid var(--cor-acao);
            color: #fff;
            transition: 0.3s;
        }
        .btn-hero:hover {
            background: transparent;
            border-color: #fff;
            color: #fff;
        }

        /*--------------------------------------------------------------
        # Seção "Como Funciona"
        --------------------------------------------------------------*/
        .feature-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 25px rgba(0,0,0,0.08);
            padding: 40px 30px;
            text-align: center;
            transition: all 0.3s ease-in-out;
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }
        .feature-icon i {
            font-size: 48px;
            color: var(--cor-acao);
            margin-bottom: 20px;
            display: inline-block;
        }
        .feature-card h3 {
            font-size: 20px;
            font-weight: 700;
        }

        /*--------------------------------------------------------------
        # Seção de Impacto (Números)
        --------------------------------------------------------------*/
        #impacto {
            background-color: var(--cor-secundaria);
        }
        .impacto-box {
            text-align: center;
        }
        .impacto-box .icon {
            font-size: 42px;
            color: var(--cor-primaria);
        }
        .impacto-box .count {
            font-size: 40px;
            font-weight: 700;
            display: block;
            color: var(--cor-acao);
        }
        .impacto-box .description {
            font-size: 15px;
            color: var(--cor-texto);
        }

        /*--------------------------------------------------------------
        # Rodapé
        --------------------------------------------------------------*/
        #footer {
            background: #333;
            color: #fff;
            padding: 40px 0;
            text-align: center;
        }
        #footer h3 {
            color: #fff;
            font-weight: 700;
        }
        #footer .social-links a {
            color: #fff;
            font-size: 24px;
            margin: 0 10px;
            transition: 0.3s;
        }
        #footer .social-links a:hover {
            color: var(--cor-acao);
        }
    </style>
</head>
<body>

    <nav id="navbar" class="navbar navbar-expand-lg fixed-top navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-heart-pulse-fill"></i>
                <strong>SANEM</strong>
            </a>
            <div class="ms-auto">
                <a href="{{ route('login') }}" class="btn btn-login">Login / Entrar</a>
            </div>
        </div>
    </nav>

    <section id="hero">
        <div class="container">
            <h1>Conectando Corações, Transformando Vidas em Medianeira</h1>
            <p>Sua doação de itens se torna o amparo para uma família da nossa comunidade.</p>
            <a href="{{ route('login') }}" class="btn-hero">Faça Parte Desta Missão</a>
        </div>
    </section>

    <main id="main">

        <section id="como-funciona" class="py-5">
            <div class="container">
                <div class="section-title">
                    <h2>Como Você Pode Ajudar</h2>
                    <p>O processo é simples, transparente e gera um impacto real.</p>
                </div>
                <div class="row g-4">
                    <div class="col-md-6 col-lg-3 d-flex align-items-stretch">
                        <div class="feature-card">
                            <div class="feature-icon"><i class="bi bi-person-plus"></i></div>
                            <h3>1. Cadastre-se</h3>
                            <p>Crie sua conta em nosso sistema de forma rápida e segura para se tornar um doador ou voluntário.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 d-flex align-items-stretch">
                        <div class="feature-card">
                            <div class="feature-icon"><i class="bi bi-box2-heart"></i></div>
                            <h3>2. Registre a Doação</h3>
                            <p>Informe os itens que deseja doar. Podem ser roupas, alimentos, móveis, entre outros.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 d-flex align-items-stretch">
                        <div class="feature-card">
                            <div class="feature-icon"><i class="bi bi-truck"></i></div>
                            <h3>3. Agende a Coleta</h3>
                            <p>Nossa equipe entrará em contato para agendar o melhor horário para retirar a doação em sua casa.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 d-flex align-items-stretch">
                        <div class="feature-card">
                            <div class="feature-icon"><i class="bi bi-people-fill"></i></div>
                            <h3>4. Veja o Impacto</h3>
                            <p>Acompanhe pelo sistema como sua generosidade chegou a quem mais precisa em Medianeira.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="impacto" class="py-5">
            <div class="container">
                <div class="section-title">
                    <h2>Nosso Impacto em Números</h2>
                    <p>Cada número representa uma vida tocada pela solidariedade da nossa comunidade.</p>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-6 d-md-flex align-items-md-stretch">
                        <div class="impacto-box w-100">
                            <span class="icon"><i class="bi bi-house-heart"></i></span>
                            <span class="count">120+</span>
                            <p class="description">Famílias Apoiadas</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 d-md-flex align-items-md-stretch">
                        <div class="impacto-box w-100">
                            <span class="icon"><i class="bi bi-gift"></i></span>
                            <span class="count">2.500+</span>
                            <p class="description">Itens Doados e Distribuídos</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 d-md-flex align-items-md-stretch">
                        <div class="impacto-box w-100">
                            <span class="icon"><i class="bi bi-person-check"></i></span>
                            <span class="count">45+</span>
                            <p class="description">Voluntários Ativos</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <footer id="footer">
        <div class="container">
            <h3>Sanem</h3>
            <p>Sociedade de Amparo aos Necessitados de Medianeira</p>
            <div class="social-links">
                <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
            </div>
            <div class="copyright pt-3">
                &copy; Copyright <strong><span>Sanem</span></strong>. Todos os Direitos Reservados
            </div>
        </div>
    </footer>


    <script>
        /**
         * Adiciona uma classe 'scrolled' na navbar quando o usuário rola a página
         */
        document.addEventListener('DOMContentLoaded', () => {
            const navbar = document.querySelector('#navbar');
            if (navbar) {
                const handleScroll = () => {
                    if (window.scrollY > 20) {
                        navbar.classList.add('scrolled', 'navbar-light');
                        navbar.classList.remove('navbar-dark');
                    } else {
                        navbar.classList.remove('scrolled', 'navbar-light');
                        navbar.classList.add('navbar-dark');
                    }
                };
                window.addEventListener('scroll', handleScroll);
                handleScroll(); // Executa uma vez no carregamento
            }
        });
    </script>

</body>
</html>