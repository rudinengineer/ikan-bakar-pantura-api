<div class="row">
    <div class="mb-3 text-start">
        <label for="name" class="form-label"><span class="text-danger">*</span>Nama Menu</label>
        <input name="name" type="text" class="form-control" id="name" placeholder="Cth: Lele Goreng" autocomplete="off">
        <small id="name-error" class="text-danger"></small>
    </div>
    <div class="mb-3 text-start">
        <label for="price" class="form-label"><span class="text-danger">*</span>Harga</label>
        <input type="text" class="form-control" id="price" placeholder="Cth: 12.000" autocomplete="off">
        <input type="hidden" name="price">
        <small id="price-error" class="text-danger"></small>
    </div>
    <div class="mb-3 text-start">
        <label for="image" class="form-label"><span class="text-danger">*</span>Gambar</label>
        <input name="image" type="file" class="form-control" id="image" accept="image/*">
        <img src="" alt="" width="80" height="80" id="image-preview" class="mt-3 d-none">
        <small id="image-error" class="text-danger"></small>
    </div>
</div>

<script>
    $(function() {
        $('#image').on('change', function(e) {
            $('#image-preview').attr('src', URL.createObjectURL(e.target.files[0]))
            $('#image-preview').removeClass('d-none')
        })

        $('#price').on('input', function() {
            let value = $(this).val().replace(/[^0-9]/g, '')
            let formatted = new Intl.NumberFormat('id-ID').format(value)

            $(this).val(formatted)
            $('input[name="price"]').val(value)
        })
    })
</script>