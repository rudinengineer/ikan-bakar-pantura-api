{{-- Hidden ID --}}
<input type="hidden" name="hidden_id" value="{{ $product->id }}">

<div class="row">
    <div class="mb-3 text-start">
        <label for="name" class="form-label"><span class="text-danger">*</span>Nama Menu</label>
        <input name="name" type="text" class="form-control" id="name" placeholder="Cth: Lele Goreng" autocomplete="off" value="{{ $product->name }}">
        <small id="name-edit-error" class="text-danger"></small>
    </div>
    <div class="mb-3 text-start">
        <label for="price-edit" class="form-label"><span class="text-danger">*</span>Harga</label>
        <input type="text" class="form-control" id="price-edit" placeholder="Cth: 12.000" autocomplete="off" value="{{ number_format($product->price, 0, '.', '.') }}">
        <input type="hidden" name="price" value="{{ $product->price }}">
        <small id="price-edit-error" class="text-danger"></small>
    </div>
    <div class="mb-3 text-start">
        <label for="image-edit" class="form-label"><span class="text-danger">*</span>Gambar</label>
        <input name="image" type="file" class="form-control" id="image-edit" accept="image/*">
        <img src="{{ asset('uploads/' . $product->image) }}" alt="" width="80" height="80" id="image-preview-edit" class="mt-3">
        <small id="image-edit-error" class="text-danger"></small>
    </div>
</div>

<script>
    $(function() {
        $('#image-edit').on('change', function(e) {
            $('#image-preview-edit').attr('src', URL.createObjectURL(e.target.files[0]))
            $('#image-preview-edit').removeClass('d-none')
        })

        $('#price-edit').on('input', function() {
            let value = $(this).val().replace(/[^0-9]/g, '')
            let formatted = new Intl.NumberFormat('id-ID').format(value)

            $(this).val(formatted)
            $('input[name="price"]').val(value)
        })
    })
</script>