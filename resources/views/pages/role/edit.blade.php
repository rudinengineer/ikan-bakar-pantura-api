{{-- Hidden ID --}}
<input type="hidden" name="hidden_id" value="{{ $role->id }}">

<div class="row">
    <div class="mb-3 text-start">
        <label for="name" class="form-label"><span class="text-danger">*</span>Nama Hak Akses</label>
        <input name="name" type="text" class="form-control" id="name" placeholder="Contoh: Admin" autocomplete="off" value="{{ $role->name }}">
        <small id="name-edit-error" class="text-danger"></small>
    </div>

    <div class="mb-3 text-start">
        <label for="level" class="form-label"><span class="text-danger">*</span>Tingkatan Peran</label>
        <input name="level" type="number" class="form-control" id="level" placeholder="Diisi dengan angka" autocomplete="off" value="{{ $role->level }}">
        <div class="form-text">Tingkatan peran yang tampil adalah sudah sesuai dengan data di server, namu anda dapat merubah sesui kebutuhan.</div>
        <small id="level-edit-error" class="text-danger"></small>
    </div>
</div>