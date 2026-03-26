@include('pages.app-setting.js')

<div class="container-fluid">

    <div class="page-content-wrapper">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <div class="header-title mb-4">
                            <div class="mb-3">
                                <h5>Pengaturan</h5>
                                <div class="form-text">Atur aplikasi di sini.</div>
                            </div>
                        </div>

                        {{-- <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link btn-setting-web active" aria-current="page" href="javascript:void(0)">Aplikasi</a>
                            </li>
                        </ul> --}}
                        <div class="w-100 setting-container pt-4 ps-1 pe-1 pb-3">
                            <form id="form" method="POST" action="">
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">Nama Aplikasi</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" placeholder="Cth: Ikan Bakar Pantura" name="name" required="" value="{{ $store->name }}" autocomplete="off">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">Logo Aplikasi</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="file" name="logo" accept="image/*">

                                        @if ($store->logo)
                                        <div class="mt-3">
                                            <img src="{{ asset('uploads/' . $store->logo) }}" alt="{{ $store->name }}" width="100" height="100">
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">Area</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" placeholder="Cth: Merakurak" name="area" required="" value="{{ $store->area }}" autocomplete="off">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">Alamat</label>
                                    <div class="col-sm-10">
                                        <textarea rows="3" class="form-control" type="text" placeholder="Cth: Jln. Soedirman" name="address" required="" style="resize: none;">{{ $store->address }}</textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">WhatsApp</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="number" placeholder="Cth: 628xxx" name="whatsapp" required="" value="{{ $store->whatsapp }}" autocomplete="off">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">Nama BANK</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" placeholder="Cth: BCA" name="bank" required="" value="{{ $store->bank }}" autocomplete="off">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">Nomor Rekening</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="number" placeholder="Cth: 123xxxx" name="account_number" required="" value="{{ $store->account_number }}" autocomplete="off">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">Nama Pemilik Rekening</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" placeholder="Cth: Hendra" name="account_name" required="" value="{{ $store->account_name }}" autocomplete="off">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">QRIS</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="file"  name="qris" accept="image/*">

                                        @if ($store->qris)
                                            <div class="mt-3">
                                                <img src="{{ asset('uploads/' . $store->qris) }}" alt="QRIS" width="100" height="100">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12 text-end">
                                        <button id="btn-submit" type="submit" class="btn btn-primary">
                                            @include('components.loading.button')
                                            <span>
                                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div> <!-- end col -->
        </div>

    </div>
</div>