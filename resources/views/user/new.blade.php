@extends('layouts.app')

@section('content')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Usuarios</li>
        <li class="breadcrumb-item">Nuevo</li>
    </ol>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Nuevo <span class="fw-300"><i>Usuario</i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <form action="/users/new/create" method="POST">
                        <div class="row">
                            <div class="col-6">
                                @csrf
                                @method("PUT")
                                <div class="row">
                                    <div class="col-6 mb-15">
                                        <div class="form-group">
                                            <label class="form-label" for="ingreso">Fecha de ingreso</label>
                                            <input autocomplete="disabled" name="ingreso" type="text" class="calendar-event-date @error("ingreso") is-invalid @enderror form-control" maxlength="4" value="{{old("ingreso")}}" placeholder="01/12/2007" required>
                                            @error("ingreso") <div class="invalid-feedback">Este campo es obligatorio</div> @enderror
                                        </div>
                                    </div>
                                    <div class="col-6 mb-15">
                                        <div class="form-group">
                                            <label class="form-label" for="codigo">Codigo</label>
                                            <input name="codigo" type="text" class="@error("codigo") is-invalid @enderror form-control" maxlength="4" value="{{old("codigo")}}" placeholder="0000" required>
                                            @error("codigo") <div class="invalid-feedback">Este campo es obligatorio</div> @enderror
                                        </div>
                                    </div>
                                    <div class="col-6 mb-15">
                                        <div class="form-group">
                                            <label class="form-label" for="name">Nombre</label>
                                            <input name="name" type="text" class="@error("name") is-invalid @enderror form-control" maxlength="50" value="{{old("name")}}" placeholder="Nombre" required>
                                            @error("name") <div class="invalid-feedback">Este campo es obligatorio</div> @enderror
                                        </div>
                                    </div>
                                    <div class="col mb-15">
                                        <div class="form-group">
                                            <label class="form-label" for="doc">Documento</label>
                                            <input name="doc" type="text" class="@error("doc") is-invalid @enderror form-control" maxlength="50" value="{{old("doc")}}" placeholder="Documento" required>
                                            @error("doc") <div class="invalid-feedback">Minimo 8 caracteres o el documento ya existe</div> @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 mb-15">
                                        <div class="form-group">
                                            <label class="form-label" for="email">Email</label>
                                            <input name="email" type="text" class="@error("email") is-invalid @enderror form-control" maxlength="50" value="{{old("email")}}" placeholder="Email" required>
                                            @error("email") <div class="invalid-feedback">No es un correo valido o ya esta registrado</div> @enderror
                                        </div>
                                    </div>
                                    <div class="col-6 mb-15">
                                        <div class="form-group">
                                            <label class="form-label" for="tipo">Tipo</label>
                                            <select name="tipo" class="form-control" required>
                                                <option value="NA" selected="" disabled="">Tipo de Usuario</option>
                                                @foreach($types as $item)
                                                    <option value="{{$item->id}}">{{$item->description}}</option>
                                                @endforeach
                                            </select>
                                            @error("tipo") <div class="invalid-feedback">Minimo 8 caracteres</div> @enderror
                                        </div>
                                    </div>
                                    <div class="col-6 mb-15">
                                        <div class="form-group">
                                            <label class="form-label" for="dedication">Dedicacion</label>
                                            <select name="dedication" class="form-control" required>
                                                <option value="NA" selected="" disabled="">dedicacion</option>
                                                @foreach($dedications as $item)
                                                    <option value="{{$item->id}}">{{$item->description}}</option>
                                                @endforeach
                                            </select>
                                            @error("dedication") <div class="invalid-feedback">Minimo 8 caracteres</div> @enderror
                                        </div>
                                    </div>
                                    <div class="col mb-15">
                                        <div class="form-group">
                                            <label class="form-label" for="especialidad">Especialidad</label>
                                            <select name="especialidad" class="form-control" required>
                                                <option value="NA" selected="" disabled="">Especialidad</option>
                                                @foreach($specialtys as $item)
                                                    <option value="{{$item->id}}">{{$item->description}}</option>
                                                @endforeach
                                            </select>
                                            @error("especialidad") <div class="invalid-feedback">Minimo 8 caracteres</div> @enderror
                                        </div>
                                    </div>
                                    <div class="col-3 mb-15">
                                        <div class="form-group">
                                            <label class="form-label" for="horas">Horas dedicadas</label>
                                            <input name="horas" type="text" class="@error("horas") is-invalid @enderror form-control" value="0" maxlength="3" value="{{old("horas")}}" placeholder="horas" required>
                                            @error("horas") <div class="invalid-feedback">Minimo 8 caracteres</div> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <h3>Accesos</h3>
                                        <div class="row">
                                        @foreach($acceso as $item)
                                            <div class="col-6">
                                                <div class="col-12 mb-15">
                                                 <h4>{{$item->name}}</h4>
                                                </div>
                                                @foreach($item->hijos as $item)
                                                <div class="col-12 mb-15">
                                                    <div class="custom-control custom-checkbox">
                                                        <input name="access[]" value="{{$item->id}}" type="checkbox" class="custom-control-input" id="C{{$item->id}}">
                                                        <label class="custom-control-label" for="C{{$item->id}}">{{$item->name}}</label>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="col-12">
                                <button class="btn btn-primary float-right" type="submit">Crear</button>
                                <a class="btn btn-secondary" href="/users">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
