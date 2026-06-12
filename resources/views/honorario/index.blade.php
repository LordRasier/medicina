@extends('layouts.app')

@section('content')
    @component('components.page-header', [
        'title' => 'Honorarios cargados',
        'subtitle' => 'Listado administrativo de liquidaciones importadas al sistema.',
        'breadcrumbs' => [
            ['label' => 'Honorarios'],
            ['label' => 'Archivo'],
        ],
    ])
        <a href="/honorarios/create" class="btn btn-primary">
            <i class="fal fa-upload mr-1"></i>
            Cargar registros
        </a>
    @endcomponent

    @isset($result)
        <div class="alert alert-success" role="alert">
            Importacion procesada correctamente.
            @isset($importSummary)
                <span class="ml-2">Archivos importados: {{ $importSummary['imported'] ?? 0 }}.</span>
                <span class="ml-2">Omitidos: {{ $importSummary['skipped'] ?? 0 }}.</span>
            @endisset
        </div>
    @endisset

    @isset($elim)
        <div class="alert alert-success" role="alert">
            Honorario eliminado correctamente.
        </div>
    @endisset

    @component('components.table-panel', [
        'title' => 'Liquidaciones',
        'description' => 'Consulta comprobantes de honorarios y facturas asociadas por profesional.',
    ])
        <table id="mytable" class="table table-striped table-hover ui-data-table">
            <thead>
            <tr>
                <th>Honorario</th>
                <th>Profesional</th>
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
                    <td>{{ optional($item->user)->name ?? 'Usuario no disponible' }}</td>
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
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        No hay honorarios cargados para mostrar.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    @endcomponent
@endsection
