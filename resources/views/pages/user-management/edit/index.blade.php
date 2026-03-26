@include('pages.user-management.edit.js')

{{-- Hidden ID --}}
<input type="hidden" name="hidden_id" value="{{ $user->id }}">

<div class="row">
    <div class="mb-3 col-md-6 text-start">
        <label for="store-edit" class="form-label"><span class="text-start text-danger">*</span>Cabang</label>
        <div>
            <select name="store_id" id="store-edit" class="form-control"></select>
        </div>
        <small id="store_id-edit-error" class="text-danger"></small>
    </div>

    <div class="mb-3 col-md-6 text-start">
        <label for="name-edit" class="form-label"><span class="text-start text-danger">*</span>Nama</label>
        <input name="name" type="text" class="form-control" id="name-edit" placeholder="Contoh: Smiths" autocomplete="off" value="{{ $user->name }}">
        <small id="name-edit-error" class="text-danger"></small>
    </div>

    <div class="mb-3 col-md-6 text-start">
        <label for="username-edit" class="form-label"><span class="text-start text-danger">*</span>Username</label>
        <input name="username" type="text" class="form-control" id="username-edit" placeholder="Contoh: smith01" autocomplete="off" value="{{ $user->username }}">
        <small id="username-edit-error" class="text-danger"></small>
    </div>

    <div class="mb-3 col-md-6 text-start">
        <label for="email-edit" class="form-label"><span class="text-start text-danger">*</span>Email</label>
        <input name="email" type="email" class="form-control" id="email-edit" placeholder="Contoh: smith@gmail.com" autocomplete="off" value="{{ $user->email }}">
        <small id="email-edit-error" class="text-danger"></small>
    </div>

    <div class="mb-3 col-md-6 text-start">
        <label for="phone-edit" class="form-label"><span class="text-start text-danger">*</span>WhatsApp</label>
        <div class="input-group">
            <div class="input-group-text">+62</div>
            <input name="phone" type="number" class="form-control" id="phone-edit" placeholder="Contoh: smith@gmail.com" autocomplete="off" value="{{ $user->phone }}">
        </div>
        <small id="phone-edit-error" class="text-danger"></small>
    </div>

    <div class="mb-3 col-md-6 text-start">
        <label for="role-edit" class="form-label"><span class="text-start text-danger">*</span>Peran Pengguna</label>
        <div>
            <select name="role_id" id="role-edit" class="form-control"></select>
        </div>
        <small id="role_id-edit-error" class="text-danger"></small>
    </div>

    <div class="mb-3 col-md-6 text-start">
        <label for="password-edit" class="form-label">Password</label>
        <input name="password" type="password" class="form-control" id="password-edit" placeholder="***" autocomplete="off">
        <small id="password-edit-error" class="text-danger"></small>
    </div>

    <div class="mb-3 col-md-6 text-start">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="flexCheckChecked" name="is_active" @if (boolval($user->is_active))checked @endif>
            <label class="form-check-label" for="flexCheckChecked" title="Jika pengguna aktif maka pastikan inputan ini tercentang">
                Pengguna Aktif
            </label>
        </div>
        <small id="is_active-edit-error" class="text-danger"></small>
    </div>
</div>