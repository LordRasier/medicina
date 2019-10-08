@extends('layouts.app')

@section('content')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Espacios</li>
        <li class="breadcrumb-item">Solicitudes</li>
        <li class="breadcrumb-item">Nueva</li>
    </ol>
    <div class="col-md-6 col-sm-12 col-xs-12">

        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Nueva Solicitud de <span class="fw-300"><i>Espacio</i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <form action="/espacios/new/create" method="POST">
                        <div class="row">
                            <div class="col">
                                @csrf
                                @method("PUT")
                                <div class="row">
                                    <div class="col mb-15">
                                        <div class="form-group">
                                            <label class="form-label" for="description">Nombre</label>
                                            <input name="description" type="text" class="@error("description") is-invalid @enderror form-control" maxlength="50" value="{{old("description")}}" placeholder="Nombre del espacio" required>
                                            @error("description") <div class="invalid-feedback">Este campo es obligatorio</div> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="col-12">
                                <button class="btn btn-primary float-right" type="submit">Enviar solicitud</button>
                                <a class="btn btn-secondary" href="/espacios/solicitudes/list">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
