<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="{{ asset('bootstrap4/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('inicio/css/style.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('fontawesome/css/fontawesome.min.css') }}">
    {{-- <link href="https://tresplazas.com/web/img/big_punto_de_venta.png" rel="shortcut icon"> --}}
    <title>Inicio de sesión</title>
</head>

<body>
    <img class="wave" src="{{ asset('inicio/img/wave.png') }}">
    <div class="container">
        <div class="img">
            <img src="{{ asset('inicio/img/bg.svg') }}">
        </div>
        <div class="login-content">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <img src="{{ asset('inicio/img/avatar.svg') }}">
                <h2 class="title">BIENVENIDO</h2>
                @if (session('mensaje'))
                    <div class="alert alert-warning alert-dismissible fade show mb-0" role="alert">
                        <small>{{ session('mensaje') }}</small>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="mb-3">

                    @error('usuario')
                        <div class="alert alert-danger alert-dismissible fade show mb-1" role="alert">
                            <small>{{ $errors->first('usuario') }}</small>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @enderror


                    @error('password')
                        <div class="alert alert-danger alert-dismissible fade show mb-2" role="alert">
                            <small>{{ $errors->first('password') }}</small>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @enderror

                </div>
                <div class="input-div one">
                    <div class="i">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="div">
                        <h5>Usuario</h5>
                        <input id="usuario" type="text"
                            class="input @error('usuario') is-invalid
                        @enderror" name="usuario"
                            title="ingrese su nombre de usuario" autocomplete="usuario" value="{{ old('usuario') }}">


                    </div>
                </div>
                <div class="input-div pass">
                    <div class="i">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="div">
                        <h5>Contraseña</h5>
                        <input type="password" id="input" class="input @error('password') is-invalid @enderror"
                            name="password" title="ingrese su clave para ingresar" autocomplete="current-password">


                    </div>
                </div>
                <div class="view">
                    <div class="fas fa-eye verPassword" onclick="vista()" id="verPassword"></div>
                </div>


                <div class="text-center">
                    <a class="font-italic isai5" href="">Olvidé mi contraseña</a>
                </div>
                <input name="btningresar" class="btn" title="click para ingresar" type="submit"
                    value="INICIAR SESION">
                {{-- login --}}
            </form>
        </div>
    </div>
    <script type="text/javascript" src="{{ asset('inicio/js/main.js') }}"></script>
    <script type="text/javascript" src="{{ asset('inicio/js/main2.js') }}"></script>
    <script type="text/javascript" src="{{ asset('bootstrap4/js/jquery.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('bootstrap4/js/bootstrap.js') }}"></script>
    <script type="text/javascript" src="{{ asset('bootstrap4/js/bootstrap.bundle.js') }}"></script>

</body>

</html>
