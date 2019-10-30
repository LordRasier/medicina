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
