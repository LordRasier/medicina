@extends('layouts.app')

@section('content')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Espacios</li>
    </ol>
    <div class="col-md-12 col xs 12 col sm 12">
        <div id="panel-1" class="panel">
            <div class="panel-container show">
                <div class="panel-content">
                    <a href="/espacios/new" class="btn btn-primary">Nuevo espacio</a>
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
                                <th class="center" style="width: 35%">Espacio</th>
                                <th class="center" style="width: 10%"><i class="fa fa-pencil"></i></th>
                                <th class="center" style="width: 10%"><i class="fa fa-trash"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($espacios as $item)
                            <tr>
                                <td class="center">{{$item->id}}</td>
                                <td class="center">{{$item->description}}</td>
                                <td class="center"><a class="btn btn-secondary" href="/espacios/{{$item->id}}/edit"><i class="fa fa-edit"></i></a></td>
                                <td class="center"><button class="btn btn-danger" data-toggle="modal" data-target="#elim-{{$item->id}}"><i class="fa fa-trash"></i></button></td>
                            </tr>
                            <div class="modal modal-alert fade" id="elim-{{$item->id}}" tabindex="-1" role="dialog" aria-hidden="true">
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
                                            <a type="button" href="/espacios/{{$item->id}}/remove" class="btn btn-primary">Continuar</a>
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
