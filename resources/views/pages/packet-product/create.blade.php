<div class="row">
    <div class="mb-3">
        <label for="product_items" class="form-label"><span class="text-danger">*</span>Pilih Menu</label>
        <div>
            <select name="product_items[]" id="product_items" class="form-control" multiple></select>
        </div>
        <small id="product_items-error" class="text-danger"></small>
    </div>
</div>

<script>
    $(function() {
        // Select2 Init
        $("#product_items").select2({
            allowClear: true,
            dropdownParent: $('#product_items').parent(),
            width: '100%',
            ajax: {
                url: '{{ route('product.select2') }}',
                dataType: "json",
                data: function(e) {
                    return {
                        q: e.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.name
                            }
                        })
                    }
                }
            },
            placeholder: "Pilih menu",
            language: {
                searching: function() {
                    return "Searching...";
                }
            }
        })
    })
</script>