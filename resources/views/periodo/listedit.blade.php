@extends('layouts.app')

@section('content')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Usuarios</li>
    </ol>
    <div class="col-md-12 col xs 12 col sm 12">
        @isset($resp)
            <div class="alert alert-success" role="alert">
                <strong>Info!</strong> Dias asignados
            </div>
        @endisset
        <div id="panel-1" class="panel">
            <div class="panel-container show">
                <div class="panel-content">
                    <a href="/editperiod" class="btn btn-primary">Atras</a>
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
                            <th class="center" style="width: 35%">Usuario</th>
                            <th class="center" style="width: 35%">Periodo</th>
                            <th class="center" style="width: 35%">Dias</th>
                            <th class="center" style="width: 10%"><i class="fa fa-search"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($extras as $item)
                            <tr>
                                <td class="center">{{$item->id}}</td>
                                <td class="center">{{$item->user->name}}</td>
                                <td class="center">{{$item->periodo->start}} - {{$item->periodo->end}}</td>
                                <td class="center">{{$item->days}}</td>
                                <td class="center"><button class="btn btn-primary" data-toggle="modal" data-target="#detail-{{$item->id}}"><i class="fa fa-search"></i></button></td>
                            </tr>
                            <div class="modal modal-alert fade" id="detail-{{$item->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detalle de la solicitud {{$item->name}}</h5>
                                                <span aria-hidden="true"><i class="fal fa-times"></i></span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            {{$item->description}}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>>
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
