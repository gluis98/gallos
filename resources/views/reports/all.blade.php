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
    @foreach ($g as $item)
    <h2>Ficha de Gallo</h2>
    <hr>
    <div class="card w-75" >
        @if(count($item->gallos_imagenes) > 0)
            <img src="../files/gallos/{{$item->id}}/{{$item->gallos_imagenes->first()->imagen}}" class="w-100" alt="...">
        @else
            <img src="../img/avatar.png" class="w-100" alt="...">
        @endif
        <div class="card-header border">
            <h5 class="card-title">Placa del gallo: {{$item->placa}}</h5>
        </div>
        <div class="card-body">
            <p class="card-text fs-5">
                <span class="fw-bold">Marca de nacimiento: {{$item->marca_nacimiento}}</span><br>
                <span class="fw-bold">Marca de federacion: {{$item->marca_federacion}}</span><br>
                <span class="fw-bold">Fecha de nacimiento: {{$item->fecha_nacimiento}}</span><br>
                <span class="fw-bold">Color: {{$item->color}}</span><br>
                <span class="fw-bold">Luna: {{$item->luna}}</span><br>
                <span class="fw-bold">Cresta: {{$item->cresta}}</span>
                <hr>
                <span class="fw-bold fs-5">N° de Peleas: {{$item->peleas}}</span>
                <hr>
            </p>
            <h4><b>Estatus</b>: {{$item->estatus}}</h4>
        </div>
    </div>
    <div style="page-break-after: always"></div>
    @endforeach
</body>
<script>
    print()
</script>
</html>