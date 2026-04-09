<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Maxinero utilizes the API already provided by Maxscale to allow easy integration.">
    <meta name="keywords" content="maxscale, mariadb, mysql, galera, clustering, proxy, database">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Maxinero') }}</title>

    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    @vite(['resources/js/app.js', 'resources/sass/app.scss'])

    <style>
        .numberCircle {
            border-radius: 50%;
            width: 166px;
            height: 166px;
            padding: 50px;
            background: #01FCA1;
            border: 2px solid #666;
            color: #666;
            text-align: center;
            font: 48px Arial, sans-serif;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark navbar-maxinero">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                    <div style="width:36px;height:36px;overflow:hidden;flex-shrink:0;">
                        <img src="{{ asset('img/logo.png') }}" height="36" style="max-width:none;width:auto;" alt="Maxinero"/>
                    </div>
                    <span class="fw-semibold">Maxinero</span>
                </a>
                <button class="navbar-toggler" type="button"
                        data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('maxscale.index') }}">MaxScale</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('services.index') }}">Services | Monitors</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('servers.index') }}">Servers</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">Users</a></li>
                        @endauth
                    </ul>

                    <ul class="navbar-nav ms-auto">
                        @guest
                            <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>
                        @else
                            @if (!empty($navApiSettings) && $navApiSettings->count() > 0)
                            <li class="nav-item dropdown me-2">
                                <a class="nav-link dropdown-toggle" href="#" role="button"
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="me-1" viewBox="0 0 16 16"><path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2zm2 0v12h8V2z"/><path d="M5 4h6v1H5zm0 2h6v1H5zm0 2h4v1H5z"/></svg>
                                    {{ $navApiSettings->firstWhere('selected', true)->name ?? 'No API selected' }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    @foreach ($navApiSettings as $api)
                                    <li>
                                        <button type="button"
                                                class="dropdown-item d-flex align-items-center gap-2 nav-api-select {{ $api->selected ? 'fw-semibold' : '' }}"
                                                data-id="{{ $api->id }}"
                                                data-url="{{ route('settings.select', $api->id) }}">
                                            @if ($api->selected)
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="#20c997" viewBox="0 0 16 16"><path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/></svg>
                                            @else
                                                <span style="width:12px;"></span>
                                            @endif
                                            {{ $api->name }}
                                        </button>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                            @endif
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{ route('profile.index') }}">Profile</a></li>
                                    <li><a class="dropdown-item" href="{{ route('settings.index') }}">Config</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>

        <footer class="app-footer mt-4">
            &copy; {{ date('Y') }} <a href="https://www.maxinero.com" class="text-muted">maxinero.com</a>
        </footer>
    </div>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"/>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        window.setTimeout(function () {
            $(".alert").fadeTo(500, 0).slideUp(500, function () { $(this).remove(); });
        }, 4000);
    </script>

    <script src="{{ asset('js/ajax.js') }}" defer></script>

    <div class="modal fade" id="frameModalBottom" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row d-flex justify-content-center align-items-center">
                        <p class="pt-3 pe-2" id="errormodal"></p>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="page-footer font-small mt-4">
        <div class="footer-copyright text-center py-3">
            &copy; {{ date('Y') }} <a href="https://www.maxinero.com">Maxinero.com</a>
        </div>
    </footer>
</body>
</html>