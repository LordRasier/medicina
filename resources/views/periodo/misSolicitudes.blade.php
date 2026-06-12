@extends('layouts.app')

@section('content')
    @component('components.page-header', [
        'title' => 'Mis solicitudes',
        'subtitle' => 'Seguimiento de dispensas cargadas y su estado de autorizacion.',
        'breadcrumbs' => [
            ['label' => 'Dispensas', 'url' => '/misDispensas'],
            ['label' => 'Mis solicitudes'],
        ],
    ])
        <a href="/misDispensas" class="btn btn-outline-secondary">
            <i class="fal fa-arrow-left mr-1"></i>
            Volver
        </a>
        <a href="/misDispensas/solicitud" class="btn btn-primary">
            <i class="fal fa-plus mr-1"></i>
            Nueva solicitud
        </a>
    @endcomponent

    @component('components.table-panel', [
        'title' => 'Solicitudes registradas',
        'description' => 'Cada fila muestra el avance de autorizacion y permite consultar el detalle completo.',
    ])
        <table id="mytable" class="table table-striped table-hover ui-data-table">
            <thead>
            <tr>
                <th>Solicitud</th>
                <th>Fecha</th>
                <th>Autorizacion 2</th>
                <th>Autorizacion 3</th>
                <th class="text-right">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @forelse($solicitudes as $item)
                <tr>
                    <td>
                        <strong>#{{ $item->id }}</strong>
                    </td>
                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @include('components.status-badge', ['status' => $item->autorizacion2])
                    </td>
                    <td>
                        @include('components.status-badge', ['status' => $item->autorizacion3])
                    </td>
                    <td class="text-right">
                        <a class="btn btn-outline-secondary btn-sm" href="/request/detalle/{{ $item->id }}" data-toggle="tooltip" title="Ver detalle">
                            <i class="fa fa-search"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        No hay solicitudes registradas para mostrar.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    @endcomponent
@endsection
