<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Maxinero — MaxScale UI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; }
        .hero {
            background: linear-gradient(135deg, #1a2a3a 0%, #1c6e7e 100%);
            min-height: 70vh;
            display: flex;
            align-items: center;
        }
        .feature-icon {
            width: 56px; height: 56px;
            background: rgba(32, 201, 151, 0.15);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-md navbar-dark" style="background:#1a2a3a;">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                <div style="width:36px;height:36px;overflow:hidden;flex-shrink:0;">
                    <img src="{{ asset('img/logo.png') }}" height="36" style="max-width:none;width:auto;" alt="Maxinero"/>
                </div>
                <span class="fw-semibold">Maxinero</span>
            </a>
            <div class="ms-auto d-flex gap-2">
                @auth
                    <a href="{{ url('/home') }}" class="btn btn-outline-light btn-sm">Dashboard</a>
                    <a href="{{ route('logout') }}" class="btn btn-secondary btn-sm"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-sm" style="background:#20c997;color:#fff;border:none;">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <section class="hero text-white">
        <div class="container text-center py-5">
            <div style="width:110px;height:110px;overflow:hidden;margin:0 auto 1.5rem;" >
                <img src="{{ asset('img/logo.png') }}" height="110" style="max-width:none;width:auto;" alt="Maxinero"/>
            </div>
            <h1 class="display-5 fw-bold mb-3">MaxScale Management, Simplified</h1>
            <p class="lead text-white-50 mb-4 mx-auto" style="max-width:620px;">
                Maxinero connects to the MaxScale REST API to give you a clean web UI for managing DB servers, services, listeners, monitors, and users — across <strong class="text-white">multiple MaxScale instances</strong>, all from one dashboard.
            </p>
            @auth
                <a href="{{ url('/home') }}" class="btn btn-lg px-5 fw-semibold" style="background:#20c997;color:#fff;border:none;">Go to Dashboard</a>
            @else
                <a href="{{ route('register') }}" class="btn btn-lg px-5 fw-semibold me-2" style="background:#20c997;color:#fff;border:none;">Get Started</a>
                <a href="{{ route('login') }}" class="btn btn-lg btn-outline-light px-5">Login</a>
            @endauth
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-sm-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm text-center p-4">
                        <div class="feature-icon mx-auto">&#128266;</div>
                        <h5 class="fw-semibold">Servers</h5>
                        <p class="text-muted small mb-0">Add, edit, and remove backend DB servers. Control server state (master, slave, maintenance, drain) with one click.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm text-center p-4">
                        <div class="feature-icon mx-auto">&#9881;</div>
                        <h5 class="fw-semibold">Services & Monitors</h5>
                        <p class="text-muted small mb-0">Manage routing services and monitors. Start, stop, create, and update — with live connection stats and listener management.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm text-center p-4">
                        <div class="feature-icon mx-auto">&#128202;</div>
                        <h5 class="fw-semibold">Dashboard</h5>
                        <p class="text-muted small mb-0">See active sessions, worker threads, and connection charts updated every minute from your MaxScale instance.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm text-center p-4">
                        <div class="feature-icon mx-auto">&#128275;</div>
                        <h5 class="fw-semibold">Users</h5>
                        <p class="text-muted small mb-0">Manage MaxScale inet and unix users directly from the UI. Add and remove accounts without touching the command line.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm text-center p-4">
                        <div class="feature-icon mx-auto">&#127760;</div>
                        <h5 class="fw-semibold">Multi-Instance</h5>
                        <p class="text-muted small mb-0">Add as many MaxScale endpoints as you need and switch between them instantly from the top navigation — no re-login required.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-3 text-center text-muted small" style="border-top:1px solid #dee2e6;">
        &copy; {{ date('Y') }} <a href="https://www.maxinero.com" class="text-muted">maxinero.com</a>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
