<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Catálogo de gallinas</title>
    @include('reports.pdf.partials.styles', ['accent' => '#7c3aed'])
</head>
<body>
@php $logo = app(\App\Services\ReportPdfService::class)->logo(); @endphp

<div class="page">
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="logo-cell">@if($logo)<img src="{{ $logo }}" alt="Galpon">@endif</td>
                <td style="padding-left:8px;">
                    <p class="header-title">{{ $criadero }}</p>
                    <p class="header-sub">Catálogo completo de gallinas</p>
                </td>
                <td class="header-meta">Galpon<br>{{ $generated_at }}</td>
            </tr>
        </table>
    </div>
    <div class="cover">
        <h1>Plantel de gallinas</h1>
        <p>Reporte oficial con ficha y pedigree de cada ejemplar.</p>
        <div class="cover-stat">{{ $total }} {{ $total === 1 ? 'gallina' : 'gallinas' }}</div>
    </div>
</div>

@foreach($items as $item)
    <div class="{{ !$loop->last ? 'page-break' : '' }}">
        @include('reports.pdf.partials.ficha-body', array_merge($item, ['docTitle' => 'Ficha de gallina']))
    </div>
@endforeach

</body>
</html>
