<script>
    $(function() {
        const menuItems = []
        const qty = {}

        // Select2 Init
        $("#menu").select2({
            allowClear: true,
            dropdownParent: $('#menu').parent(),
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
                                id: JSON.stringify(item),
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

        $('#image').on('change', function(e) {
            $('#image-preview').attr('src', URL.createObjectURL(e.target.files[0]))
            $('#image-preview').removeClass('d-none')
        })

        function renderItems() {
            let total = 0
            $('#menu-container').html('')
            menuItems.map((value, index) => {
                total += Number(value.price) * Number(qty[value.id])
                $('#menu-container').append(`
                <tr>
                    <input type="hidden" name="order_items[]" value="${value.id}">
                    <td class="text-center">${index + 1}</td>
                    <td>${value.name}</td>
                    <td class="text-center d-flex justify-content-center gap-2">
                        <button data-id="${value.id}" type="button" class="minus-qty-btn btn btn-sm btn-danger"><i class="ti ti-minus"></i></button>
                        <input data-id="${value.id}" name="qty[${value.id}]" type="text" class="qty-input form-control text-center" style="width:50px;" id="qty-${value.id}" placeholder="Cth: 1" autocomplete="off" value="${qty[value.id]}" min="1">
                        <button data-id="${value.id}" type="button" class="add-qty-btn btn btn-sm btn-primary"><i class="ti ti-plus"></i></button>
                    </td>
                    <td class="text-end">Rp ${new Intl.NumberFormat('id-ID', {
                        'currency': 'IDR'
                    }).format(Number(value.price) * Number(qty[value.id]))}</td>
                </tr>
                `)
            })

            $('#total-text').text(`Rp ${new Intl.NumberFormat('id-ID', {
                'currency': 'IDR'
            }).format(total)}`)
            $('#payment_total').val(new Intl.NumberFormat('id-ID', {
                'currency': 'IDR'
            }).format(total))

            var optionMenu = new Option('', '', true, true)
            $('#menu').append(optionMenu).trigger('change')
        }

        $('#payment_method').on('change', function() {
            let total = 0
            menuItems.map((value) => {
                total += Number(value.price) * Number(qty[value.id])
            })

            if ( $(this).val() === 'full' ) {
                $('#payment_total').val(new Intl.NumberFormat('id-ID', {
                    'currency': 'IDR'
                }).format(total))
            } else if ( $(this).val() === 'dp' ) {
                $('#payment_total').val(new Intl.NumberFormat('id-ID', {
                    'currency': 'IDR'
                }).format(total / 2))
            } else if ( $(this).val() === 'custom' ) {
                $('#payment_total').val(new Intl.NumberFormat('id-ID', {
                    'currency': 'IDR'
                }).format(total / 2))
            }
        })

        $('#payment_total').on('input', function() {
            let value = $(this).val().replace(/[^0-9]/g, '')
            let formatted = new Intl.NumberFormat('id-ID').format(value)

            $(this).val(formatted)
        })

        $('#add-modal').find('.modal-body').on('input', '.qty-input', function() {
            qty[Number($(this).attr('data-id'))] = Number($(this).val())
            renderItems()
        })

        $('#add-modal').find('.modal-body').on('click', '.minus-qty-btn', function() {
            const element = $('#qty-' + $(this).attr('data-id'))
            if ( Number(element.val()) > 1 ) {
                element.val(Number(element.val()) - 1)
                element.trigger('input')
            }
        })

        $('#add-modal').find('.modal-body').on('click', '.add-qty-btn', function() {
            const element = $('#qty-' + $(this).attr('data-id'))
            element.val(Number(element.val()) + 1)
            element.trigger('input')
        })

        $('#menu').on('change', function() {
            if ( $(this).val() ) {
                const data = JSON.parse($(this).val())
                const find = menuItems.find((v) => v.id === data.id)

                if ( find?.id ) {
                    qty[data.id] += 1
                } else {
                    data['qty'] = 1;
                    qty[data.id] = 1;
                    menuItems.push(data)
                }

                renderItems()
            }
        })
    })
</script>