@extends('layouts.app')

@section('content')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Cargar honorarios</li>
    </ol>
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Cargar <span class="fw-300"><i>Honorarios</i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <form action="/honorarios/create/store" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-12">
                                @csrf
                                @method("PUT")
                                <div class="row">
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
                                <a class="btn btn-secondary" href="/honorarios/index">Archivo</a>
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
