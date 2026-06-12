@extends('layouts.app')

@section('content')
    @php
        $totalDisponible = $disponibles + $especiales - $usados;
    @endphp

    @component('components.page-header', [
        'title' => 'Mis dispensas',
        'subtitle' => 'Periodo ' . $periodo->start . ' al ' . $periodo->end,
        'breadcrumbs' => [
            ['label' => 'Dispensas'],
            ['label' => 'Mi periodo'],
        ],
    ])
        <a href="/misDispensas/solicitudes" class="btn btn-outline-secondary">
            <i class="fal fa-list mr-1"></i>
            Mis solicitudes
        </a>
        <a href="/misDispensas/solicitud" class="btn btn-primary">
            <i class="fal fa-plus mr-1"></i>
            Nueva solicitud
        </a>
    @endcomponent

    <div class="period-nav mb-3">
        @if($prevno)
            <a href="/misDispensas/Prev" class="btn btn-outline-secondary" data-toggle="tooltip" title="Periodo anterior">
                <i class="far fa-chevron-left"></i>
            </a>
        @else
            <button class="btn btn-outline-secondary" disabled>
                <i class="far fa-chevron-left"></i>
            </button>
        @endif

        <a href="/misDispensas" class="btn btn-outline-secondary" data-toggle="tooltip" title="Periodo actual">
            <i class="far fa-calendar mr-1"></i>
            Actual
        </a>

        @if($nextno)
            <a href="/misDispensas/Next" class="btn btn-outline-secondary" data-toggle="tooltip" title="Periodo siguiente">
                <i class="far fa-chevron-right"></i>
            </a>
        @else
            <button class="btn btn-outline-secondary" disabled>
                <i class="far fa-chevron-right"></i>
            </button>
        @endif
    </div>

    <div class="metric-strip mb-3">
        <div class="metric-item">
            <span>Base</span>
            <strong>{{ $disponibles }}</strong>
        </div>
        <div class="metric-item">
            <span>Extras</span>
            <strong>{{ $especiales }}</strong>
        </div>
        <div class="metric-item">
            <span>Usadas</span>
            <strong>{{ $usados }}</strong>
        </div>
        <div class="metric-item">
            <span>Disponibles</span>
            <strong>{{ $totalDisponible }}</strong>
        </div>
    </div>

    @component('components.table-panel', [
        'title' => 'Calendario del periodo',
        'description' => 'Los dias usados o bloqueados no cuentan como disponibles para nuevas solicitudes.',
        'contentClass' => 'no-padding',
    ])
        @include('components.period-calendar', [
            'almanaque' => $almanaque,
            'domingos' => $domingos,
            'marcar' => $marcar,
        ])
    @endcomponent
@endsection
