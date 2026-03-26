@include('pages.packet.edit.js')

{{-- Hidden ID --}}
<input type="hidden" name="hidden_id" value="{{ $packet->id }}">

<div class="row">
    <div class="mb-3">
        <label for="category-edit" class="form-label"><span class="text-danger">*</span>Kategori</label>
        <div>
            <select name="category_id" id="category-edit" class="form-control"></select>
        </div>
        <small id="category_id-edit-error" class="text-danger"></small>
    </div>

    <div class="mb-3 text-start">
        <label for="name" class="form-label"><span class="text-danger">*</span>Nama Paket</label>
        <input name="name" type="text" class="form-control" id="name" placeholder="Contoh: Paket 5 Orang" autocomplete="off" value="{{ $packet->name }}">
        <small id="name-edit-error" class="text-danger"></small>
    </div>

    <div class="mb-3 text-start">
        <label for="image" class="form-label"><span class="text-danger">*</span>Gambar</label>
        <input name="image" type="file" class="form-control" id="image" accept="image/*">
        <img src="{{ asset('uploads/' . $packet->image) }}" alt="" width="80" height="80" id="image-preview" class="mt-3">
        <small id="image-edit-error" class="text-danger"></small>
    </div>

    <div class="mb-3 text-start">
        <label for="order_number" class="form-label"><span class="text-danger">*</span>Urutan Ke</label>
        <input name="order_number" type="number" class="form-control" id="order_number" placeholder="Contoh: 1" autocomplete="off" value="{{ $packet->order_number }}">
        <small id="order_number-edit-error" class="text-danger"></small>
    </div>
</div>