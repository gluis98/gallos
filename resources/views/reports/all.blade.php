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
    <h2 class="text-center">Ficha de Gallo</h2>
    <table class="table table-border">
        <tbody>
            <tr>
                <td>
                    @if($item->gallos_imagenes != null)
                    <img src="../../files/gallos/{{$item->id}}/{{$item->gallos_imagenes->first()->imagen}}" class="h-100 w-100" alt="..." class="border">
                    @else
                    <img src="../../files/img/avatar.png" class="h-100 w-100" alt="..." class="border">
                    @endif
                </td>
                <td>
                    <h5>Placa del gallo: {{$g->placa}}</h5>
                    <p class="p-4 border" style="font-size: 12px;">
                        <span class="fw-bold">Marca de nacimiento:</span> {{$g->marca_nacimiento}}<br>
                        <span class="fw-bold">Marca de federacion:</span> {{$g->marca_federacion}}<br>
                        <span class="fw-bold">Fecha de nacimiento:</span> {{$g->fecha_nacimiento}}<br>
                        <span class="fw-bold">Color:</span> {{$g->color}}<br>
                        <span class="fw-bold">Color Alternativo:</span> {{$g->color_alternativo}}<br>
                        <span class="fw-bold">Luna:</span> {{$g->luna}}<br>
                        <span class="fw-bold">Cresta:</span> {{$g->cresta}}<br>
                        <span class="fw-bold">N° de Peleas:</span> {{$g->peleas}}<br>
                        <span class="fw-bold">Observaciones:</span><br>
                        {{$g->observaciones}}
                        
                        <h4 class="border p-2"><b>Estatus</b>: {{$g->estatus}}</h4> 
                    </p>
                </td>
            </tr>
            <tr class="p-0">
                <td colspan="2" class="p-0">
                    <h4 class="text-center">PEDIGREE</h4>
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>
                                    @if($g->gallos_hijos[0]->padre != null)
                                    <table class="table">
                                        <tr>
                                            <td>
                                                @if($g->gallos_hijos[0]->padre->gallos_imagenes != null)
                                                <img src="../../files/gallos/{{$g->gallos_hijos[0]->padre->id}}/{{$g->gallos_hijos[0]->padre->gallos_imagenes->first()->imagen}}" width="100%" height="180" alt="..." class="border">
                                                @else
                                                <img src="../../files/img/avatar.png" width="100%" height="180" alt="..." class="border">
                                                @endif
                                                <h6>Placa del padre: {{$g->gallos_hijos[0]->padre->placa}}</h6>
                                                <p class=" p-4 border" style="font-size: 12px">
                                                    <span class="fw-bold">Marca de nacimiento:</span> {{$g->gallos_hijos[0]->padre->marca_nacimiento}}<br>
                
                                                    {{-- <span class="fw-bold">N° de Peleas:</span> {{$g->gallos_hijos[0]->padre->peleas}}<br> --}}
                                                    <span class="fw-bold">Observaciones:</span><br>
                                                    {{$g->gallos_hijos[0]->padre->observaciones}}
                                                    <span class="fw-bold">Estatus</span>: {{$g->gallos_hijos[0]->padre->estatus}}
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                    @endif
                                </td>
                                <td>
                                    @if($g->gallos_hijos[0]->madre != null)
                                    <table class="table">
                                        <tr>
                                            <td>
                                                @if($g->gallos_hijos[0]->madre->gallinas_imagenes != null)
                                                <img src="../../files/gallinas/{{$g->gallos_hijos[0]->madre->id}}/{{$g->gallos_hijos[0]->madre->gallinas_imagenes->first()->imagen}}" width="100%" height="180" alt="..." class="border">
                                                @else
                                                <img src="../../files/img/avatar-2.png" width="100%" height="180" alt="..." class="border">
                                                @endif
                                                <h6>Placa de la madre: {{$g->gallos_hijos[0]->madre->placa}}</h6>
                                                <p class="p-4 border" style="font-size: 12px">
                                                    <span class="fw-bold">Marca de nacimiento:</span> {{$g->gallos_hijos[0]->madre->marca_nacimiento}}<br>
                                                    <span class="fw-bold">Observaciones:</span><br>
                                                    {{$g->gallos_hijos[0]->madre->observaciones}}
                                                    <span class="fw-bold">Estatus</span>: {{$g->gallos_hijos[0]->madre->estatus}}
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    @endforeach
</body>
<script>
    print()
</script>
</html>