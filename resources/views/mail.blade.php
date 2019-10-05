@extends('layouts.app')

@section('content')
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">{{$route}}</li>
    </ol>
    <div class="d-flex flex-grow-1 p-0">
        <!-- left slider -->
        <div id="js-inbox-menu" class="flex-wrap position-relative bg-white slide-on-mobile slide-on-mobile-left">
            <div class="position-absolute pos-top pos-bottom w-100">
                <div class="d-flex h-100 flex-column">
                    <div class="px-3 px-sm-4 px-lg-5 py-4 align-items-center">
                        <div class="btn-group btn-block" role="group" aria-label="Button group with nested dropdown ">
                            <button type="button" class="btn btn-danger btn-block fs-md" data-action="toggle" data-class="d-flex" data-target="#panel-compose" data-focus="message-to">Nuevo Mensaje</button>
                        </div>
                    </div>
                    <div class="flex-1 pr-3">
                        <a href="/mail/inbox" class="dropdown-item px-3 px-sm-4 pr-lg-3 pl-lg-5 py-2 fs-md  d-flex justify-content-between rounded-pill border-top-left-radius-0 border-bottom-left-radius-0 ">
                            <div>
                                <i class="fas fa-folder-open width-1"></i>Bandeja de entrada
                            </div>
                            <div class="fw-400 fs-xs js-unread-emails-count"></div>
                        </a>
                        <a href="/mail/outbox" class="dropdown-item px-3 px-sm-4 pr-lg-3 pl-lg-5 py-2 fs-md d-flex justify-content-between rounded-pill border-top-left-radius-0 border-bottom-left-radius-0">
                            <div>
                                <i class="fas fa-paper-plane width-1"></i>Enviados
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="slide-backdrop" data-action="toggle" data-class="slide-on-mobile-left-show" data-target="#js-inbox-menu"></div> <!-- end left slider -->
        <!-- inbox container -->
        <div class="d-flex flex-column flex-grow-1 bg-white">
            <!-- inbox header -->
            <div class="flex-grow-0">
                <!-- inbox button shortcut -->

                <!-- end inbox button shortcut -->
            </div>
            <!-- end inbox header -->
            <!-- inbox message -->
            <div class="flex-wrap align-items-center flex-grow-1 position-relative bg-white">
                <div class=" position-absolute pos-top pos-bottom w-100 custom-scroll">
                    <div class="d-flex h-100 flex-column">
                        <!-- inbox title -->

                        <!-- end inbox title -->
                        <!-- message -->
                        @foreach($mails as $mail)
                        <div id="msg-{{$mail->id}}" class="d-flex flex-column border-faded border-top-0 border-left-0 border-right-0 py-3 px-3 px-sm-4 px-lg-0 mr-0 mr-lg-5 flex-shrink-0">
                            <div class="d-flex align-items-center flex-row">
                                <div class="ml-0 mr-3 mx-lg-3">
                                    <img src="/storage/profile/{{$mail->oprofile}}" class="profile-image profile-image-md rounded-circle" alt="{{$mail->oname}}">
                                </div>
                                <div class="fw-500 flex-1 d-flex flex-column cursor-pointer" data-toggle="collapse" data-target="#msg-{{$mail->id}} > .js-collapse">
                                    <div class="fs-lg">
                                        {{$mail->oname}}
                                        <span class="fs-nano fw-400 ml-2">{{$mail->oemail}}</span>
                                        para {{$mail->dname}} <span class="fs-nano fw-400 ml-2">{{$mail->demail}}</span>
                                    </div>
                                    <div class="fs-nano">
                                       {{$mail->asunto}}
                                    </div>
                                </div>
                                <div class="color-fusion-200 fs-sm">
                                    {{$mail->created_at}}
                                </div>
                                <div class="collapsed-reveal">
                                    <a href="javascript:void(0);" onclick="response_compose({{$mail->oid}},'{{$mail->asunto}}')" class="btn btn-icon ml-1 fs-lg rounded-circle" data-action="toggle" data-class="d-flex" data-target="#panel-compose" data-focus="message-to">
                                        <i class="fal fa-reply"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="collapse js-collapse">
                                <div class="pl-lg-5 ml-lg-5 pt-3 pb-4">
                                    {{$mail->body}}
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <!-- end message -->
                    </div>
                </div>
            </div>
            <!-- end inbox message -->
        </div>
        <!-- end inbox container -->
        <!-- compose message -->
    </div>
    <script>
        function response_compose(destino,asunto){
            $('#compose_destiny').val(destino).trigger('change');
            $("[name='asunto']").val("RW: "+asunto);
        }

    </script>
@endsection
