@extends('layouts.app')

@section('content')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Solicitudes</li>
        <li class="breadcrumb-item">Historial</li>
    </ol>
    <div class="col-md-12 col xs 12 col sm 12">
        <div class="alert alert-info" role="alert">
            <strong>Info!</strong> Aca puedes revisar el historial de solicitudes que han sido resueltas
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-container show">
                <div class="panel-content">
                    <a href="/espacios/solicitudes/autorizar" class="btn btn-primary">Autorizar solicitudes</a>
                </div>
            </div>
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-container show">
                <div class="panel-content">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th class="center" style="width: 5%">ID</th>
                            <th class="center" style="width: 20%">Solicitante</th>
                            <th class="center" style="width: 20%">Espacio</th>
                            <th class="center" style="width: 15%">Fecha</th>
                            <th class="center" style="width: 20%">Horario</th>
                            <th class="center" style="width: 10%">Estado</th>
                            <th class="center" style="width: 10%"><i class="fa fa-search"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($solicitudes as $item)

                            <tr>
                                <td class="center">{{$item->id}}</td>
                                <td class="center">{{$item->user->name}}</td>
                                <td class="center">{{$item->espacio->description}}</td>
                                <td class="center">{{$item->fecha}}</td>
                                <td class="center">{{$item->horario}}</td>
                                <td class="center"><i class="{{$icon[$item->autorizado]}}"></i></td>
                                <td class="center"><a href="/espacios/solicitudes/{{$item->id}}/show" class="btn btn-primary" ><i class="fa fa-search"></i></a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
