<!-- Hidden ID -->
<input type="hidden" name="hidden_id" value="{{ $useraccessitem->id }}">

<div class="row">
    <div class="mb-3 text-start">
        <label for="name" class="form-label"><span class="text-danger">*</span>Nama Akses</label>
        <input name="access_name" type="text" class="form-control" id="name" placeholder="Contoh: Beranda" autocomplete="off" required value="{{ $useraccessitem->access_name }}">
        <small id="access_name-edit-error" class="text-danger"></small>
    </div>

    <div class="mb-3 text-start">
        <label for="access_link" class="form-label"><span class="text-danger">*</span>Link Akses</label>
        <input name="access_link" type="text" class="form-control" id="access_link" placeholder="Contoh: home" autocomplete="off" required value="{{ $useraccessitem->access_link }}">
        <div class="form-text">Hanya huruf dan tanda "_" atau "-" yang diperbolehkan!</div>
        <small id="access_link-edit-error" class="text-danger"></small>
    </div>

    <div class="col-6">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="access_menu" name="access_menu" @if(boolval($useraccessitem->access_menu))checked @endif>
            <label class="form-check-label" for="access_menu">Akses Menu</label>
        </div>

        <small id="access_menu-edit-error" class="text-danger"></small>
    </div>
    <div class="col-6">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="access_create" name="access_create" @if(boolval($useraccessitem->access_create))checked @endif>
            <label class="form-check-label" for="access_create">Akses Membuat</label>
        </div>
        <small id="access_create-edit-error" class="text-danger"></small>
    </div>
    <div class="col-6">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="access_read" name="access_read" @if(boolval($useraccessitem->access_read))checked @endif>
            <label class="form-check-label" for="access_read">Akses Melihat</label>
        </div>
        <small id="access_read-edit-error" class="text-danger"></small>
    </div>
    <div class="col-6">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="access_update" name="access_update" @if(boolval($useraccessitem->access_update))checked @endif>
            <label class="form-check-label" for="access_update">Akses Mengubah</label>
        </div>
        <small id="access_update-edit-error" class="text-danger"></small>
    </div>
    <div class="col-6">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="access_approve" name="access_approve" @if(boolval($useraccessitem->access_approve))checked @endif>
            <label class="form-check-label" for="access_approve">Akses Menyetujui</label>
        </div>
        <small id="access_approve-edit-error" class="text-danger"></small>
    </div>
    <div class="col-6">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="access_delete" name="access_delete" @if(boolval($useraccessitem->access_delete))checked @endif>
            <label class="form-check-label" for="access_delete">Akses Menghapus</label>
        </div>
        <small id="access_delete-edit-error" class="text-danger"></small>
    </div>
    <div class="col-6">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="access_member" name="access_member" @if(boolval($useraccessitem->access_member))checked @endif>
            <label class="form-check-label" for="access_member">Akses Bawahan</label>
        </div>
        <small id="access_member-edit-error" class="text-danger"></small>
    </div>
</div>