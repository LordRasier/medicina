@extends('layouts.app')

@section('content')
    @component('components.page-header', [
        'title' => 'Autorizaciones nivel 3',
        'subtitle' => 'Solicitudes aprobadas por nivel 2 pendientes de resolucion final.',
        'breadcrumbs' => [
            ['label' => 'Dispensas'],
            ['label' => 'Autorizaciones nivel 3'],
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
        'title' => 'Bandeja de resolucion final',
        'description' => 'Estas solicitudes ya pasaron el primer nivel y esperan definicion final.',
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
                    <td><strong>#{{ $item->id }}</strong></td>
                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    <td>@include('components.status-badge', ['status' => $item->autorizacion2])</td>
                    <td>@include('components.status-badge', ['status' => $item->autorizacion3])</td>
                    <td class="text-right">
                        <a class="btn btn-outline-secondary btn-sm" href="/autorizaciones/3/definir/{{ $item->id }}" data-toggle="tooltip" title="Resolver solicitud">
                            <i class="fa fa-search"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        No hay solicitudes pendientes para resolucion final.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    @endcomponent
@endsection
