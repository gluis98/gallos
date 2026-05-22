<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Ficha gallo {{ $placa }}</title>
    @include('reports.pdf.partials.styles', ['accent' => '#3b82f6'])
</head>
<body>
@php
    $docTitle = 'Ficha de gallo';
@endphp
@include('reports.pdf.partials.ficha-body')
</body>
</html>
