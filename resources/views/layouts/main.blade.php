<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>

        <!-- google fonts -->
        <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>

        <!-- Bootstrap 5.3 CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

        <!-- CSS Aplicação -->
        <link rel="stylesheet" href="/css/styles.css"> 

        <!-- JS Aplicação -->
        <script src="/js/scripts.js"></script>



    </head>
    <body>
        <header>
            <nav class="navbar navbar-expand-lg" id="navbar">
                <div class="container-fluid">
                    <a href="/" class="navbar-brand">
                        <img src="/img/hdcevents_logo.svg" alt="HDC Eventos">
                    </a>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="/">Eventos</a>
                            </li>

                            @auth
                                @if(auth()->user()->role === 'admin')
                                    <li class="nav-item">
                                        <a class="nav-link" href="/events/create">Criar Evento</a>
                                    </li>
                                @endif

                                <li class="nav-item">
                                    <a class="nav-link" href="/dashboard">Meus Eventos</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="/account">Minha Conta</a>
                                </li>

                                <li class="nav-item">
                                    <form action="/logout" method="POST">
                                        @csrf
                                        <a class="nav-link" href="/logout" onclick="event.preventDefault(); this.closest('form').submit();">Sair</a>
                                    </form>
                                </li>
                            @endauth

                            @guest
                                <li class="nav-item">
                                    <a class="nav-link" href="/login">Entrar</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/register">Cadastrar</a>
                                </li>
                            @endguest

                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        
        <main>
            <div class="container-fluid">
                <div class="row">
                    @if(session('msg'))
                        <p class="msg">{{session('msg')}}</p>
                    @endif

                    @yield('content')
                </div>
            </div>
        </main>

        <footer>
            <p>HDC Eventos &copy; 2025</p>
        </footer>
        <!-- CDN ION ICONS -->
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    </body>
</html>
