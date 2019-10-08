@extends('layouts.app')

@section('content')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Facturas</li>
    </ol>
    <div class="col-md-12 col xs 12 col sm 12">
        <div class="alert alert-info" role="alert">
            <strong>Info!</strong> Aqui puede ver todas las facturas cargadas y acceder a cargar una nueva!.
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-container show">
                <div class="panel-content">
                    <a href="/misFacturas/create" class="btn btn-primary">Cargar nueva factura</a>
                </div>
            </div>
        </div>
        <div id="panel-2" class="panel">
            <div class="panel-container show">
                <div class="panel-content">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th class="center" style="width: 10%">ID</th>
                            <th class="center" style="width: 30%">Emisor</th>
                            <th class="center" style="width: 30%">Numero</th>
                            <th class="center" style="width: 20%">Fecha</th>
                            <th class="center" style="width: 10%"><i class="fa fa-search"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($facturas as $item)
                            <tr>
                                <td class="center">{{$item->id}}</td>
                                <td class="center">{{$item->user["name"]}}</td>
                                <td class="center">{{$item->numero}}</td>
                                <td class="center">{{$item->fecha}}</td>
                                <td class="center"><a target="_blank" class="btn btn-secondary" href="/facturas/show/{{$item->id}}"><i class="fa fa-search"></i></a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
