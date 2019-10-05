@extends('layouts.app')

@section('content')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Espacios</li>
        <li class="breadcrumb-item">Solicitudes</li>
        <li class="breadcrumb-item">Definir</li>
    </ol>
    <div class="row">
        <div class="col-md-4 col-sm-12 col-xs-12">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        Autorizar <span class="fw-300"><i>Solicitud N# {{$item->id}}</i></span>
                    </h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <form action="/espacios/solicitudes/definir" method="POST">
                            <div class="row">
                                <div class="col">
                                    @csrf
                                    @method("PUT")
                                    <input name="id" type="hidden" value="{{$item->id}}">
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
                                                <input name="fecha" type="text" class="@error("fecha") is-invalid @enderror form-control" maxlength="20" value="{{$item->fecha}}{{old("fecha")}}" placeholder="Fecha a solicitar" readonly>
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
                                                <textarea rows="5" name="respuesta" type="text" class="@error("respuesta") is-invalid @enderror form-control"  placeholder="Respuesta a la solicitud" required></textarea>
                                                @error("respuesta") <div class="invalid-feedback">Este campo es obligatorio</div> @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 mb-15">
                                            <div class="frame-wrap mb-0">
                                                <div class="btn-group btn-group-toggle btn-block" data-toggle="buttons">
                                                    <label class="btn btn-primary">
                                                        <input type="radio" name="estado" value="1"> Autorizar
                                                    </label>
                                                    <label class="btn btn-secondary">
                                                        <input type="radio" name="estado" value="2"> Rechazar
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-12">
                                    <button class="btn btn-primary float-right" type="submit">Enviar resolucion</button>
                                    <a class="btn btn-secondary" href="/espacios/solicitudes/autorizar">Atras</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="alert alert-info" role="alert">
                <strong>Info!</strong> Para autorizar haga click en un uno de los botones de resolucion inferiores, detalle el motivo de la resolucion y haga click en el boton enviar!
            </div>
        </div>
    </div>
@endsection
