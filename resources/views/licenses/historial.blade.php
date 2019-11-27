@extends('layouts.app')

@section('content')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Detalle de la solicitud</li>
    </ol>
    <div class="row">
        <div class="col-md-6 col xs 12 col sm 12">
            <div id="panel-1" class="panel">
                <div class="panel-container show">
                    <div class="panel-content">
                        <a href="/dispensas/historial" class="btn btn-primary">Atras</a>
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
                                    <input class="form-control" type="text" value="@isset($solicitud->user->name){{$solicitud->user->name}} @endisset" readonly>
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
                                    <textarea class="form-control" rows="5" readonly>{{$solicitud->description}}</textarea>
                                </div>
                            </div>
                            <div class="col-12 mb-15">
                                <div class="form-group">
                                    <label class="form-label">Respuesta del autorizador</label>
                                    <textarea class="form-control" rows="5" readonly>{{$solicitud->respuesta}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col xs 12 col sm 12">
            <div id="panel-2" class="panel">
                <div class="panel-container show">
                    <div class="panel-content">
                        <div class="row">
                            @foreach($solicitud->days as $item)
                                <div class="col-3 mb-15">
                                    <div class="form-group">
                                        <label class="form-label">{{$solicitud->licence->description}}</label>
                                        <input type="text" value="{{$item->date}}" class="form-control" disabled>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection