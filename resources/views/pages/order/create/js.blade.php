<script>
    $(function() {
        // Select2 Init
        $("#category").select2({
            allowClear: true,
            dropdownParent: $('#category').parent(),
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

        $('#image').on('change', function(e) {
            $('#image-preview').attr('src', URL.createObjectURL(e.target.files[0]))
            $('#image-preview').removeClass('d-none')
        })
    })
</script>