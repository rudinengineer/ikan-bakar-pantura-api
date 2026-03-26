<script>
    $(function() {
        // Select2 Init
        $("#category-edit").select2({
            allowClear: true,
            dropdownParent: $('#category-edit').parent(),
            width: '100%',
            ajax: {
                url: '{{ route('category.select2') }}',
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
            placeholder: "Pilih kategori",
            language: {
                searching: function() {
                    return "Searching...";
                }
            }
        })

        // Default Value Select2
        var optionCategory = new Option('{{ $packet->category->name }}', '{{ $packet->category->id }}', true, true)
        $('#category-edit').append(optionCategory).trigger('change')
    })
</script>