@extends('layouts.app')

@section('content')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Facturas</li>
    </ol>
    <div class="col-md-12 col xs 12 col sm 12">
        <div class="alert alert-info" role="alert">
            <strong>Info!</strong> Revise las facturas que han sido cargadas y haga el click en el boton <i class="far fa-search fa-xs"></i> para mirarla en el navegador
        </div>
        @isset($resp)
            @if($resp == true)
                <div class="alert alert-success" role="alert">
                    <strong>Info!</strong> Autorizacion enviada
                </div>
            @endif
            @if($resp == false)
                <div class="alert alert-danger" role="alert">
                    <strong>Info!</strong> Notificacion de rechazo enviada
                </div>
            @endif
        @endisset
        <div id="panel-2" class="panel">
            <div class="panel-container show">
                <div class="panel-content">
                    <table id="mytable" class="table table-striped table-hover">
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
                                <td class="center"><a class="btn btn-secondary" href="/autorizaciones/3/definir/{{$item->id}}"><i class="fa fa-search"></i></a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
