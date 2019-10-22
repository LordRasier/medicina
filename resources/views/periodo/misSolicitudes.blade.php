@extends('layouts.app')

@section('content')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Dispensas</li>
        <li class="breadcrumb-item">Mis solicitudes</li>
    </ol>
    <div class="col-md-12 col xs 12 col sm 12">
        <div class="alert alert-info" role="alert">
            <strong>Info!</strong> Revise sus solicitudes de dispensas en esta pantalla!
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-container show">
                <div class="panel-content">
                    <a href="/misDispensas" class="btn btn-primary">Atras</a>
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
                            <th class="center" style="width: 30%">Fecha</th>
                            <th class="center" style="width: 30%">Aut. 2</th>
                            <th class="center" style="width: 20%">Aut. 3</th>
                            <th class="center" style="width: 10%"><i class="fa fa-search"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($solicitudes as $item)
                            <tr>
                                <td class="center">{{$item->id}}</td>
                                <td class="center">{{$item->created_at}}</td>
                                <td class="center"><i class="{{$icono[$item->autorizacion2]}}"></i></td>
                                <td class="center"><i class="{{$icono[$item->autorizacion3]}}"></i></td>
                                <td class="center"><a class="btn btn-secondary" href="/request/detalle/{{$item->id}}"><i class="fa fa-search"></i></a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
