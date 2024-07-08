<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css2?family=Material+Icons"
        rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined"
        rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <h2>Ficha de Gallina</h2>
    <hr>
    <div class="card" style="float: left;">
        
        <img src="../../files/gallinas/{{$g->id}}/{{$g->gallinas_imagenes->first()->imagen}}" class="w-100" alt="...">
        <div class="card-header border">
            <h5 class="card-title">Placa del gallo: {{$g->placa}}</h5>
        </div>
        <div class="card-body">
            <p class="card-text fs-5">
                <span class="fw-bold">Marca de nacimiento: {{$g->marca_nacimiento}}</span><br>
                <span class="fw-bold">Marca de federacion: {{$g->marca_federacion}}</span><br>
                <span class="fw-bold">Fecha de nacimiento: {{$g->fecha_nacimiento}}</span><br>
                <span class="fw-bold">Color: {{$g->color}}</span><br>
                <span class="fw-bold">Luna: {{$g->luna}}</span><br>
                <span class="fw-bold">Cresta: {{$g->cresta}}</span>
                <hr>
                <span class="fw-bold fs-5">Observaciones: {{$g->observaciones}}</span>
                <hr>
            </p>
            <h4><b>Estatus</b>: {{$g->estatus}}</h4>
        </div>
    </div>
</body>
<script>
    print();
</script>
</html>