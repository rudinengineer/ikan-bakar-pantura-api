<script>
    $(function() {
        // Select2 Init
        $("#store-edit").select2({
            allowClear: true,
            dropdownParent: $('#store-edit').parent(),
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

        $("#role-edit").select2({
            allowClear: true,
            dropdownParent: $('#role-edit').parent(),
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

        // Default Value Select2
        var optionStore = new Option('{{ $user->store->name ?? '' }}', '{{ $user->store->id ?? '' }}', true, true)
        $('#store-edit').append(optionStore).trigger('change')
        var optionRole = new Option('{{ $user->role->name ?? '' }}', '{{ $user->role->id ?? '' }}', true, true)
        $('#role-edit').append(optionRole).trigger('change')
    })
</script>