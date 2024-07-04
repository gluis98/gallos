<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Rooster APP</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>
    <body class="">
        <div class="container-fluid vh-100 bg-black bg-gradient">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-12">
                    <div class="card shadow-lg bg-transparent border-dark text-warning my-4    ">
                        <div class="card-header text-center">{{ __('Autenticación') }}</div>
        
                        <div class="card-body">
                            <img src="{{ asset('img/logo.png') }}" alt="" class="w-100">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>