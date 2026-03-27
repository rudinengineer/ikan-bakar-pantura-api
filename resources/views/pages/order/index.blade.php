@include('pages.order.js')

{{-- Content --}}
<div class="container-fluid">
    <div class="datatables">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <div class="mb-3">
                        <h5>Kelola Pesanan</h5>
                        <div class="form-text">Kelola pesanan disini.</div>
                    </div>

                    <div class="w-100 d-flex justify-content-between align-items-center">
                        <!-- Create Modal -->
                        @if (check_user_access('order', 'create') && auth()->user()->role->level > 1)    
                            <div>
                                <button id="create-btn" class="justify-content-center w-100 btn mb-1 btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#add-modal">
                                    <i class="ti ti-plus fs-4 me-2"></i>
                                    <span>Buat Pesanan</span>
                                </button>
                            </div>

                            <div id="add-modal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <form id="create-form" method="post" class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title mt-0" id="myModalLabel">Buat Pesanan</h5>
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
                                <th>ID Pesanan</th>
                                @if (auth()->user()->role->level >= 1)
                                    <th>Cabang</th>
                                @endif
                                <th>Pelanggan</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Tamu</th>
                                <th>Total</th>
                                <th>Status</th>
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

{{-- Detail Modal --}}
<div id="edit-modal" class="modal fade" tabindex="-1"
    aria-labelledby="edit-modal" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <form method="post" id="edit-form" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title" id="myModalLabel">
                    Detail Pesanan
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @include('components.loading.spinner')
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

{{-- Modal Confirm --}}
<div id="modalConfirm" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="myModalLabel">Apakah anda yakin?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    
                </button>
            </div>
            <div class="modal-body">Pesanan akan dikonfirmasi</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Cancel</button>
                <button id="confirm-btn" type="button" class="btn btn-danger waves-effect waves-light">
                    @include('components.loading.button')
                    <span>Konfirmasi Pesanan</span>
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>