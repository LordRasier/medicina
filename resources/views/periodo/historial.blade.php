@extends('layouts.app')

@section('content')
    @component('components.page-header', [
        'title' => 'Historial de dispensas',
        'subtitle' => 'Solicitudes ya resueltas por el circuito de autorizacion.',
        'breadcrumbs' => [
            ['label' => 'Dispensas'],
            ['label' => 'Historial'],
        ],
    ])
    @endcomponent

    @isset($resp)
        <div class="alert @if($resp) alert-success @else alert-danger @endif" role="alert">
            @if($resp)
                Autorizacion enviada correctamente.
            @else
                Notificacion de rechazo enviada.
            @endif
        </div>
    @endisset

    @component('components.table-panel', [
        'title' => 'Solicitudes resueltas',
        'description' => 'Consulta solicitudes aprobadas o rechazadas y revisa el detalle de cada resolucion.',
    ])
        <table id="mytable" class="table table-striped table-hover ui-data-table">
            <thead>
            <tr>
                <th>Solicitud</th>
                <th>Profesional</th>
                <th>Tipo</th>
                <th>Fecha</th>
                <th>Autorizacion 2</th>
                <th>Autorizacion 3</th>
                <th class="text-right">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @forelse($solicitudes as $item)
                <tr>
                    <td><strong>#{{ $item->id }}</strong></td>
                    <td>{{ optional($item->user)->name ?? 'Usuario no disponible' }}</td>
                    <td>{{ optional($item->licence)->description ?? 'Sin tipo' }}</td>
                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    <td>@include('components.status-badge', ['status' => $item->autorizacion2])</td>
                    <td>@include('components.status-badge', ['status' => $item->autorizacion3])</td>
                    <td class="text-right">
                        <a class="btn btn-outline-secondary btn-sm" href="/historial/detail/{{ $item->id }}" data-toggle="tooltip" title="Ver detalle">
                            <i class="fa fa-search"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        No hay solicitudes resueltas para mostrar.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    @endcomponent
@endsection
