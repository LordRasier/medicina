@extends('layouts.app')

@section('content')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Mis facturas</li>
        <li class="breadcrumb-item">Cargar nueva factura</li>
    </ol>
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Cargar <span class="fw-300"><i>Factura</i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <form action="/misFacturas/create/store" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-12">
                                @csrf
                                @method("PUT")
                                <div class="row">
                                    <div class="col-12 mb-15">
                                        <div class="form-group">
                                            <label for="fecha" class="form-label">Fecha</label>
                                            <input type="text" autocomplete="off" name="fecha" class="form-control calendar-event-date" required>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-15">
                                        <div class="form-group">
                                            <label for="numero" class="form-label">Numero de factura</label>
                                            <input type="text" name="numero" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-15">
                                        <div class="form-group">
                                            <label for="observacion" class="form-label">Observacion</label>
                                            <textarea class="form-control" name="observacion" rows="5"> </textarea>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-15">
                                        <div class="form-group">
                                            <label class="form-label" for="file">Selecciona archivo</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="file"  accept="application/pdf,html" multiple>
                                                    <label class="custom-file-label" for="file">Buscar</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="col-12">
                                <button class="btn btn-primary float-right" type="submit">Enviar</button>
                                <a class="btn btn-secondary" href="/misFacturas">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){
            $(".calendar-event-date").datepicker({dateFormat:"dd/mm/yy"});
        });
    </script>
@endsection
