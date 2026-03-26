<script>
    $(function() {
        $('#form').on('submit', function(e) {
            e.preventDefault()
            const form = $(this)

            const formData = new FormData(e.target)
            formData.set('_method', 'POST')
            formData.set('_token', $('meta[name="csrf-token"]').attr('content'))

            $.ajax({
                url: '{{ route('profile.update') }}',
                method: 'POST',
                processData: false,
                contentType: false,
                data: formData,
                dataType: 'json',
                success: () => {
                    form[0].reset()

                    iziToast.show({
                        title: 'Success',
                        message: 'Password berhasil diperbarui',
                        color: 'green',
                        position: 'topRight'
                    })
                },
                error: (e) => {
                    if ( e.status === 400 ) {
                        $('#old_password-error').text(e.responseJSON?.data?.old_password)
                        $('#password-error').text(e.responseJSON?.data?.password)
                        $('#confirm_password-error').text(e.responseJSON?.data?.confirm_password)

                        return
                    }

                    iziToast.show({
                        title: 'Error',
                        message: e.responseJSON.message ?? '',
                        color: 'red',
                        position: 'topRight'
                    })
                },
                beforeSend: () => {
                    $('#btn-submit').prop('disabled', true)
                    $('#btn-submit').find('#loading').removeClass('d-none')
                    $('#btn-submit').find('span').addClass('d-none')

                    $('#old_password-error').text('')
                    $('#password-error').text('')
                    $('#confirm_password-error').text('')
                },
                complete: () => {
                    $('#btn-submit').prop('disabled', false)
                    $('#btn-submit').find('#loading').addClass('d-none')
                    $('#btn-submit').find('span').removeClass('d-none')
                }
            })
        })
    })
</script>