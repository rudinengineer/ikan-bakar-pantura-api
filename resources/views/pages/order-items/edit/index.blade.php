@include('pages.order-items.create.js')

<input type="hidden" name="hidden_id" value="{{ $orderitems->id }}">

<div class="row">
    <div class="mb-3 text-start">
        <label for="qty" class="form-label"><span class="text-danger">*</span>QTY</label>
        <input name="qty" type="number" class="form-control" id="qty" placeholder="Contoh: 1" autocomplete="off" value="{{ $orderitems->quantity }}">
        <small id="qty-error" class="text-danger"></small>
    </div>
</div>