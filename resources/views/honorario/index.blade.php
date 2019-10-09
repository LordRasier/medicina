@extends('layouts.app')

@section('content')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Honorarios</li>
    </ol>
    <div class="col-md-12 col xs 12 col sm 12">
        <div class="alert alert-info" role="alert">
            <strong>Info!</strong> Revise los honorarios que han sido cargados y haga el click en el boton <i class="far fa-search fa-xs"></i> para mirarla en el navegador
        </div>
        <div id="panel-2" class="panel">
            <div class="panel-container show">
                <div class="panel-content">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th class="center" style="width: 10%">ID</th>
                            <th class="center" style="width: 30%">Usuario</th>
                            <th class="center" style="width: 20%">Fecha</th>
                            <th class="center" style="width: 10%"><i class="fa fa-search"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($facturas as $item)
                            <tr>
                                <td class="center">{{$item->id}}</td>
                                <td class="center">{{$item->user["name"]}}</td>
                                <td class="center">{{$item->fecha}}</td>
                                <td class="center"><a target="_blank" class="btn btn-secondary" href="/honorarios/show/{{$item->id}}"><i class="fa fa-search"></i></a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
