{{-- Hidden ID --}}
<input type="hidden" name="hidden_id" value="{{ $category->id }}">

<div class="row">
    <div class="mb-3 text-start">
        <label for="name" class="form-label"><span class="text-danger">*</span>Nama Kategori</label>
        <input name="name" type="text" class="form-control" id="name" placeholder="Contoh: Basic" autocomplete="off" value="{{ $category->name }}">
        <small id="name-edit-error" class="text-danger"></small>
    </div>
</div>