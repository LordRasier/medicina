@extends('layouts.app')

@section('content')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">{{$use->name}}</li>
        <li class="breadcrumb-item">Dispensas</li>
    </ol>
    <div class="col-md-12 col xs 12 col sm 12">
        <div class="alert alert-info" role="alert">
            <strong>Info!</strong> Aqui podras ver las solicitudes de dispensas correspondientes al periodo actual
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="panel-4" class="panel">
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="row">
                                <div class="col-md-5">
                                    @if($prevno)
                                        <a data-template="<div class=&quot;tooltip&quot; role=&quot;tooltip&quot;><div class=&quot;tooltip-inner bg-primary-500&quot;></div></div>" data-toggle="tooltip" title="" data-original-title="Periodo anterior" href="/dispensas/check/{{$use->id}}/prev" class="btn btn-primary btn-block"><i class="far fa-chevron-left"></i></a>
                                    @endif
                                </div>
                                <div class="col-md-2">
                                    <a data-template="<div class=&quot;tooltip&quot; role=&quot;tooltip&quot;><div class=&quot;tooltip-inner bg-primary-500&quot;></div></div>" data-toggle="tooltip" title="" data-original-title="Periodo actual" href="/dispensas/check/{{$use->id}}" class="btn btn-primary btn-block"><i class="far fa-chevron-down"></i></a>
                                </div>
                                <div class="col-md-5">
                                    @if($nextno)
                                        <a data-template="<div class=&quot;tooltip&quot; role=&quot;tooltip&quot;><div class=&quot;tooltip-inner bg-primary-500&quot;></div></div>" data-toggle="tooltip" title="" data-original-title="Periodo Siguiente" href="/dispensas/check/{{$use->id}}/next" class="btn btn-secondary btn-block"><i class="far fa-chevron-right"></i></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div id="panel-2" class="panel">
                    <div class="panel-hdr center">
                        <h2 class="center">Periodo {{$periodo->start}} - {{$periodo->end}}</h2>
                    </div>
                    <div class="row">
                        <div class="col-3 mb-15">
                            <h3 class="center">Dispensas Comunes asigandas</h3>
                            <h4 class="center" style="width:100%">{{$disponibles}}</h4>
                        </div>
                        <div class="col-3 mb-15">
                            <h3 class="center">Dispensas Extraordinarias asignadas</h3>
                            <h4 class="center" style="width:100%">{{$especiales}}</h4>
                        </div>
                        <div class="col-3 mb-15">
                            <h3 class="center">Dispensas Usadas</h3>
                            <h4 class="center" style="width:100%">{{$usados}}</h4>
                        </div>
                        <div class="col-3 mb-15">
                            <h3 class="center">Total disponible</h3>
                            <h4 class="center" style="width:100%">{{$disponibles + $especiales - $usados}}</h4>
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
                                <td>{{$item[1]}}</td>
                                @for($i = 1; $i <= 31; $i++)
                                    <td data-date="{{$item[0]."-".str_pad($i,2,0,STR_PAD_LEFT)}}" @isset($marcar[$item[0]."-".str_pad($i,2,0,STR_PAD_LEFT)]) style="background-color:#a38cc6" @endisset class="center purple-hover">@isset($domingos[$item[0]."-".str_pad($i,2,0,STR_PAD_LEFT)])<i class="far fa-times"></i> @endisset</td>
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
