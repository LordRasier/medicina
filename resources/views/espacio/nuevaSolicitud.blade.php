@extends('layouts.app')

@section('content')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Espacios</li>
        <li class="breadcrumb-item">Solicitud</li>
        <li class="breadcrumb-item">Nueva</li>
    </ol>
    <div class="row">
        <div class="col-md-4 col-sm-12 col-xs-12">
            @isset($result)
                @if($result == true)
                    <div class="alert alert-success" role="alert">
                        <strong>Info!</strong> Solicitud creada correctamente
                    </div>
                @endif
                @if($result == false)
                    <div class="alert alert-danger" role="alert">
                        <strong>Info!</strong> Ya esta reservado este espacio en esta fecha y horario
                    </div>
                @endif
            @endisset
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        Nuevo <span class="fw-300"><i>Espacio</i></span>
                    </h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <form action="/espacios/solicitudes/new/create" method="POST">
                            <div class="row">
                                <div class="col">
                                    @csrf
                                    @method("PUT")
                                    <div class="row">
                                        <div class="col-12 mb-15">
                                            <div class="form-group">
                                                <label class="form-label" for="fecha">Fecha</label>
                                                <input disabled name="fecha" type="text" class="@error("fecha") is-invalid @enderror form-control" maxlength="20" value="{{old("fecha")}}" placeholder="Fecha a solicitar" required>
                                                @error("fecha") <div class="invalid-feedback">Este campo es obligatorio</div> @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 mb-15">
                                            <div class="form-group">
                                                <label class="form-label" for="espacio">Espacio a reservar</label>
                                                <select name="espacio"  class="form-control" required>
                                                    <option value="NA" selected="" disabled="">Seleccione un espacio</option>
                                                    @foreach($espacios as $item)
                                                        <option value="{{$item->id}}">{{$item->description}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 mb-15">
                                            <div class="form-group">
                                                <label class="form-label" for="horario">Horario a solicitar</label>
                                                <select name="horario"  class="form-control" required>
                                                    <option value="NA" selected="" disabled="">Seleccione un horario</option>
                                                    <option value="Mañana (8:00 - 12:00)">Mañana (8:00 - 12:00)</option>
                                                    <option value="Tarde (13:00 - 17:00)">Tarde (13:00 - 17:00)</option>                                                   </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 mb-15">
                                            <div class="form-group">
                                                <label class="form-label" for="detalle">Detalle de la solicitud</label>
                                                <textarea rows="5" name="detalle" type="text" class="@error("detalle") is-invalid @enderror form-control" maxlength="20" value="{{old("detalle")}}" placeholder="Descripcion" required></textarea>
                                                @error("detalle") <div class="invalid-feedback">Este campo es obligatorio</div> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-12">
                                    <button class="btn btn-primary float-right" type="submit">Enviar solicitud</button>
                                    <a class="btn btn-secondary" href="/espacios/solicitudes/list">Atras</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-sm-12 col-xs-12">
            <div class="alert alert-info" role="alert">
                <strong>Info!</strong> Seleccione una fecha en el calendario y rellene los campos solicitados y envie la solicitud, se le notificara por correo cuando sea resuelta.
            </div>
            <div id="panel-2" class="panel">
                <div class="panel-hdr">
                    <h2>
                        Espacios <span class="fw-300"><i>Reservados</i></span>
                    </h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <div id="espacios-reservados" style="overflow: auto;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script>

        $(document).ready(function(){

            var calendarEl = document.getElementById('espacios-reservados');

            var calendar = new FullCalendar.Calendar(calendarEl,
                {
                    plugins: ['dayGrid', 'bootstrap','interaction'],
                    themeSystem: 'bootstrap',
                    timeZone: 'UTC',
                    dateClick: function(date, jsEvent, view) {
                        $("[name='fecha']").val(date["dateStr"]);
                        $(".fc-day").css("background-color","transparent");
                        date.dayEl.style.backgroundColor = '#1dc9b7';
                    },
                    dateAlignment: "month", //week, month
                    buttonText:
                        {
                            today: 'today',
                            month: 'month',
                            week: 'week',
                            day: 'day',
                            list: 'list'
                        },
                    eventTimeFormat:
                        {
                            hour: 'numeric',
                            minute: '2-digit',
                            meridiem: 'short'
                        },
                    navLinks: true,
                    header:
                        {
                            left: 'title',
                            center: '',
                            right: 'today prev,next'
                        },
                    footer:
                        {
                            left: '',
                            center: '',
                            right: ''
                        },
                    editable: true,
                    eventLimit: true, // allow "more" link when too many events

                    viewSkeletonRender: function()
                    {
                        $('.fc-toolbar .btn-default').addClass('btn-sm');
                        $('.fc-header-toolbar h2').addClass('fs-md');
                        $('#calendar').addClass('fc-reset-order')
                    },

                });

            calendar.render();
            $('.calendar').on('shown.bs.modal', function (e) {
                $(".fc-dayGridMonth-button").click();
            });
            $(".calendar-event-date").datepicker({dateFormat:"dd/mm/yy"});

        });
    </script>
@endsection
