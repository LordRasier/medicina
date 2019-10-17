@extends('layouts.app')

@section('content')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Configuracion</li>
        <li class="breadcrumb-item">Dispensa</li>
    </ol>
    <div class="col-md-12 col xs 12 col sm 12">
        <div class="alert alert-info" role="alert">
            <strong>Info!</strong> Aqui podras ver las solicitudes de dispensas correspondientes al periodo actual
        </div>
        <div id="panel-1" class="panel">
            <div class="panel-container show">
                <div class="panel-content">
                    <a href="/dispensas/create" class="btn btn-primary">Generar nueva solicitud</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div id="panel-2" class="panel">
                    <div class="panel-hdr center">
                        <h3 style="width:100%">Periodo</h3>
                    </div>
                    <div class="row">

                            <div class="col-6 mb-15">
                                <h4 class="center">Inicio</h4>
                                <label>{{$periodo->start}}</label>
                            </div>
                            <div class="col-6 mb-15">
                                <label class="label">Fin</label>
                                <label>{{$periodo->end}}</label>
                            </div>

                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div id="panel-3" class="panel">

                </div>
            </div>
        </div>
    </div>
@endsection

