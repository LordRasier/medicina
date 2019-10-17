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
                    <a href="/misDispensas/solicitud" class="btn btn-primary">Generar nueva solicitud</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="panel-2" class="panel">
                    <div class="panel-hdr center">
                        <h2 class="center">Periodo</h2>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-15">
                            <h4 class="center">Inicio</h4>
                            <h3 class="center" style="width:100%">{{$periodo->start}}</h3>
                        </div>
                        <div class="col-6 mb-15">
                            <h4 class="center">Fin</h4>
                            <h3 class="center" style="width:100%">{{$periodo->end}}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div id="panel-3" class="panel">
                    <table class="table table-bordered" style="margin-bottom: 0">
                        <thead>
                            <tr>
                                <th></th>
                                @for($i = 1; $i <= 31; $i++)
                                    <th class="center">{{$i}}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($almanaque as $item)
                                <tr>
                                    <td>{{$item}}</td>
                                    @for($i = 1; $i <= 31; $i++)
                                        <td data-date="{{$item."-".str_pad($i,2,0,STR_PAD_LEFT)}}" class="center purple-hover">@isset($domingos[$item."-".str_pad($i,2,0,STR_PAD_LEFT)])<i class="far fa-times"></i> @endisset</td>
                                    @endfor
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
