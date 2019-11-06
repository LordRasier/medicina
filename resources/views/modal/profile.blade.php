<div class="modal fade default-example-modal-right-sm profile" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-right modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h4">Mi Perfil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-lg-12 col-xl-12 ">
                    <!-- profile summary -->
                    <div class="row no-gutters row-grid">
                        <div class="col-12">
                            <div class="d-flex flex-column align-items-center justify-content-center p-4">
                                <img src="/storage/profile/{{$user->profile}}" class="rounded-circle shadow-2 img-thumbnail" alt="">
                                <form action="/profile/pic/update" enctype="multipart/form-data" method="POST">
                                    @csrf
                                    @method("put")
                                    <a href="#" onclick="$('[name=pic]').click()" class="btn btn-primary btn-icon rounded-circle change-pic"><i class="fal fa-sync"></i></a>
                                    <input onchange="this.form.submit()" type="file" name="pic" style="visibility: hidden" accept="jpg,png">
                                </form>
                                <h5 class="mb-0 fw-700 text-center mt-3">
                                    {{$user->name}}
                                    <small class="text-muted mb-0">{{$user->email}}</small>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <form action="/profile/newpass" method="post">
                                @csrf
                                @method("put")
                                <div class="col-12 mb-15">
                                    <div class="form-group">
                                      <label for="newpass" class="form-label">Nueva contraseña</label>
                                      <input class="form-control" type="password" name="pass" value="" placeholder="Nueva contraeña">
                                    </div>
                                </div>
                                <div class="col-12 mb-15">
                                    <div class="form-group">
                                      <label for="re-pass" class="form-label">Repita nueva contraseña</label>
                                      <input class="form-control" type="password" name="re-pass" value="" placeholder="Nueva contraeña">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-block">Actualizar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
