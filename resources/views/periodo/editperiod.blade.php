@extends('layouts.app')

@section('content')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Facturas</li>
    </ol>
    <div class="col-md-4 col xs 12 col sm 12">
        <div id="panel-1" class="panel">
            <div class="panel-container show">
                <div class="panel-content">
                    <a href="/editperiod" class="btn btn-primary">Atras</a>
                </div>
            </div>
        </div>
        <div id="panel-2" class="panel">
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="row">
                        <form action="/editperiod/{{$user->id}}/update" method="POST" style="width: 100%;">
                            @csrf
                            @method("PUT")
                            <input type="hidden" name="id" value="{{$user->periodos->id}}">
                            <div class="col-12 mb-15">
                                <div class="form-group">
                                    <label for="periodo" class="form-label">Periodo</label>
                                    <input type="text" class="form-control" name="periodo" value="{{$user->periodos->start}} - {{$user->periodos->end}}">
                                </div>
                            </div>
                            <div class="col-12 mb-15">
                                <div class="form-group">
                                    <label for="observacion" class="form-label">Descripcion</label>
                                    <textarea class="form-control" name="observacion" rows="5" required></textarea>
                                </div>
                            </div>
                            <div class="col-12 mb-15">
                                <div class="form-group">
                                    <label for="cantidad" class="form-label">Cantidad de dias a Insertar</label>
                                    <input type="number" class="form-control" name="cantidad" >
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
