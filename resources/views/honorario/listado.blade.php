@extends('layouts.app')

@section('content')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Mis honorarios</li>
    </ol>
    <div class="col-md-12 col xs 12 col sm 12">
        <div class="alert alert-info" role="alert">
            <strong>Info!</strong> Aqui puede ver todas los honorarios cargados y acceder a cargar una nueva!.
        </div>
        @isset($elim)
            <div class="alert alert-danger" role="alert">
                <strong>Info!</strong> Archivo eliminado
            </div>
        @endisset
        <div id="panel-2" class="panel">
            <div class="panel-container show">
                <div class="panel-content">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th class="center" style="width: 10%">ID</th>
                            <th class="center" style="width: 20%">Fecha</th>
                            <th class="center" style="width: 10%">Honorario</th>
                            <th class="center" style="width: 10%">Factura</th>
                            <th class="center" style="width: 10%">Cargar</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($honorarios as $item)
                            <tr>
                                <td class="center">{{$item->id}}</td>
                                <td class="center">{{$item->fecha}}</td>
                                <td class="center"><a target="_blank" class="btn btn-primary" href="/honorarios/show/{{$item->id}}"><i class="fa fa-search"></i></a></td>
                                <td class="center"><a target="_blank" class="btn btn-secondary @if($item->factura == null) btn-danger @else btn-success @endif" href="@if($item->factura == null) # @else /facturas/show/{{$item->id}} @endif"><i class="fa fa-search"></i></a></td>
                                <td class="center"><a  class="btn btn-secondary" href="/misFacturas/create/{{$item->id}}"><i class="fa fa-upload"></i></a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
