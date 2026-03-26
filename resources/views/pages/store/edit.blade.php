{{-- Hidden ID --}}
<input type="hidden" name="hidden_id" value="{{ $store->id }}">

<div class="row">
    <div class="mb-3 col-md-6 text-start">
        <label for="name-edit" class="form-label"><span class="text-danger">*</span>Nama Cabang</label>
        <input name="name" type="text" class="form-control" id="name-edit" placeholder="Cth: Ikan Bakar Pantura" autocomplete="off" value="{{ $store->name }}">
        <small id="name-edit-error" class="text-danger"></small>
    </div>
    <div class="mb-3 col-md-6 text-start">
        <label for="area-edit" class="form-label"><span class="text-danger">*</span>Area</label>
        <input name="area" type="text" class="form-control" id="area-edit" placeholder="Cth: Merakurak" autocomplete="off" value="{{ $store->area }}">
        <small id="area-edit-error" class="text-danger"></small>
    </div>
    <div class="mb-3 col-md-6 text-start">
        <label for="address-edit" class="form-label"><span class="text-danger">*</span>Alamat</label>
        <textarea rows="3" name="address" type="text" class="form-control" id="address-edit" placeholder="Cth: Jln. Soedirman" style="resize: none;">{{ $store->address }}</textarea>
        <small id="address-edit-error" class="text-danger"></small>
    </div>
    <div class="mb-3 col-md-6 text-start">
        <label for="image-edit" class="form-label"><span class="text-danger">*</span>Logo</label>
        <input name="logo" type="file" class="form-control" id="image-edit" accept="image/*">
        <img src="{{ asset('uploads/' . $store->logo) }}" alt="" width="80" height="80" id="image-preview-edit" class="mt-3">
        <small id="logo-edit-error" class="text-danger"></small>
    </div>
    <div class="mb-3 col-md-6 text-start">
        <label for="whatsapp-edit" class="form-label"><span class="text-danger">*</span>WhatsApp</label>
        <input name="whatsapp" type="text" class="form-control" id="whatsapp-edit" placeholder="Cth: 628xxx" autocomplete="off" value="{{ $store->whatsapp }}">
        <small id="whatsapp-edit-error" class="text-danger"></small>
    </div>
    <div class="mb-3 col-md-6 text-start">
        <label for="bank-edit" class="form-label"><span class="text-danger">*</span>Nama BANK</label>
        <input name="bank" type="text" class="form-control" id="bank-edit" placeholder="Cth: BCA" autocomplete="off" value="{{ $store->bank }}">
        <small id="bank-edit-error" class="text-danger"></small>
    </div>
    <div class="mb-3 col-md-6 text-start">
        <label for="account_number-edit" class="form-label"><span class="text-danger">*</span>Nomor Rekening</label>
        <input name="account_number" type="number" class="form-control" id="account_number-edit" placeholder="Cth: 123xxx" autocomplete="off" value="{{ $store->account_number }}">
        <small id="account_number-edit-error" class="text-danger"></small>
    </div>
    <div class="mb-3 col-md-6 text-start">
        <label for="account_name-edit" class="form-label"><span class="text-danger">*</span>Nama Pemilik Rekening</label>
        <input name="account_name" type="text" class="form-control" id="account_name-edit" placeholder="Cth: Hendra" autocomplete="off" value="{{ $store->account_name }}">
        <small id="account_name-edit-error" class="text-danger"></small>
    </div>
</div>

<script>
    $(function() {
        $('#image-edit').on('change', function(e) {
            $('#image-preview-edit').attr('src', URL.createObjectURL(e.target.files[0]))
            $('#image-preview-edit').removeClass('d-none')
        })
    })
</script>