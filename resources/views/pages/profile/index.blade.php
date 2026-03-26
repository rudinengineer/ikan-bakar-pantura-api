@include('pages.profile.js')

<div class="container-fluid">

    <div class="page-content-wrapper">

        <div class="row">
            <div class="col-lg-6">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h5 class="mb-4">Data Saya</h5>
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td>Nama</td>
                                    <td>: <b>{{ $user->name }}</b></td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>: <b>{{ $user->email }}</b></td>
                                </tr>
                                <tr>
                                    <td>Whatsapp</td>
                                    <td>: <b>{{ $user->phone_prefix }}{{ $user->phone }}</b></td>
                                </tr>
                                <tr>
                                    <td>Password</td>
                                    <td>: <b>********</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h5 class="mb-4">Ubah Password</h5>
                        <form id="form" method="POST" action="">
                            <div class="mb-3">
                                <label for="" class="form-label">Password</label>
                                <input type="password" name="old_password" placeholder="********" class="form-control">
                                <small id="old_password-error" class="text-danger"></small>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Password Baru</label>
                                <input type="password" name="password" placeholder="********" class="form-control">
                                <small id="password-error" class="text-danger"></small>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Konfirmasi Password Baru</label>
                                <div class="input-group">
                                    <input type="password" placeholder="********" name="confirm_password" class="form-control">
                                </div>
                                <small id="confirm_password-error" class="text-danger"></small>
                            </div>
                            <div class="w-100 text-end">
                                <button type="submit" id="btn-submit" class="btn btn-primary">
                                    @include('components.loading.button')
                                    <span>
                                        <i class="fa fa-save"></i> Simpan Perubahan
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>