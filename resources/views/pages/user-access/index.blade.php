@include('pages.user-access.js')

<div class="container-fluid">
    <div class="datatables">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <div class="header-title mb-4">
                        <div class="w-100 d-flex justify-content-between">
                            <div>
                                <h5>Ubah Peran Pengguna</h5>
                                <div class="form-text">Centang untuk memberikan akses kepada peran <span class="text-danger"><b>{{ $role->name }}</b></span></div>
                            </div>

                            <!-- Refresh Button -->
                            <div>
                                <button title="Refresh Data" id="refresh-btn" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-sync"></i>
                                    <span>Refresh</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 table-responsive">
                    <table
                        id="table"
                        class="w-100 table table-striped table-bordered text-nowrap align-middle">
                        <thead>
                            <th class="text-center" style="width: 30px;">No</th>
                            <th>Nama Akses</th>
                            <th>Menu</th>
                            <th>Melihat</th>
                            <th>Membuat</th>
                            <th>Mengubah</th>
                            <th>Menghapus</th>
                            <th>Menyetujui</th>
                            <th>Akses Bawahan</th>
                            <th class="text-center">Pilihan</th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>