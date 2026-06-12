@extends('layouts.app')

@section('content')
    @component('components.page-header', [
        'title' => 'Mis honorarios',
        'subtitle' => 'Consulta tus liquidaciones y gestiona la factura asociada.',
        'breadcrumbs' => [
            ['label' => 'Honorarios'],
            ['label' => 'Mis honorarios'],
        ],
    ])
        <a href="/misFacturas" class="btn btn-outline-secondary">
            <i class="fal fa-file-invoice mr-1"></i>
            Mis facturas
        </a>
    @endcomponent

    @isset($elim)
        <div class="alert alert-success" role="alert">
            Archivo eliminado correctamente.
        </div>
    @endisset

    @component('components.table-panel', [
        'title' => 'Liquidaciones disponibles',
        'description' => 'Cada honorario permite ver el comprobante y cargar o consultar su factura.',
    ])
        <table id="mytable" class="table table-striped table-hover ui-data-table">
            <thead>
            <tr>
                <th>Honorario</th>
                <th>Fecha</th>
                <th>Comprobante</th>
                <th>Factura</th>
                <th class="text-right">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @forelse($honorarios as $item)
                <tr>
                    <td><strong>#{{ $item->id }}</strong></td>
                    <td>{{ $item->fecha }}</td>
                    <td>
                        @include('components.file-status-badge', [
                            'available' => $item->file != null,
                            'availableText' => 'Disponible',
                            'missingText' => 'Sin archivo',
                        ])
                    </td>
                    <td>
                        @include('components.file-status-badge', [
                            'available' => $item->factura != null,
                            'availableText' => 'Facturada',
                            'missingText' => 'Sin factura',
                        ])
                    </td>
                    <td class="text-right">
                        <a target="_blank" class="btn btn-outline-secondary btn-sm" href="/honorarios/show/{{ $item->id }}" data-toggle="tooltip" title="Ver honorario">
                            <i class="fa fa-search"></i>
                        </a>
                        @if($item->factura != null)
                            <a target="_blank" class="btn btn-outline-secondary btn-sm" href="/facturas/show/{{ $item->id }}" data-toggle="tooltip" title="Ver factura">
                                <i class="fal fa-file-invoice"></i>
                            </a>
                        @else
                            <a class="btn btn-outline-primary btn-sm" href="/misFacturas/create/{{ $item->id }}" data-toggle="tooltip" title="Cargar factura">
                                <i class="fa fa-upload"></i>
                            </a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        No hay honorarios cargados para mostrar.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    @endcomponent
@endsection
