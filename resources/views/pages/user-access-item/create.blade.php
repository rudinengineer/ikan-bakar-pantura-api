<div class="row">
    <div class="mb-3 text-start">
        <label for="name" class="form-label"><span class="text-danger">*</span>Nama Akses</label>
        <input name="access_name" type="text" class="form-control" id="name" placeholder="Contoh: Beranda" autocomplete="off">
        <small id="access_name-error" class="text-danger"></small>
    </div>

    <div class="mb-3 text-start">
        <label for="access_link" class="form-label"><span class="text-danger">*</span>Link Akses</label>
        <input name="access_link" type="text" class="form-control" id="access_link" placeholder="Contoh: home" autocomplete="off">
        <div class="form-text">Hanya huruf dan tanda "_" atau "-" yang diperbolehkan!</div>
        <small id="access_link-error" class="text-danger"></small>
    </div>

    <div class="col-6">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" checked="" id="access_menu" name="access_menu">
            <label class="form-check-label" for="access_menu">Akses Menu</label>
        </div>
        <small id="access_menu-error" class="text-danger"></small>
    </div>
    <div class="col-6">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="access_create" name="access_create">
            <label class="form-check-label" for="access_create">Akses Membuat</label>
        </div>
        <small id="access_create-error" class="text-danger"></small>
    </div>
    <div class="col-6">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" checked="" id="access_read" name="access_read">
            <label class="form-check-label" for="access_read">Akses Melihat</label>
        </div>
        <small id="access_read-error" class="text-danger"></small>
    </div>
    <div class="col-6">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="access_update" name="access_update">
            <label class="form-check-label" for="access_update">Akses Mengubah</label>
        </div>
        <small id="access_update-error" class="text-danger"></small>
    </div>
    <div class="col-6">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="access_approve" name="access_approve">
            <label class="form-check-label" for="access_approve">Akses Menyetujui</label>
        </div>
        <small id="access_approve-error" class="text-danger"></small>
    </div>
    <div class="col-6">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="access_delete" name="access_delete">
            <label class="form-check-label" for="access_delete">Akses Menghapus</label>
        </div>
        <small id="access_delete-error" class="text-danger"></small>
    </div>
    <div class="col-6">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="access_member" name="access_member">
            <label class="form-check-label" for="access_member">Akses Bawahan</label>
        </div>
        <small id="access_member-error" class="text-danger"></small>
    </div>
</div>