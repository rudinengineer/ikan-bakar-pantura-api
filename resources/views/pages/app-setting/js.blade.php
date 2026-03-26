<script>
    $(function() {
        $('#form').on('submit', function(e) {
            e.preventDefault()

            const formData = new FormData(e.target)
            formData.set('_method', 'POST')
            formData.set('_token', $('meta[name="csrf-token"]').attr('content'))

            $.ajax({
                url: '{{ route('app-setting.update') }}',
                contentType: false,
                processData: false,
                data: formData,
                method: 'POST',
                success: () => {
                    iziToast.show({
                        title: 'Success',
                        message: 'Data berhasil disimpan',
                        color: 'green',
                        position: 'topRight'
                    })
                },
                error: () => {
                    iziToast.show({
                        title: 'Error',
                        message: 'Data gagal disimpan',
                        color: 'red',
                        position: 'topRight'
                    })
                },
                beforeSend: () => {
                    $('#btn-submit').prop('disabled', true)
                    $('#btn-submit').find('#loading').removeClass('d-none')
                    $('#btn-submit').find('span').addClass('d-none')
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