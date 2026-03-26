@include('pages.order-items.create.js')

<div class="row">
    <div class="mb-3">
        <label for="product_id" class="form-label"><span class="text-danger">*</span>Menu</label>
        <div>
            <select name="product_id" id="product_id" class="form-control"></select>
        </div>
        <small id="product_id-error" class="text-danger"></small>
    </div>

    <div class="mb-3 text-start">
        <label for="qty" class="form-label"><span class="text-danger">*</span>QTY</label>
        <input name="qty" type="number" class="form-control" id="qty" placeholder="Contoh: 1" autocomplete="off" value="1">
        <small id="qty-error" class="text-danger"></small>
    </div>
</div>