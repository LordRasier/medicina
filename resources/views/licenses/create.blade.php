@extends('layouts.app')

@section('content')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Configuraciones</li>
        <li class="breadcrumb-item">Dispensas</li>
        <li class="breadcrumb-item">Nueva Dispensa</li>
    </ol>
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Crear <span class="fw-300"><i>Dispensa</i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <form action="/dispensas/create/store" method="POST">
                        <div class="row">
                            <div class="col-12">
                                @csrf
                                @method("PUT")
                                <div class="row">
                                    <div class="col-12 mb-15">
                                        <div class="form-group">
                                            <label for="dispensa" class="form-label">Descripcion</label>
                                            <input type="text" autocomplete="off" name="dispensa" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-15">
                                        <div class="custom-control custom-checkbox">
                                            <input name="alter" id="alter" type="checkbox" class="custom-control-input">
                                            <label class="custom-control-label" for="alter">Calcula</label>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-15">
                                        <div class="custom-control custom-checkbox">
                                            <input name="habilitado" id="habilitado" type="checkbox" class="custom-control-input">
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
