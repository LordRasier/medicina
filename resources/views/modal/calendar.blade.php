<div class="modal fade default-example-modal-left-xl calendar" tabindex="-1" role="dialog" aria-hidden="true" style="overflow-y: scroll">
    <div class="modal-dialog modal-dialog-left modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h4">Agenda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col col-1 no-padding">
                    <button data-toggle="modal" data-target=".sub-calendar" class="btn btn-success fill ">Nuevo evento</button>
                </div>
                <hr>
                <div id="panel-3" class="panel">
                    <div class="panel-hdr">
                        <h2 class="js-get-date"></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade sub-calendar" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-left modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h4">Nuevo evento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/calendar/event/add" method="POST">
                    @csrf
                    @method("put")
                    <div class="col col-6">
                        <div class="form-group">
                            <label class="form-label" for="fecha">Fecha</label>
                            <input class="form-control calendar-event-date"  type="text" name="fecha" autocomplete="off">
                        </div>
                    </div>
                    <div class="col col-12">
                        <div class="form-group">
                            <label class="form-label" for="descripcion">Descripcion</label>
                            <textarea rows="5" class="form-control" name="descripcion"></textarea>
                        </div>
                    </div>
                    <hr>
                    <div class="col col-12">
                        <button data-dismiss="modal" class="btn btn-danger pull-left">Atras</button>
                        <button class="btn btn-success float-right" type="submit">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



