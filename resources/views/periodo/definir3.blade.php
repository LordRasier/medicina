@extends('layouts.app')

@section('content')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Facturas</li>
    </ol>
    <div class="col-md-6 col xs 12 col sm 12">
        <div id="panel-1" class="panel">
            <div class="panel-container show">
                <div class="panel-content">
                    <a href="/autorizaciones/2" class="btn btn-primary">Atras</a>
                </div>
            </div>
        </div>
        <div id="panel-2" class="panel">
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="row">
                        <div class="col-12 mb-15">
                            <div class="form-group">
                                <label class="form-label">Solicitante</label>
                                <input class="form-control" type="text" value="{{$solicitud->user->name}}" readonly>
                            </div>
                        </div>
                        <div class="col-12 mb-15">
                            <div class="form-group">
                                <label class="form-label">Fecha de solicitud</label>
                                <input class="form-control" type="text" value="{{$solicitud->created_at}}" readonly>
                            </div>
                        </div>
                        <div class="col-12 mb-15">
                            <div class="form-group">
                                <label class="form-label">Archivo adjunto</label>
                                @if($solicitud->file != null) <a target="_blank" href="/checkfile/{{$solicitud->id}}">Click aqui para ver!</a> @endif
                            </div>
                        </div>
                        <div class="col-12 mb-15">
                            <div class="form-group">
                                <label class="form-label">Descripcion</label>
                                <textarea class="form-control" rows="5">{{$solicitud->description}}</textarea>
                            </div>
                        </div>
                        <hr>
                        <form action="/autorizaciones/3/definir/{{$solicitud->id}}/update" method="POST" style="width: 100%;">
                            @csrf
                            @method("PUT")
                            <input type="hidden" name="id" value="{{$solicitud->id}}">
                            <div class="col-12 mb-15">
                                <div class="form-group">
                                    <label for="respuesta" class="form-label">Descripcion</label>
                                    <textarea class="form-control" name="respuesta" rows="5" required></textarea>
                                </div>
                            </div>
                            <div class="col-12 mb-15">
                                <div class="frame-wrap mb-0">
                                    <div class="btn-group btn-group-toggle btn-block" data-toggle="buttons">
                                        <label class="btn btn-primary">
                                            <input type="radio" name="estado" value="2"> Autorizar
                                        </label>
                                        <label class="btn btn-secondary">
                                            <input type="radio" name="estado" value="0"> Rechazar
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary float-right" type="submit">Enviar resolucion</button>
                            </div>
                        </form>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection