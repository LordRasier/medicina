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
                    <a href="/misDispensas" class="btn btn-primary">Atras</a>
                </div>
            </div>
        </div>
        <form action="/misDispensas/create/store" method="POST" enctype="multipart/form-data">
            @csrf
            @method("PUT")
            <input type="hidden" value="{{$periodo->id}}" name="periodo">
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
                    <div id="panel-4" class="panel">
                        <div class="panel-hdr center">

                        </div>
                        <div class="row">
                            <div class="col-12 mb-15">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="licencia" class="center">Dispensa a solicitar</label>
                                        <select name="licencia" class="form-control">
                                            @foreach($licencias as $item)
                                                <option value="{{$item->id}}">{{$item->description}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
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
                                        <td data-date="{{$item[0]."-".str_pad($i,2,0,STR_PAD_LEFT)}}" class="center @empty($marcar[$item[0]."-".str_pad($i,2,0,STR_PAD_LEFT)]) @empty($domingos[$item[0]."-".str_pad($i,2,0,STR_PAD_LEFT)]) dia @endempty @endempty purple-hover " @isset($marcar[$item[0]."-".str_pad($i,2,0,STR_PAD_LEFT)]) style="background-color:#a38cc6" @endisset> @isset($domingos[$item[0]."-".str_pad($i,2,0,STR_PAD_LEFT)])<i class="far fa-times"></i> @endisset

                                        </td>
                                        @empty($marcar[$item[0]."-".str_pad($i,2,0,STR_PAD_LEFT)])
                                            @empty($domingos[$item[0]."-".str_pad($i,2,0,STR_PAD_LEFT)])
                                                <input id="D{{$item[0]."-".str_pad($i,2,0,STR_PAD_LEFT)}}" value="{{$item[0]."-".str_pad($i,2,0,STR_PAD_LEFT)}}" type="checkbox" name="dia[]" class="dias" style="opacity:0; position:absolute; left:9999px;">
                                            @endempty
                                        @endempty
                                    @endfor
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-12">
                    <div id="panel-4" class="panel" style="padding-top: 1.5rem;">
                        <div class="row">
                            <div class="col-12 mb-15">
                                <div class="col">
                                    <div class="input-group">
                                        <label for="file" class="form-label" style="width:100%">Adjunto</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="file"  accept="application/pdf,html" >
                                            <label class="custom-file-label" for="file">Buscar</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-15">
                                <div class="col">
                                    <div class="input-group">
                                        <label for="description" class="form-label" style="width:100%">Descripcion</label>
                                        <textarea name="description" class="form-control" cols="5" rows="5" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-15 center">
                                <button class="btn btn-primary center">Enviar solicitud</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script>
        var disponibles = {{$disponibles + $especiales - $usados}};

        $(document).ready(function(){
            var usados = 0;

            $(".dia").click(function () {
                usados = 0;
                var fecha = $(this).data("date");
                $(".dias").each(function () {
                    if ($(this).is(":CHECKED")) {
                        usados++;
                    }
                });
                console.log(usados+" "+disponibles);
                if (usados < disponibles) {
                    $("#D" + fecha).click();
                    if ($("#D" + fecha).is(":CHECKED")) {
                        $(this).css("background-color", "#a38cc6 !important");
                    } else {
                        $(this).css("background-color", "");
                    }
                }else{
                    $("#D" + fecha).prop("checked",false);
                    $(this).css("background-color", "");
                }
            });
        });
    </script>
@endsection
