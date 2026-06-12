@extends('layouts.app')

@section('content')
    @component('components.page-header', [
        'title' => 'Cargar honorarios',
        'subtitle' => 'Importacion masiva de comprobantes por profesional.',
        'breadcrumbs' => [
            ['label' => 'Honorarios', 'url' => '/honorarios'],
            ['label' => 'Cargar registros'],
        ],
    ])
        <a href="/honorarios" class="btn btn-outline-secondary">
            <i class="fal fa-arrow-left mr-1"></i>
            Volver al archivo
        </a>
    @endcomponent

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <strong>Revisar carga.</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-7">
            @component('components.table-panel', [
                'title' => 'Archivos de honorarios',
                'description' => 'Puedes seleccionar uno o varios archivos para importarlos en una sola operacion.',
            ])
                <form action="/honorarios/create/store" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method("PUT")

                    <div class="upload-dropzone">
                        <i class="fal fa-file-upload"></i>
                        <label for="honorarios-file" class="form-label">Seleccionar archivos</label>
                        <div class="custom-file">
                            <input id="honorarios-file" type="file" class="custom-file-input @error('file') is-invalid @enderror" name="file[]" accept=".pdf,.jpg,.jpeg,.png" multiple required>
                            <label class="custom-file-label" for="honorarios-file">Buscar archivos</label>
                        </div>
                        <small class="text-muted d-block mt-2">Formatos permitidos: PDF, JPG, JPEG y PNG.</small>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button class="btn btn-primary" type="submit">
                            <i class="fal fa-upload mr-1"></i>
                            Importar honorarios
                        </button>
                    </div>
                </form>
            @endcomponent
        </div>

        <div class="col-lg-5">
            @component('components.table-panel', [
                'title' => 'Formato esperado',
                'description' => 'El importador toma la fecha y el codigo profesional desde el nombre del archivo.',
            ])
                <div class="format-example">
                    <span>Ejemplo</span>
                    <code>Planilla - 2026-06-20 - MED123-detalle.pdf</code>
                </div>
                <ul class="upload-guidelines">
                    <li>La fecha debe poder interpretarse como fecha valida.</li>
                    <li>El codigo profesional debe coincidir con el campo `code` del usuario.</li>
                    <li>Los archivos sin formato valido se omiten y quedan reportados en el resumen.</li>
                </ul>
            @endcomponent
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.custom-file-input').forEach(function (input) {
                input.addEventListener('change', function () {
                    var names = Array.prototype.map.call(input.files, function (file) {
                        return file.name;
                    });
                    input.nextElementSibling.textContent = names.length > 1 ? names.length + ' archivos seleccionados' : (names[0] || 'Buscar archivos');
                });
            });
        });
    </script>
@endpush
