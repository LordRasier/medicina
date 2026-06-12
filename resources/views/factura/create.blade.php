@extends('layouts.app')

@section('content')
    @component('components.page-header', [
        'title' => 'Cargar factura',
        'subtitle' => 'Factura asociada al honorario #' . $honorario,
        'breadcrumbs' => [
            ['label' => 'Honorarios', 'url' => '/misHonorarios'],
            ['label' => 'Cargar factura'],
        ],
    ])
        <a href="/misHonorarios" class="btn btn-outline-secondary">
            <i class="fal fa-arrow-left mr-1"></i>
            Cancelar
        </a>
    @endcomponent

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <strong>Revisar factura.</strong>
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
                'title' => 'Archivo de factura',
                'description' => 'Selecciona el comprobante que corresponde a este honorario.',
            ])
                <form action="/misFacturas/create/store" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method("PUT")
                    <input type="hidden" name="honorario" value="{{ $honorario }}">

                    <div class="upload-dropzone">
                        <i class="fal fa-file-invoice"></i>
                        <label for="factura-file" class="form-label">Seleccionar factura</label>
                        <div class="custom-file">
                            <input id="factura-file" type="file" class="custom-file-input @error('file') is-invalid @enderror" name="file" accept=".pdf,.jpg,.jpeg,.png" required>
                            <label class="custom-file-label" for="factura-file">Buscar archivo</label>
                        </div>
                        <small class="text-muted d-block mt-2">Formatos permitidos: PDF, JPG, JPEG y PNG.</small>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button class="btn btn-primary" type="submit">
                            <i class="fal fa-upload mr-1"></i>
                            Cargar factura
                        </button>
                    </div>
                </form>
            @endcomponent
        </div>

        <div class="col-lg-5">
            @component('components.table-panel', [
                'title' => 'Honorario asociado',
                'description' => 'La factura quedara vinculada a este registro.',
            ])
                <dl class="request-summary mb-0">
                    <dt>Honorario</dt>
                    <dd>#{{ $honorario }}</dd>

                    <dt>Estado</dt>
                    <dd>
                        @include('components.file-status-badge', [
                            'available' => false,
                            'missingText' => 'Pendiente de carga',
                        ])
                    </dd>
                </dl>
            @endcomponent
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.custom-file-input').forEach(function (input) {
                input.addEventListener('change', function () {
                    var fileName = input.files.length ? input.files[0].name : 'Buscar archivo';
                    input.nextElementSibling.textContent = fileName;
                });
            });
        });
    </script>
@endpush
