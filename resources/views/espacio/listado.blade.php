@extends('layouts.app')

@section('content')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Mis Solicitudes</li>
    </ol>
    <div class="col-md-12 col xs 12 col sm 12">

        <div class="alert alert-info" role="alert">
            <strong>Info!</strong> Aca se listan todas las solicitudes de espacio que alla realizado asi como poder generar nuevas solicitudes
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-container show">
                <div class="panel-content">
                    <a href="/espacios/solicitudes/new" class="btn btn-primary">Nueva solicitud</a>
                </div>
            </div>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-container show">
                <div class="panel-content">
                    <table id="mytable" class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th class="center" style="width: 10%">ID</th>
                            <th class="center" style="width: 25%">Espacio</th>
                            <th class="center" style="width: 15%">Fecha</th>
                            <th class="center" style="width: 20%">Horario</th>
                            <th class="center" style="width: 10%">Estado</th>
                            <th class="center" style="width: 10%"><i class="fa fa-trash"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($solicitudes as $item)
                            <tr>
                                <td class="center">{{$item->pivot["id"]}}</td>
                                <td class="center">{{$item->description}}</td>
                                <td class="center">{{$item->pivot["fecha"]}}</td>
                                <td class="center">{{$item->pivot["horario"]}}</td>
                                <td class="center"><i class="{{$icon[$item->pivot["autorizado"]]}}"></i></td>
                                <td class="center"><button class="btn btn-danger" data-toggle="modal" data-target="#elim-{{$item->pivot["id"]}}" @if($item->pivot["autorizado"] == 1) disabled @endif ><i class="fa fa-trash"></i></button></td>
                            </tr>
                            <div class="modal modal-alert fade" id="elim-{{$item->pivot["id"]}}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Eliminar a {{$item->description}}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true"><i class="fal fa-times"></i></span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Esta accion no se puede deshacer
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <a type="button" href="/espacios/solicitudes/{{$item->pivot["id"]}}/remove" class="btn btn-primary">Continuar</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
