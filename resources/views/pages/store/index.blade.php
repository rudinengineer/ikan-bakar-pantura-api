@include('pages.store.js')

<div class="container-fluid">
    <div class="datatables">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <div class="mb-3">
                        <h5>Kelola Cabang</h5>
                        <div class="form-text">Kelola cabang disini.</div>
                    </div>

                    <div class="w-100 d-flex justify-content-between align-items-center">
                        <!-- Create Modal -->
                        @if (check_user_access('store', 'create'))
                            <div>
                                <button id="create-btn" class="justify-content-center w-100 btn mb-1 btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#add-modal">
                                    <i class="ti ti-plus fs-4 me-2"></i>
                                    <span>Tambah Cabang</span>
                                </button>
                            </div>

                            <div id="add-modal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <form id="create-form" method="post" class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title mt-0" id="myModalLabel">Tambah Cabang</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            @include('components.loading.spinner')
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                                            <button id="create-btn-submit" class="btn btn-primary waves-effect waves-light">
                                                @include('components.loading.button')
                                                <span>Save Data</span>
                                            </button>
                                        </div>
                                    </form><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div>
                        @else
                            <div></div>
                        @endif

                        <!-- Refresh Button -->
                        <button title="Refresh Data" id="refresh-btn" class="btn btn-sm btn-warning">
                            <i class="fas fa-sync"></i>
                            <span>Refresh</span>
                        </button>
                    </div>
                </div>

                <div class="mt-4 table-responsive">
                    <table
                        id="table"
                        class="w-100 table table-striped table-bordered text-nowrap align-middle">
                        <thead>
                            <!-- start row -->
                            <tr>
                                <th class="text-center" style="width: 30px;">No</th>
                                <th>Logo</th>
                                <th>Nama Cabang</th>
                                <th>Area</th>
                                <th class="text-center">Pilihan</th>
                            </tr>
                            <!-- end row -->
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div id="edit-modal" class="modal fade" tabindex="-1"
    aria-labelledby="edit-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <form method="post" id="edit-form" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title" id="myModalLabel">
                    Edit Cabang
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @include('components.loading.spinner')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-danger text-light  waves-effect"
                    data-bs-dismiss="modal">
                    Close
                </button>
                <button id="edit-btn" class="btn bg-primary text-light  waves-effect">
                    @include('components.loading.button')
                    <span>Save Data</span>
                </button>
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>