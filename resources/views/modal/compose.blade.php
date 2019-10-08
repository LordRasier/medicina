<form action="/home/mail/send" method="POST">
<div id="panel-compose" class="panel w-100 position-fixed pos-bottom pos-right mb-0 z-index-cloud mr-lg-4 shadow-3 border-bottom-left-radius-0 border-bottom-right-radius-0 expand-full-height-on-mobile expand-full-width-on-mobile shadow" style="max-width:40rem; height:35rem; display: none;">
    <div class="position-relative h-100 w-100 d-flex flex-column">
        <!-- desktop view -->
        <div class="panel-hdr bg-fusion-600 height-4 d-none d-sm-none d-lg-flex">
            <h4 class="flex-1 fs-lg color-white mb-0 pl-3">
                Redactar Nuevo Correo
            </h4>
            <div class="panel-toolbar pr-2">
                <a href="javascript:void(0);" class="btn btn-icon btn-icon-light fs-xl mr-1" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Ampliar" data-placement="bottom">
                    <i class="fal fa-expand-alt"></i>
                </a>
                <a href="javascript:void(0);" class="btn btn-icon btn-icon-light fs-xl" data-action="toggle" data-class="d-flex" data-target="#panel-compose" data-toggle="tooltip" data-original-title="Cerrar" data-placement="bottom">
                    <i class="fal fa-times"></i>
                </a>
            </div>
        </div>
        <!-- end desktop view -->
        <!-- mobile view -->
        <div class="d-flex d-lg-none align-items-center px-3 py-3 bg-faded  border-faded border-top-0 border-left-0 border-right-0 flex-shrink-0">
            <!-- button for mobile -->
            <!-- end button for mobile -->
            <h3 class="subheader-title">
                Nuevo Correo
            </h3>
            <div class="ml-auto">
                <button type="button" class="btn btn-outline-danger" data-action="toggle" data-class="d-flex" data-target="#panel-compose">Cancelar</button>
            </div>
        </div>
        <!-- end mobile view -->

            @csrf
            @method("PUT")
            <div class="panel-container show rounded-0 flex-1 d-flex flex-column">
                <div class="px-3">
                    <select id="compose_destiny" name="destiny[]" class="select2 form-control border-top-0 border-left-0 border-right-0 px-0 rounded-0 fs-md mt-2 pr-5" multiple="multiple" id="multiple-basic">
                        @foreach($users as $user)
                            <option value="{{$user->id}}">{{$user->name}}</option>
                        @endforeach
                    </select>
                    <input name="asunto" type="text" placeholder="Asunto" class="form-control border-top-0 border-left-0 border-right-0 px-0 rounded-0 fs-md mt-2" tabindex="4">
                </div>
                <div class="flex-1" style="overflow-y: auto;">
                    <textarea name="body" class="form-control rounded-0 w-100 h-100 border-0 py-3" contenteditable tabindex="5">

                    </textarea>
                </div>
                <div class="px-3 py-4 d-flex flex-row align-items-center flex-wrap flex-shrink-0">
                    <button type="submit" href="javascript:void(0);" class="btn btn-info mr-3">Enviar</button>

                    <a href="javascript:void(0);" class="btn btn-icon fs-xl mr-1" data-toggle="tooltip" data-original-title="Attach files" data-placement="top">
                        <i class="fas fa-paperclip color-fusion-300"></i>
                    </a>
                </div>
            </div>

    </div>
</div> <!-- end compose message -->
</form>
