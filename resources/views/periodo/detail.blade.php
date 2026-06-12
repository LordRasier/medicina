@extends('layouts.app')

@section('content')
    @php
        $solicitud = $periodo;
    @endphp

    @component('components.page-header', [
        'title' => 'Solicitud #' . $solicitud->id,
        'subtitle' => 'Detalle y estado de autorizacion de tu dispensa.',
        'breadcrumbs' => [
            ['label' => 'Dispensas', 'url' => '/misDispensas'],
            ['label' => 'Mis solicitudes', 'url' => '/misDispensas/solicitudes'],
            ['label' => 'Detalle'],
        ],
    ])
        <a href="/misDispensas/solicitudes" class="btn btn-outline-secondary">
            <i class="fal fa-arrow-left mr-1"></i>
            Volver
        </a>
    @endcomponent

    <div class="row">
        <div class="col-lg-7">
            @component('components.table-panel', [
                'title' => 'Resumen',
                'description' => 'Informacion principal de la solicitud enviada.',
            ])
                <dl class="request-summary">
                    <dt>Fecha</dt>
                    <dd>{{ $solicitud->created_at->format('d/m/Y H:i') }}</dd>

                    <dt>Tipo</dt>
                    <dd>{{ optional($solicitud->licence)->description ?? 'Sin licencia asociada' }}</dd>

                    <dt>Autorizacion 2</dt>
                    <dd>@include('components.status-badge', ['status' => $solicitud->autorizacion2])</dd>

                    <dt>Autorizacion 3</dt>
                    <dd>@include('components.status-badge', ['status' => $solicitud->autorizacion3])</dd>

                    <dt>Adjunto</dt>
                    <dd>
                        @if($solicitud->file != null)
                            <a target="_blank" href="/checkfile/{{ $solicitud->id }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fal fa-paperclip mr-1"></i>
                                Ver adjunto
                            </a>
                        @else
                            <span class="text-muted">Sin adjunto</span>
                        @endif
                    </dd>
                </dl>

                <div class="form-group mb-0">
                    <label class="form-label">Descripcion enviada</label>
                    <textarea class="form-control" rows="4" readonly>{{ $solicitud->description }}</textarea>
                </div>
            @endcomponent

            @component('components.table-panel', [
                'title' => 'Respuestas',
                'description' => 'Observaciones registradas durante el circuito de autorizacion.',
            ])
                <div class="response-stack">
                    <div class="response-card">
                        <div class="response-card-title">
                            @include('components.status-badge', ['status' => $solicitud->autorizacion2])
                            <span>Nivel 2</span>
                        </div>
                        <p>{{ $solicitud->observacion2 ?: 'Sin observacion registrada.' }}</p>
                    </div>
                    <div class="response-card">
                        <div class="response-card-title">
                            @include('components.status-badge', ['status' => $solicitud->autorizacion3])
                            <span>Nivel 3</span>
                        </div>
                        <p>{{ $solicitud->observacion3 ?: 'Sin observacion registrada.' }}</p>
                    </div>
                </div>
            @endcomponent
        </div>

        <div class="col-lg-5">
            @component('components.table-panel', [
                'title' => 'Dias solicitados',
                'description' => 'Fechas incluidas en esta solicitud.',
            ])
                <div class="date-chip-list">
                    @forelse($solicitud->days as $item)
                        <span class="date-chip">
                            <i class="fal fa-calendar mr-1"></i>
                            {{ $item->date }}
                        </span>
                    @empty
                        <span class="text-muted">No hay dias cargados.</span>
                    @endforelse
                </div>
            @endcomponent
        </div>
    </div>
@endsection
