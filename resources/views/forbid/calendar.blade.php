@extends('layouts.app')

@section('content')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">{{$actual}}</li>
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
                                <div class="col-md-6">
                                    <a data-template="<div class=&quot;tooltip&quot; role=&quot;tooltip&quot;><div class=&quot;tooltip-inner bg-primary-500&quot;></div></div>" data-toggle="tooltip" title="" data-original-title="Año anterior" href="/configuracion/forbid/{{$actual - 1}}" class="btn btn-primary btn-block"><i class="far fa-chevron-left"></i></a>
                                </div>
                                <div class="col-md-6">
                                    <a data-template="<div class=&quot;tooltip&quot; role=&quot;tooltip&quot;><div class=&quot;tooltip-inner bg-primary-500&quot;></div></div>" data-toggle="tooltip" title="" data-original-title="Año Siguiente" href="/configuracion/forbid/{{$actual + 1}}" class="btn btn-secondary btn-block"><i class="far fa-chevron-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form action="/configuracion/forbid/store" method="POST" enctype="multipart/form-data">
            @csrf
            @method("PUT")
            <input type="hidden" name="anio" value="{{$actual}}">
        <div class="row">
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
                                    <td data-date="{{$item[0]."-".str_pad($i,2,0,STR_PAD_LEFT)}}" class="center  @empty($domingos[$item[0]."-".str_pad($i,2,0,STR_PAD_LEFT)]) dia @endempty purple-hover " @isset($marcar[$item[0]."-".str_pad($i,2,0,STR_PAD_LEFT)]) style="background-color:#a38cc6" @endisset> @isset($domingos[$item[0]."-".str_pad($i,2,0,STR_PAD_LEFT)])<i class="far fa-times"></i> @endisset

                                    </td>

                                        @empty($domingos[$item[0]."-".str_pad($i,2,0,STR_PAD_LEFT)])
                                            <input id="D{{$item[0]."-".str_pad($i,2,0,STR_PAD_LEFT)}}" value="{{$item[0]."-".str_pad($i,2,0,STR_PAD_LEFT)}}" type="checkbox" name="dia[]" class="dias" style="opacity:0; position:absolute; left:9999px;" @isset($marcar[$item[0]."-".str_pad($i,2,0,STR_PAD_LEFT)]) checked @endisset>
                                        @endempty

                                @endfor
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="panel-4" class="panel">
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="row">
                                <button type="submit" class="btn btn-primary btn-block">Actualizar Periodo</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script>

        $(document).ready(function(){


            $(".dia").click(function () {
                    var fecha = $(this).data("date");
                    $("#D" + fecha).click();
                    if ($("#D" + fecha).is(":CHECKED")) {
                        console.log(this);
                        $(this).css("background-color", "#a38cc6 !important");
                    } else {
                        $(this).css("background-color", "");
                    }

            });
        });
    </script>
@endsection
