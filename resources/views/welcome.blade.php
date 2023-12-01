<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Maxinero - Home</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #000000;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                        
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="{{ url('/profile') }}" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        
                            
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            @endif

            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-4">
                    <!--MaxiNero-->
                    
                </div>
    
                <div class="links">
                   <!-- <a href="">Documentation</a>-->
                </div>
            </div>
            <div class="jumbotron">
                <div class="container">
                    <img class="img-fluid" src="{{ asset('img/logo.png') }} " height="320" width="840" alt="maxinero, maxscale, load balancer, mysql, mariadb"/>
                </div>
            </div>
            <section class="bg-primary" id="about">
                <div class="container">
                    <div class="row">
                    <div class="col-lg-8 mx-auto text-center">
                        <h2 class="section-heading text-white">We've got what you need!</h2>
                        <hr class="light my-4">
                        <p class="text-faded mb-4">Maxinero utilizes the API already provided by Maxscale to allow easy integration. Just provide Maxinero with your API endpoint and credentials and Maxinero will do the rest. With Maxinero, users can manage database servers, services, listeners, monitors, etc.</p>
                        <a class="btn btn-light btn-xl js-scroll-trigger" href="{{ route('register') }}">Get Started!</a>
                    </div>
                    </div>
                </div>
            </section>
        </div>
        
    </body>
    <!-- Footer -->
    <footer class="page-footer font-small">
        <!-- Copyright -->
        <div class="footer-copyright text-center py-3">© 2022 Copyright:
            <a href="https://www.maxinero.com">maxinero.com</a>
        </div>
        <!-- Copyright -->
        
    </footer>
    <!-- Footer -->
</html>
