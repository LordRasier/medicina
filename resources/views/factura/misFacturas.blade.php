@extends('layouts.app')

@section('content')
    @component('components.page-header', [
        'title' => 'Mis facturas',
        'subtitle' => 'Facturas asociadas a tus honorarios cargados.',
        'breadcrumbs' => [
            ['label' => 'Honorarios', 'url' => '/misHonorarios'],
            ['label' => 'Mis facturas'],
        ],
    ])
        <a href="/misHonorarios" class="btn btn-outline-secondary">
            <i class="fal fa-arrow-left mr-1"></i>
            Volver a honorarios
        </a>
    @endcomponent

    @isset($carga)
        <div class="alert alert-success" role="alert">
            Nueva factura cargada correctamente.
        </div>
    @endisset

    @component('components.table-panel', [
        'title' => 'Facturas cargadas',
        'description' => 'Desde esta pantalla puedes ver o eliminar facturas ya asociadas a honorarios.',
    ])
        <table id="mytable" class="table table-striped table-hover ui-data-table">
            <thead>
            <tr>
                <th>Factura</th>
                <th>Honorario</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th class="text-right">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @forelse($facturas as $item)
                <tr>
                    <td><strong>#{{ $item->id }}</strong></td>
                    <td>#{{ $item->id }}</td>
                    <td>{{ $item->fecha }}</td>
                    <td>
                        @include('components.file-status-badge', [
                            'available' => $item->factura != null,
                            'availableText' => 'Cargada',
                            'missingText' => 'Sin archivo',
                        ])
                    </td>
                    <td class="text-right">
                        <a target="_blank" class="btn btn-outline-secondary btn-sm" href="/facturas/show/{{ $item->id }}" data-toggle="tooltip" title="Ver factura">
                            <i class="fa fa-search"></i>
                        </a>
                        <form method="POST" action="/facturas/delete/{{ $item->id }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" title="Eliminar factura">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        Todavia no cargaste facturas.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    @endcomponent
@endsection
