<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema Ventas Laravel Vue Js- IncanatoIT">
    <meta name="author" content="andrestor.com">
    <meta name="keyword" content="Sistema ventas Laravel Vue Js, Sistema compras Laravel Vue Js">
    <link rel="shortcut icon" href="img/favicon.png">
    <title>Sistema Ventas - IncanatoIT</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/plantilla.css') }}" rel="stylesheet">
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
    <div id="app">
    <header class="app-header navbar">
        <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">
          <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#"></a>
        <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
          <span class="navbar-toggler-icon"></span>
        </button>
        <ul class="nav navbar-nav d-md-down-none">
            <li class="nav-item px-3">
                <a class="nav-link" href="#">Escritorio</a>
            </li>
            <li class="nav-item px-3">
                <a class="nav-link" href="#">Configuraciones</a>
            </li>
        </ul>
        <ul class="nav navbar-nav ml-auto">
            <li class="nav-item d-md-down-none">
                <a class="nav-link" href="#" data-toggle="dropdown">
                    <i class="icon-bell"></i>
                    <span class="badge badge-pill badge-danger">5</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-header text-center">
                        <strong>Notificaciones</strong>
                    </div>
                    <a class="dropdown-item" href="#">
                        <i class="fa fa-envelope-o"></i> Ingresos
                        <span class="badge badge-success">3</span>
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fa fa-tasks"></i> Ventas
                        <span class="badge badge-danger">2</span>
                    </a>
                </div>
            </li>
            <li>
            </li>
            <li class="nav-item d-md-down-none">
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                        {{ __('Cerrar sesion') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                    </form>
                </li>
            
        </ul>
        <ul class="nav-item">
        </ul>
    </header>
    {{-- </div> --}}
    <div class="app-body">
        
        
        @if(Auth::check())
            @if(Auth::user()->rol_id == 1)
                @include('includes.plantilla.sidebarAdministrador')
            @elseif (Auth::user()->rol_id == 2)
                @include('includes.plantilla.sidebarVendedor')
            @elseif (Auth::user()->rol_id == 3)
                @include('includes.plantilla.sidebarBodeguero')
            @endif
        @endif
        {{-- @include('includes.plantilla.sidebar') --}}
        <!-- Contenido Principal -->
        @yield('content')
        <!-- /Fin del contenido principal -->
    </div>   
            
</div>
    <footer class="app-footer">
        <span><a href="#">Ivan Andres Lopez</a> &copy; 2019</span>
        <span class="ml-auto">Desarrollado por <a href="#">Ivan Andres Lopez</a></span>
    </footer>

    <!-- Bootstrap and necessary plugins -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/plantilla.js') }}"></script>
    


   
</body>

</html>