@extends('layouts.app')

@section('content')
    @php
        $primaryAction = $dashboard['primaryAction'] ?? null;
    @endphp

    <div class="dashboard-hero mb-3">
        <div class="dashboard-hero-copy">
            <span>Panel operativo</span>
            <h1>{{ Auth::user()->name }}</h1>
            <p>Resumen diario de solicitudes, honorarios, reservas y acciones disponibles para tu perfil.</p>
        </div>
        <div class="dashboard-hero-side">
            <span class="dashboard-date">
                <i class="fa fa-calendar-alt"></i>
                {{ now()->format('d/m/Y') }}
            </span>
            @if($primaryAction)
                <a href="{{ $primaryAction['url'] }}" class="btn btn-primary">
                    <i class="{{ $primaryAction['icon'] }} mr-1"></i>
                    {{ $primaryAction['label'] }}
                </a>
            @endif
        </div>
    </div>

    <div class="dashboard-attention mb-3">
        <div class="dashboard-block-title">
            <div>
                <span>Prioridad</span>
                <h2>Necesitan atencion</h2>
            </div>
            <i class="fa fa-bell"></i>
        </div>

        <div class="dashboard-attention-grid">
            @forelse($dashboard['attentionItems'] as $item)
                <a href="{{ $item['url'] }}" class="dashboard-attention-item is-{{ $item['tone'] }}">
                    <span class="dashboard-attention-icon">
                        <i class="{{ $item['icon'] }}"></i>
                    </span>
                    <span>
                        <strong>{{ $item['value'] }}</strong>
                        <small>{{ $item['label'] }}</small>
                    </span>
                </a>
            @empty
                <div class="dashboard-empty-state">
                    <i class="fa fa-check-square"></i>
                    <span>No hay acciones urgentes para tu perfil.</span>
                </div>
            @endforelse
        </div>
    </div>

    <div class="dashboard-metrics mb-3">
        @foreach($dashboard['metrics'] as $metric)
            <div class="dashboard-metric">
                <div class="dashboard-metric-icon">
                    <i class="{{ $metric['icon'] }}"></i>
                </div>
                <div>
                    <span>{{ $metric['label'] }}</span>
                    <strong>{{ $metric['value'] }}</strong>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-xl-4">
            @component('components.table-panel', [
                'title' => 'Accesos rapidos',
                'description' => 'Atajos principales para operar sin vueltas.',
            ])
                <div class="quick-action-list">
                    @forelse($dashboard['quickActions'] as $action)
                        <a href="{{ $action['url'] }}" class="quick-action">
                            <i class="{{ $action['icon'] }}"></i>
                            <span>{{ $action['label'] }}</span>
                        </a>
                    @empty
                        <span class="text-muted">No hay accesos rapidos disponibles.</span>
                    @endforelse
                </div>
            @endcomponent
        </div>

        <div class="col-xl-8">
            <div class="dashboard-section-grid">
                @forelse($dashboard['sections'] as $section)
                    @component('components.table-panel', [
                        'title' => $section['title'],
                        'description' => $section['description'],
                    ])
                        <div class="dashboard-list">
                            @forelse($section['items'] as $item)
                                <div class="dashboard-list-item">
                                    <div>
                                        <strong>{{ $item['label'] }}</strong>
                                        <span>{{ $item['meta'] }}</span>
                                    </div>
                                    <span class="dashboard-list-status">{{ $item['status'] }}</span>
                                </div>
                            @empty
                                <div class="text-muted">No hay datos recientes para mostrar.</div>
                            @endforelse
                        </div>

                        <div class="text-right mt-3">
                            <a href="{{ $section['url'] }}" class="btn btn-outline-secondary btn-sm">
                                Ver modulo
                                <i class="fa fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    @endcomponent
                @empty
                    @component('components.table-panel', [
                        'title' => 'Sin modulos activos',
                        'description' => 'Tu usuario no tiene permisos funcionales asignados todavia.',
                    ])
                        <span class="text-muted">Solicita accesos para comenzar a operar la plataforma.</span>
                    @endcomponent
                @endforelse
            </div>
        </div>
    </div>
@endsection
