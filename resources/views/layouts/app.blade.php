<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Rooster APP</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css2?family=Material+Icons"
        rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined"
        rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    </head>
    <body class="bg-dark">
        <header>
            <nav class="navbar navbar-expand-lg bg-black navbar-dark ">
                <div class="container-fluid">
                    <a class="navbar-brand mx-auto" href="#">
                    <img src="{{ asset('img/logo.png') }}" alt="" width="130" height="130">
                    </a>
                </div>
            </nav>
            <div class="container-fluid d-none d-sm-none d-md-block d-lg-block d-xs-block">
                <ul class="nav border-bottom bg-black">
                    <li class="nav-item">
                      <a class="nav-link text-white active" aria-current="page" href="#">Gallos</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link text-white" href="#">Gallinas</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link text-white" href="#">Ventas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">
                            <span class="material-symbols-outlined float-end">
                                settings
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </header>
        
        <main class="p-2 my-4">
            @yield('content')
        </main>

        <footer class="container-fluid fixed-bottom bg-dark p-3 justify-content-start align-content-center border-top bg-black d-block d-sm-block d-md-none d-lg-none d-xs-none text-center">
            <a href="#" class="btn text-white text-decoration-none">Gallos</a>
            <a href="#" class="btn text-white text-decoration-none">Gallinas</a>
            <a href="#" class="btn text-white text-decoration-none">Ventas</a>
            <a href="#" class="btn text-white text-decoration-none">
                <span class="material-symbols-outlined float-end">
                    settings
                </span></a>
        </footer>
        
    </body>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('scripts')
</html>