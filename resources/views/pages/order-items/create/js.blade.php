<script>
    $(function() {
        // Select2 Init
        $("#product_id").select2({
            allowClear: true,
            dropdownParent: $('#product_id').parent(),
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
            placeholder: "Pilih Menu",
            language: {
                searching: function() {
                    return "Searching...";
                }
            }
        })
    })
</script>