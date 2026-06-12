@extends('layouts.app')

@section('content')
    @component('components.page-header', [
        'title' => 'Facturas cargadas',
        'subtitle' => 'Archivo administrativo de facturas asociadas a honorarios.',
        'breadcrumbs' => [
            ['label' => 'Honorarios'],
            ['label' => 'Facturas'],
        ],
    ])
        <a href="/honorarios" class="btn btn-outline-secondary">
            <i class="fal fa-arrow-left mr-1"></i>
            Volver a honorarios
        </a>
    @endcomponent

    @component('components.table-panel', [
        'title' => 'Archivo de facturas',
        'description' => 'Consulta las facturas cargadas por profesional y el honorario asociado.',
    ])
        <table id="mytable" class="table table-striped table-hover ui-data-table">
            <thead>
            <tr>
                <th>Factura</th>
                <th>Profesional</th>
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
                    <td>{{ optional($item->user)->name ?? 'Usuario no disponible' }}</td>
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
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        No hay facturas cargadas para mostrar.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    @endcomponent
@endsection
