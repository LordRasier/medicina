@extends('layouts.app')

@section('content')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Usuarios</li>
    </ol>
    <div class="col-md-12 col xs 12 col sm 12">
        <div id="panel-1" class="panel">
            <div class="panel-container show">
                <div class="panel-content">
                    <a href="/extralist" class="btn btn-primary">Asignaciones</a>
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
                            <th class="center" style="width: 35%">Nombre</th>
                            <th class="center" style="width: 35%">Email</th>
                            <th class="center" style="width: 10%"><i class="fa fa-pencil"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $item)
                            <tr>
                                <td class="center">{{$item->id}}</td>
                                <td class="center">{{$item->name}}</td>
                                <td class="center">{{$item->email}}</td>
                                <td class="center"><a class="btn btn-primary" href="/editperiod/{{$item->id}}"><i class="far fa-edit"></i></a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
