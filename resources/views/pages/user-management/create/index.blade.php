@include('pages.user-management.create.js')

<div class="row">
    <div class="mb-3 col-md-6">
        <label for="store" class="form-label"><span class="text-danger">*</span>Cabang</label>
        <div>
            <select name="store_id" id="store" class="form-control"></select>
        </div>
        <small id="store_id-error" class="text-danger"></small>
    </div>

    <div class="mb-3 col-md-6 text-start">
        <label for="name" class="form-label"><span class="text-danger">*</span>Nama</label>
        <input name="name" type="text" class="form-control" id="name" placeholder="Contoh: Smiths" autocomplete="off">
        <small id="name-error" class="text-danger"></small>
    </div>

    <div class="mb-3 col-md-6 text-start">
        <label for="username" class="form-label"><span class="text-danger">*</span>Username</label>
        <input name="username" type="text" class="form-control" id="username" placeholder="Contoh: smith01" autocomplete="off">
        <small id="username-error" class="text-danger"></small>
    </div>

    <div class="mb-3 col-md-6 text-start">
        <label for="email" class="form-label"><span class="text-danger">*</span>Email</label>
        <input name="email" type="email" class="form-control" id="email" placeholder="Contoh: smith@gmail.com" autocomplete="off">
        <small id="email-error" class="text-danger"></small>
    </div>

    <div class="mb-3 col-md-6 text-start">
        <label for="phone" class="form-label"><span class="text-danger">*</span>WhatsApp</label>
        <div class="input-group">
            <div class="input-group-text">+62</div>
            <input name="phone" type="number" class="form-control" id="phone" placeholder="Contoh: smith@gmail.com" autocomplete="off">
        </div>
        <small id="phone-error" class="text-danger"></small>
    </div>

    <div class="mb-3 col-md-6">
        <label for="role" class="form-label"><span class="text-danger">*</span>Peran Pengguna</label>
        <div>
            <select name="role_id" id="role" class="form-control"></select>
        </div>
        <small id="role_id-error" class="text-danger"></small>
    </div>

    <div class="mb-3 col-md-6">
        <label for="password" class="form-label"><span class="text-danger">*</span>Password</label>
        <input name="password" type="password" class="form-control" id="password" placeholder="***" autocomplete="off">
        <small id="password-error" class="text-danger"></small>
    </div>
</div>