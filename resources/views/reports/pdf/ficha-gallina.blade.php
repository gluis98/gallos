<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Ficha gallina {{ $placa }}</title>
    @include('reports.pdf.partials.styles', ['accent' => '#7c3aed'])
</head>
<body>
@php
    $docTitle = 'Ficha de gallina';
@endphp
@include('reports.pdf.partials.ficha-body')
</body>
</html>
