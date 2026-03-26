<script>
    $(function() {
        $('#payment-total').on('input', function() {
            let value = $(this).val().replace(/[^0-9]/g, '')
            let formatted = new Intl.NumberFormat('id-ID').format(value)

            $(this).val(formatted)
        })

        $('#add-menu-btn').on('click', function() {
            $('.modal').modal('hide')
            window.location.href = '#order-items/{{ $order->id }}'
        })

        $('#information-tab').on('click', function() {
            $('.tab-container').addClass('d-none')
            $('.tab-nav-link').removeClass('active')
            $(this).addClass('active')
            $('#information-tab-container').removeClass('d-none')
        })

        $('#payment-tab').on('click', function() {
            $('.tab-container').addClass('d-none')
            $('.tab-nav-link').removeClass('active')
            $(this).addClass('active')
            $('#payment-tab-container').removeClass('d-none')
        })

        $('#menu-tab').on('click', function() {
            $('.tab-container').addClass('d-none')
            $('.tab-nav-link').removeClass('active')
            $(this).addClass('active')
            $('#menu-tab-container').removeClass('d-none')
        })

        /* Update Payment Total */
        $('#payment-total').on('change', function() {
            const element = $(this)
            let paymentTotal = $(this).val().replace(/[^0-9]/g, '')

            $.ajax({
                url: '{{ route('order.update-payment-total', $order->id) }}',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    payment_total: paymentTotal
                },
                beforeSend: () => {
                    element.prop('disabled', true)
                },
                complete: () => {
                    element.prop('disabled', false)
                },
                success: () => {
                    iziToast.show({
                        title: 'Success',
                        message: 'Jumlah dibayar berhasil diperbarui',
                        color: 'green',
                        position: 'topRight'
                    })
                },
                error: () => {
                    iziToast.show({
                        title: 'Error',
                        message: 'Jumlah dibayar gagal diperbarui',
                        color: 'red',
                        position: 'topRight'
                    })
                }
            })
        })
    })
</script>