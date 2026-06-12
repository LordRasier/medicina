@extends('layouts.app')

@section('content')
    @php
        $availableDays = max(0, $disponibles + $especiales - $usados);
        $selectedDays = old('dia', []);
    @endphp

    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="/home">Inicio</a></li>
        <li class="breadcrumb-item"><a href="/misDispensas">Mis dispensas</a></li>
        <li class="breadcrumb-item active">Nueva solicitud</li>
    </ol>

    <form action="/misDispensas/create/store" method="POST" enctype="multipart/form-data">
        @csrf
        @method("PUT")
        <input type="hidden" value="{{ $periodo->id }}" name="periodo">

        <div class="dispensa-toolbar">
            <div>
                <h1 class="page-title mb-1">Nueva solicitud de dispensa</h1>
                <div class="text-muted">Periodo {{ $periodo->start }} al {{ $periodo->end }}</div>
            </div>
            <div class="dispensa-actions">
                <a href="/misDispensas" class="btn btn-outline-secondary">
                    <i class="fal fa-arrow-left mr-1"></i>
                    Volver
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fal fa-paper-plane mr-1"></i>
                    Enviar solicitud
                </button>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>Revisar solicitud.</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="metric-strip mb-3">
            <div class="metric-item">
                <span>Disponibles</span>
                <strong>{{ $availableDays }}</strong>
            </div>
            <div class="metric-item">
                <span>Usados</span>
                <strong>{{ $usados }}</strong>
            </div>
            <div class="metric-item">
                <span>Extras</span>
                <strong>{{ $especiales }}</strong>
            </div>
            <div class="metric-item">
                <span>Seleccionados</span>
                <strong id="selected-days-count">{{ count($selectedDays) }}</strong>
            </div>
        </div>

        <div class="panel">
            <div class="panel-hdr">
                <h2>Datos de la solicitud</h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="licencia">Dispensa a solicitar</label>
                            <select id="licencia" name="licencia" class="form-control @error('licencia') is-invalid @enderror">
                                @foreach($licencias as $item)
                                    <option value="{{ $item->id }}" @selected((int) old('licencia') === (int) $item->id)>
                                        {{ $item->description }}
                                    </option>
                                @endforeach
                            </select>
                            @error('licencia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="file">Adjunto</label>
                            <div class="custom-file">
                                <input id="file" type="file" class="custom-file-input @error('file') is-invalid @enderror" name="file" accept=".pdf,.jpg,.jpeg,.png">
                                <label class="custom-file-label" for="file">Seleccionar archivo</label>
                                @error('file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="description">Descripcion</label>
                            <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-hdr">
                <h2>Dias solicitados</h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content no-padding">
                    <div class="table-responsive">
                        <table class="table table-bordered calendar-request-table mb-0">
                            <thead>
                            <tr>
                                <th class="month-column">Mes</th>
                                @for($i = 1; $i <= 31; $i++)
                                    <th>{{ $i }}</th>
                                @endfor
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($almanaque as $item)
                                <tr>
                                    <th class="month-column">{{ $item[1] }}</th>
                                    @for($i = 1; $i <= 31; $i++)
                                        @php
                                            $date = $item[0] . "-" . str_pad($i, 2, 0, STR_PAD_LEFT);
                                            $isUnavailable = isset($domingos[$date]);
                                            $isUsed = isset($marcar[$date]);
                                            $isSelectable = ! $isUnavailable && ! $isUsed;
                                            $isSelected = in_array($date, $selectedDays, true);
                                        @endphp
                                        <td
                                            data-date="{{ $date }}"
                                            class="calendar-day @if($isSelectable) js-day-select @endif @if($isUnavailable) is-blocked @endif @if($isUsed) is-used @endif @if($isSelected) is-selected @endif"
                                        >
                                            @if($isUnavailable)
                                                <i class="far fa-times"></i>
                                            @elseif($isUsed)
                                                <i class="far fa-check"></i>
                                            @endif
                                        </td>
                                        @if($isSelectable)
                                            <input
                                                id="D{{ $date }}"
                                                value="{{ $date }}"
                                                type="checkbox"
                                                name="dia[]"
                                                class="dias sr-only"
                                                @checked($isSelected)
                                            >
                                        @endif
                                    @endfor
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var availableDays = {{ $availableDays }};
            var selectedCounter = document.getElementById('selected-days-count');

            function selectedCount() {
                return document.querySelectorAll('.dias:checked').length;
            }

            function refreshCounter() {
                selectedCounter.textContent = selectedCount();
            }

            document.querySelectorAll('.js-day-select').forEach(function (cell) {
                cell.addEventListener('click', function () {
                    var checkbox = document.getElementById('D' + cell.dataset.date);

                    if (! checkbox.checked && selectedCount() >= availableDays) {
                        return;
                    }

                    checkbox.checked = ! checkbox.checked;
                    cell.classList.toggle('is-selected', checkbox.checked);
                    refreshCounter();
                });
            });

            document.querySelectorAll('.custom-file-input').forEach(function (input) {
                input.addEventListener('change', function () {
                    var fileName = input.files.length ? input.files[0].name : 'Seleccionar archivo';
                    input.nextElementSibling.textContent = fileName;
                });
            });
        });
    </script>
@endsection
