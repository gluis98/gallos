@php
    $logo = app(\App\Services\ReportPdfService::class)->logo();
@endphp
<div class="page">
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    @if($logo)<img src="{{ $logo }}" alt="Galpon">@endif
                </td>
                <td style="padding-left:8px;">
                    <p class="header-title">{{ $criadero }}</p>
                    <p class="header-sub">{{ $docTitle }} · Certificado de ficha</p>
                </td>
                <td class="header-meta">
                    Galpon<br>
                    Generado: {{ $generated_at }}
                </td>
            </tr>
        </table>
    </div>

    <div class="body-pad">
        <table class="hero-table">
            <tr>
                <td style="width:64mm;">
                    <div class="photo-box">
                        @if($foto)
                            <img src="{{ $foto }}" alt="Foto">
                        @endif
                    </div>
                </td>
                <td class="hero-info">
                    <div class="badge-placa">{{ $placa }}</div>
                    <p class="hero-name">{{ $nombre ?: 'Sin nombre registrado' }}</p>
                    @if($estatus)
                        <span class="badge-status">{{ $estatus }}</span>
                    @endif
                </td>
            </tr>
        </table>

        <p class="section-title">Información general</p>
        <table class="data-table">
            @foreach($fields as $field)
            <tr>
                <td class="label">{{ $field['label'] }}</td>
                <td>{{ $field['value'] }}</td>
            </tr>
            @endforeach
        </table>

        @if(!empty($observaciones))
        <p class="section-title">Observaciones</p>
        <div class="notes-box">{{ $observaciones }}</div>
        @endif

        <p class="section-title">Pedigree — Linaje</p>
        <table class="pedigree-table">
            <tr>
                <td>
                    @if($padre)
                        <div class="parent-card">
                            <div class="parent-head">Padre ({{ $padre['tipo'] }})</div>
                            @if($padre['foto'])
                            <div class="parent-photo"><img src="{{ $padre['foto'] }}" alt="Padre"></div>
                            @endif
                            <div class="parent-body">
                                <div class="parent-placa">{{ $padre['placa'] }}</div>
                                @if($padre['nombre'])<div class="parent-row"><strong>Nombre:</strong> {{ $padre['nombre'] }}</div>@endif
                                @if($padre['marca'])<div class="parent-row"><strong>Marca:</strong> {{ $padre['marca'] }}</div>@endif
                                @if($padre['color'])<div class="parent-row"><strong>Color:</strong> {{ $padre['color'] }}</div>@endif
                                @if(!empty($padre['peleas']))<div class="parent-row"><strong>Peleas:</strong> {{ $padre['peleas'] }}</div>@endif
                                @if($padre['estatus'])<div class="parent-row"><strong>Estatus:</strong> {{ $padre['estatus'] }}</div>@endif
                                @if($padre['observaciones'])<div class="parent-row"><strong>Notas:</strong> {{ $padre['observaciones'] }}</div>@endif
                            </div>
                        </div>
                    @else
                        <div class="empty-pedigree">Padre no registrado</div>
                    @endif
                </td>
                <td>
                    @if($madre)
                        <div class="parent-card">
                            <div class="parent-head">Madre ({{ $madre['tipo'] }})</div>
                            @if($madre['foto'])
                            <div class="parent-photo"><img src="{{ $madre['foto'] }}" alt="Madre"></div>
                            @endif
                            <div class="parent-body">
                                <div class="parent-placa">{{ $madre['placa'] }}</div>
                                @if($madre['nombre'])<div class="parent-row"><strong>Nombre:</strong> {{ $madre['nombre'] }}</div>@endif
                                @if($madre['marca'])<div class="parent-row"><strong>Marca:</strong> {{ $madre['marca'] }}</div>@endif
                                @if($madre['color'])<div class="parent-row"><strong>Color:</strong> {{ $madre['color'] }}</div>@endif
                                @if($madre['estatus'])<div class="parent-row"><strong>Estatus:</strong> {{ $madre['estatus'] }}</div>@endif
                                @if($madre['observaciones'])<div class="parent-row"><strong>Notas:</strong> {{ $madre['observaciones'] }}</div>@endif
                            </div>
                        </div>
                    @else
                        <div class="empty-pedigree">Madre no registrada</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <table class="footer-table">
            <tr>
                <td>Documento generado por Galpon · {{ $criadero }}</td>
                <td style="text-align:right;">{{ $generated_at }}</td>
            </tr>
        </table>
    </div>
</div>
