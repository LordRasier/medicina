@extends('layouts.app')

@section('content')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Dispensas</li>
        <li class="breadcrumb-item">Edicion</li>
    </ol>
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Editar <span class="fw-300"><i>{{$dispensa->description}}</i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <form action="/dispensas/show/{{$dispensa->id}}/update" method="POST">
                        <div class="row">
                            <div class="col-12">
                                @csrf
                                @method("PUT")
                                <div class="row">
                                    <input type="hidden" name="id" value="{{$dispensa->id}}">
                                    <div class="col-12 mb-15">
                                        <div class="form-group">
                                            <label for="dispensa" class="form-label">Descripcion</label>
                                            <input value="{{$dispensa->description}}" type="text" autocomplete="off" name="dispensa" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-15">
                                        <div class="custom-control custom-checkbox">
                                            <input @if($dispensa->alter == 1)Checked @endif name="alter" id="alter" type="checkbox" class="custom-control-input">
                                            <label class="custom-control-label" for="alter">Calcula</label>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-15">
                                        <div class="custom-control custom-checkbox">
                                            <input @if($dispensa->habilitado == 1)Checked @endif name="habilitado" id="habilitado" type="checkbox" class="custom-control-input">
                                            <label class="custom-control-label" for="habilitado">Habilitado</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="col-12">
                                <button class="btn btn-primary float-right" type="submit">Enviar</button>
                                <a class="btn btn-secondary" href="/dispensas">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
