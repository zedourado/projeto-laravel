<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'> 

    <!-- Bootstrap 5.3 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" 
          integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

    <!-- CSS da Aplicação -->
    <link rel="stylesheet" href="/css/styles.css">
        <!-- CDN SWEETALERT -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light mx-3" id="navbar">
        <div class="container-fluid">
            <!-- Logo -->
            <a href="/" class="navbar-brand">
                <img src="/img/hdcevents_logo.svg" alt="HDC Eventos" height="40">
            </a>

            <!-- Botão hambúrguer para mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Links -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <!-- Link público -->
                    <li class="nav-item">
                        <a class="nav-link active" href="/">Eventos</a>
                    </li>

                    @auth
                        <!-- Link apenas para admin -->
                        @if(auth()->user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="/events/create">Criar Evento</a>
                            </li>
                        @endif

                        <!-- Meus Eventos -->
                        <li class="nav-item">
                            <a class="nav-link" href="/dashboard">Meus Eventos</a>
                        </li>

                        <!-- NOTIFICAÇÕES -->
                        <li class="nav-item dropdown me-3">
                            <a class="nav-link position-relative" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <ion-icon name="notifications-outline" style="font-size: 1.5rem;"></ion-icon>
                                @if($unreadNotificationsCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notificationCount">
                                        {{ $unreadNotificationsCount }}
                                    </span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end p-2" aria-labelledby="notificationsDropdown" id="notificationsDropdownMenu" style="min-width: 300px; max-height: 400px; overflow-y: auto;">
                                @forelse($notifications as $notification)
                                    <li>
                                        <div class="card p-2 mb-2 {{ $notification->read ? 'bg-light' : 'bg-white' }}" style="width: 100%; border-radius: 10px;">
                                            <strong>{{ $notification->title }}</strong>
                                            <p class="mb-1">{{ $notification->message }}</p>
                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                    </li>
                                @empty
                                    <li>
                                        <p class="text-center mb-0">Sem notificações</p>
                                    </li>
                                @endforelse
                            </ul>
                        </li>

                        <!-- Dropdown usuário -->
                        <li class="nav-item dropdown d-flex align-items-center">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" 
                            data-bs-toggle="dropdown" aria-expanded="false">
                                @if(auth()->user()->profile_image)
                                    <img src="/img/profiles/{{ auth()->user()->profile_image }}" 
                                        alt="Perfil" class="rounded-circle me-2" 
                                        style="width:35px; height:35px; object-fit:cover;">
                                @else
                                    <img src="/img/profiles/avatar.png" 
                                        alt="Perfil" class="rounded-circle me-2" 
                                        style="width:35px; height:35px; object-fit:cover;">
                                @endif
                                <span class="d-none d-sm-inline">{{ auth()->user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="/account/edit">Editar Conta</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="/logout" method="POST">
                                        @csrf
                                        <button class="dropdown-item" type="submit">Sair</button>
                                    </form>
                                </li>
                            </ul>
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
            @yield('content')
        </div>
    </div>

    @if(session('msg'))
    <script>
        const msg = @json(session('msg')); // converte a mensagem de forma segura
        Swal.fire({
            title: 'Tudo Certo!',
            text: msg,
            icon: 'success',
            timer: 2000,
            showConfirmButton: false,
            position: 'top-end'
        });
    </script>
    @endif
</main>


    <footer>
        <p>HDC Eventos &copy; 2025</p>
    </footer>

    <!-- Bootstrap Bundle JS (inclui Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>


    <!-- CDN ION ICONS -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <!-- JS da Aplicação -->
    <script src="/js/scripts.js"></script>
</body>
</html>
