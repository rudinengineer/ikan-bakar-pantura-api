<script>
    $(function() {
        $('#form').on('submit', function(e) {
            e.preventDefault()

            const formData = new FormData(e.target)
            formData.set('_method', 'POST')
            formData.set('_token', $('meta[name="csrf-token"]').attr('content'))
            formData.set('remember_me', formData.get('remember_me') === 'on' ? 1 : 0)

            $.ajax({
                url: '{{ route('login.store') }}',
                method: 'POST',
                processData: false,
                contentType: false,
                data: formData,
                dataType: 'json',
                success: () => {
                    window.location.href = '{{ url('/') }}'
                },
                error: (e) => {
                    if ( e.status === 400 ) {
                        $('#username-error').text(e.responseJSON?.data?.username)
                        $('#password-error').text(e.responseJSON?.data?.password)

                        return
                    }

                    iziToast.show({
                        title: 'Error',
                        message: e.responseJSON?.message ?? 'Error',
                        color: 'red',
                        position: 'topRight'
                    })
                },
                beforeSend: () => {
                    $('#btn-submit').prop('disabled', true)
                    $('#btn-submit').find('#loading').removeClass('d-none')
                    $('#btn-submit').find('span').addClass('d-none')

                    $('#username-error').text('')
                    $('#password-error').text('')
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