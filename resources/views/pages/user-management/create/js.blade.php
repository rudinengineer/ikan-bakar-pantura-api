<script>
    $(function() {
        // Select2 Init
        $("#store").select2({
            allowClear: true,
            dropdownParent: $('#store').parent(),
            width: '100%',
            ajax: {
                url: '{{ route('store.select2') }}',
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
            placeholder: "Pilih Cabang",
            language: {
                searching: function() {
                    return "Searching...";
                }
            }
        })

        $("#role").select2({
            allowClear: true,
            dropdownParent: $('#role').parent(),
            width: '100%',
            ajax: {
                url: '{{ route('role.select2') }}',
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
            placeholder: "Pilih peran pengguna",
            language: {
                searching: function() {
                    return "Searching...";
                }
            }
        })
    })
</script>