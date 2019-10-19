@extends('layouts.app')

@section('content')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Facturas</li>
    </ol>
    <div class="col-md-12 col xs 12 col sm 12">
        <div id="panel-1" class="panel">
            <div class="panel-container show">
                <div class="panel-content">
                    <a href="/misDispensas/solicitudes" class="btn btn-primary">Atras</a>
                </div>
            </div>
        </div>
        <div id="panel-2" class="panel">
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Detalle Aut. 2</label>
                                <textarea rows="5" class="form-control">{{$periodo->observacion2}}</textarea>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Detalle Aut. 3</label>
                                <textarea rows="5" class="form-control">{{$periodo->observacion3}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
