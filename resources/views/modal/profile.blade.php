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
                                <form action="profile/pic/update" enctype="multipart/form-data" method="POST">
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
                </div>
            </div>
        </div>
    </div>
</div>
