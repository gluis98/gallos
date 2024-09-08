<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Rooster APP</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>
    <body>
        <div class="container-fluid vh-100 bg-black bg-gradient">
            <div class="row justify-content-center align-items-center">
                <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xs-4 mx-auto">
                    <div class="card shadow-lg bg-transparent border-dark text-warning my-4    ">
                        <div class="card-header text-center">{{ __('Autenticación') }}</div>
        
                        <div class="card-body p-0">
                            <div class="container-fluid text-center p-0">
                                <img src="{{ asset('img/logo.jpeg') }}" alt="" class="w-100">
                            </div>
                            <div class="container-fluid p-3">
                                @yield('content')

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>