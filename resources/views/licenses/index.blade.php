@extends('layouts.app')

@section('content')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Configuracion</li>
        <li class="breadcrumb-item">Dispensa</li>
    </ol>
    <div class="col-md-12 col xs 12 col sm 12">
        <div class="alert alert-info" role="alert">
            <strong>Info!</strong> Listado de tipos de dispensas habilitadas en el sistema
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-container show">
                <div class="panel-content">
                    <a href="/dispensas/create" class="btn btn-primary">Definir nueva dispensa</a>
                </div>
            </div>
        </div>
        <div id="panel-2" class="panel">
            <div class="panel-container show">
                <div class="panel-content">
                    <table id="mytable" class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th class="center" style="width: 10%">ID</th>
                            <th class="center" style="width: 30%">Dispensas</th>
                            <th class="center" style="width: 30%">Calcula</th>
                            <th class="center" style="width: 20%">Habilitado</th>
                            <th class="center" style="width: 10%"><i class="fa fa-edit"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($dispensas as $item)
                            <tr>
                                <td class="center">{{$item->id}}</td>
                                <td class="center">{{$item->description}}</td>
                                <td class="center"><i class="{{$icon[$item->alter]}}"></i></td>
                                <td class="center"><i class="{{$icon[$item->habilitado]}}"></i></td>
                                <td class="center"><a class="btn btn-secondary" href="/dispensas/show/{{$item->id}}"><i class="fa fa-edit"></i></a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
