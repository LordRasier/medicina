@extends('layouts.app')

@section('content')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Espacios</li>
        <li class="breadcrumb-item">Solicitud N# {{$item->id}}</li>
    </ol>
    <div class="row">
        <div class="col-md-4 col-sm-12 col-xs-12">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        Solicitud de <span class="fw-300"><i>Espacio N# {{$item->id}}</i></span>
                    </h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <form action="/espacios/solicitudes/definir" method="POST">
                            <div class="row">
                                <div class="col">
                                    @csrf
                                    @method("PUT")
                                    <div class="row">
                                        <div class="col-12 mb-15">
                                            <div class="form-group">
                                                <label class="form-label" for="user">Solicitante</label>
                                                <input name="user" type="text" class="form-control" value="{{$item->user[0]["name"]}}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-12 mb-15">
                                            <div class="form-group">
                                                <label class="form-label" for="fecha">Fecha</label>
                                                <input name="fecha" type="text" class="@error("fecha") is-invalid @enderror form-control" maxlength="20" value="{{$item->fecha}}" placeholder="Fecha a solicitar" readonly>
                                                @error("fecha") <div class="invalid-feedback">Este campo es obligatorio</div> @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 mb-15">
                                            <div class="form-group">
                                                <label class="form-label" for="espacio">Espacio a reservar</label>
                                                <input name="espacio" type="text" class="form-control" value="{{$item->espacio[0]["description"]}}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-12 mb-15">
                                            <div class="form-group">
                                                <label class="form-label" for="horario">Horario a solicitar</label>
                                                <input name="horario" type="text" class="form-control" value="{{$item->horario}}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-12 mb-15">
                                            <div class="form-group">
                                                <label class="form-label" for="detalle">Detalle de la solicitud</label>
                                                <textarea rows="5" name="detalle" type="text" class="@error("detalle") is-invalid @enderror form-control"  placeholder="Descripcion" readonly>{{$item->detalle}}</textarea>
                                                @error("detalle") <div class="invalid-feedback">Este campo es obligatorio</div> @enderror
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="col-12 mb-15">
                                            <div class="form-group">
                                                <label class="form-label" for="respuesta">Respuesta a la solicitud</label>
                                                <textarea rows="5" name="respuesta" type="text" class="form-control"  placeholder="Respuesta a la solicitud" readonly>{{$item->respuesta}}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-12 mb-15">
                                            <div class="form-group">
                                                <label class="form-label" for="autorizado">Estado</label>
                                                <input name="autorizado" type="text" class="form-control" value="{{$estado[$item->autorizado]}}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-12">
                                    <a class="btn btn-secondary btn-block" href="/espacios/solicitudes/historial">Atras</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
