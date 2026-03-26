<div class="row">
    <div class="mb-3 col-md-6 text-start">
        <label for="name" class="form-label"><span class="text-danger">*</span>Nama Cabang</label>
        <input name="name" type="text" class="form-control" id="name" placeholder="Cth: Ikan Bakar Pantura" autocomplete="off">
        <small id="name-error" class="text-danger"></small>
    </div>
    <div class="mb-3 col-md-6 text-start">
        <label for="area" class="form-label"><span class="text-danger">*</span>Area</label>
        <input name="area" type="text" class="form-control" id="area" placeholder="Cth: Merakurak" autocomplete="off">
        <small id="area-error" class="text-danger"></small>
    </div>
    <div class="mb-3 col-md-6 text-start">
        <label for="address" class="form-label"><span class="text-danger">*</span>Alamat</label>
        <textarea rows="3" name="address" type="text" class="form-control" id="address" placeholder="Cth: Jln. Soedirman" style="resize: none;"></textarea>
        <small id="address-error" class="text-danger"></small>
    </div>
    <div class="mb-3 col-md-6 text-start">
        <label for="image" class="form-label"><span class="text-danger">*</span>Logo</label>
        <input name="logo" type="file" class="form-control" id="image" accept="image/*">
        <img src="" alt="" width="80" height="80" id="image-preview" class="mt-3 d-none">
        <small id="logo-error" class="text-danger"></small>
    </div>
    <div class="mb-3 col-md-6 text-start">
        <label for="whatsapp" class="form-label"><span class="text-danger">*</span>WhatsApp</label>
        <input name="whatsapp" type="text" class="form-control" id="whatsapp" placeholder="Cth: 628xxx" autocomplete="off">
        <small id="whatsapp-error" class="text-danger"></small>
    </div>
    <div class="mb-3 col-md-6 text-start">
        <label for="bank" class="form-label"><span class="text-danger">*</span>Nama BANK</label>
        <input name="bank" type="text" class="form-control" id="bank" placeholder="Cth: BCA" autocomplete="off">
        <small id="bank-error" class="text-danger"></small>
    </div>
    <div class="mb-3 col-md-6 text-start">
        <label for="account_number" class="form-label"><span class="text-danger">*</span>Nomor Rekening</label>
        <input name="account_number" type="number" class="form-control" id="account_number" placeholder="Cth: 123xxx" autocomplete="off">
        <small id="account_number-error" class="text-danger"></small>
    </div>
    <div class="mb-3 col-md-6 text-start">
        <label for="account_name" class="form-label"><span class="text-danger">*</span>Nama Pemilik Rekening</label>
        <input name="account_name" type="text" class="form-control" id="account_name" placeholder="Cth: Hendra" autocomplete="off">
        <small id="account_name-error" class="text-danger"></small>
    </div>
</div>

<script>
    $(function() {
        $('#image').on('change', function(e) {
            $('#image-preview').attr('src', URL.createObjectURL(e.target.files[0]))
            $('#image-preview').removeClass('d-none')
        })
    })
</script>