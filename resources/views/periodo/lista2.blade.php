@extends('layouts.app')

@section('content')
    @component('components.page-header', [
        'title' => 'Autorizaciones nivel 2',
        'subtitle' => 'Solicitudes pendientes de primera revision administrativa.',
        'breadcrumbs' => [
            ['label' => 'Dispensas'],
            ['label' => 'Autorizaciones nivel 2'],
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
        'title' => 'Bandeja de solicitudes',
        'description' => 'Revisa cada solicitud para aprobarla o rechazarla antes de enviarla al siguiente nivel.',
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
                        <a class="btn btn-outline-secondary btn-sm" href="/autorizaciones/2/definir/{{ $item->id }}" data-toggle="tooltip" title="Resolver solicitud">
                            <i class="fa fa-search"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        No hay solicitudes pendientes para este nivel.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    @endcomponent
@endsection
