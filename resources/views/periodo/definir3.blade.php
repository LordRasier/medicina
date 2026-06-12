@extends('layouts.app')

@section('content')
    @component('components.page-header', [
        'title' => 'Resolver solicitud #' . $solicitud->id,
        'subtitle' => 'Autorizacion nivel 3',
        'breadcrumbs' => [
            ['label' => 'Dispensas'],
            ['label' => 'Autorizaciones nivel 3', 'url' => '/autorizaciones/3'],
            ['label' => 'Resolver'],
        ],
    ])
        <a href="/autorizaciones/3" class="btn btn-outline-secondary">
            <i class="fal fa-arrow-left mr-1"></i>
            Volver
        </a>
    @endcomponent

    <div class="row">
        <div class="col-lg-7">
            @component('components.table-panel', [
                'title' => 'Detalle de la solicitud',
                'description' => 'Informacion cargada por el profesional solicitante.',
            ])
                <dl class="request-summary">
                    <dt>Solicitante</dt>
                    <dd>{{ optional($solicitud->user)->name ?? 'Usuario no disponible' }}</dd>

                    <dt>Fecha de solicitud</dt>
                    <dd>{{ $solicitud->created_at->format('d/m/Y H:i') }}</dd>

                    <dt>Tipo de dispensa</dt>
                    <dd>{{ optional($solicitud->licence)->description ?? 'Sin licencia asociada' }}</dd>

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
                    <label class="form-label">Descripcion</label>
                    <textarea class="form-control" rows="5" readonly>{{ $solicitud->description }}</textarea>
                </div>
            @endcomponent
        </div>

        <div class="col-lg-5">
            @component('components.table-panel', [
                'title' => 'Dias solicitados',
                'description' => 'Fechas incluidas en esta dispensa.',
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

            @component('components.table-panel', [
                'title' => 'Resolucion final',
                'description' => 'Esta decision notifica el resultado al profesional.',
            ])
                <form action="/autorizaciones/3/definir/{{ $solicitud->id }}/update" method="POST">
                    @csrf
                    @method("PUT")
                    <input type="hidden" name="id" value="{{ $solicitud->id }}">

                    <div class="form-group">
                        <label for="respuesta" class="form-label">Observacion</label>
                        <textarea id="respuesta" class="form-control" name="respuesta" rows="4" required>{{ old('respuesta') }}</textarea>
                    </div>

                    <div class="resolution-options mb-3">
                        <label class="resolution-option is-approve">
                            <input type="radio" name="estado" value="2" required>
                            <span><i class="far fa-check mr-1"></i> Autorizar</span>
                        </label>
                        <label class="resolution-option is-reject">
                            <input type="radio" name="estado" value="0" required>
                            <span><i class="far fa-times mr-1"></i> Rechazar</span>
                        </label>
                    </div>

                    <button class="btn btn-primary btn-block" type="submit">
                        <i class="fal fa-paper-plane mr-1"></i>
                        Enviar resolucion
                    </button>
                </form>
            @endcomponent
        </div>
    </div>
@endsection
