<script>
    $(function() {
        const accessUpdate = Boolean({{ check_user_access('order', 'update') }})
        const accessDelete = Boolean({{ check_user_access('order', 'delete') }})

        const table = $('#table').DataTable({
            ajax: {
                url: "{{ route('order.datatables') }}",
                method: 'GET'
            },
            pageLength: 10,
            serverSide: true,
            processing: true,
            scrollX: true,
            order: [
                [0, 'desc']
            ],
            columnDefs: [
            @if (auth()->user()->role->level >= 1)
            {
                targets: [1, 2, 3, 8, 9],
                orderable: false
            },
            @else
            {
                targets: [1, 2, 7, 8],
                orderable: false
            },
            @endif
            {
                targets: [0, 3, 4, 5, 6, 7, 8],
                className: 'text-center'
            }],
            columns: [{
                    data: 'id',
                    render: (data, type, row, meta) => {
                        return `<div class="text-center">${meta.row + meta.settings._iDisplayStart + 1}</div>`
                    }
                },
                {
                    data: 'order_id',
                },
                @if (auth()->user()->role->level >= 1)
                {
                    data: 'store.name'
                },
                @endif
                {
                    data: 'customer_name',
                    render: (m,t,data) => {
                        return `
                        <div class="text-start">
                            <div><b>${data.customer_name}</b></div>
                            <span>${data.customer_phone}</span>
                        </div>
                        `
                    }
                },
                {
                    data: 'booking_date',
                },
                {
                    data: 'booking_time',
                },
                {
                    data: 'customer_total',
                },
                {
                    data: 'total',
                    render: (m,t,data) => {
                        return `Rp ${new Intl.NumberFormat('id-ID', {currency: 'IDR'}).format(data.total)}`
                    }
                },
                {
                    data: 'status',
                    render: (m,t,data) => {
                        if ( data.status === 'completed' ) {
                            return `
                            <span class="badge bg-success">Selesai</span>
                            `
                        } else if ( data.status === 'confirmed' ) {
                            return `
                            <span class="badge bg-success">Dikonfirmasi</span>
                            `
                        } else if ( data.status === 'canceled' ) {
                            return `
                            <span class="badge bg-danger">Dibatalkan</span>
                            `
                        } else {
                            return `
                            <span class="badge bg-warning">Diproses</span>
                            `
                        }
                    }
                },
                {
                    data: 'id',
                    render: (m, t, data) => {
                        return `
                        <div class="d-flex justify-content-center align-items-center gap-1">
                            ${accessUpdate ? `
                            <button data-id="${data.id}" title="Detail Pesanan" class="edit-btn btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#edit-modal">
                                <i class="fas fa-eye me-1"></i>
                                <span>Detail</span>
                            </button>
                            ` : ''}
                        </div>
                        `
                    }
                }
            ]
        })

        $('#refresh-btn').on('click', function() {
            table.ajax.reload(null, false)
        })

        // Show Create Modal
        $('#create-btn').on('click', function() {
            $('#add-modal').find('.modal-body').off().find('*').off()
            $('#add-modal').find('.modal-body').load('{{ route('order.create') }}')
        })

        // Show Edit Modal
        $('#app').on('click', '.edit-btn', function() {
            const id = $(this).attr('data-id')
            $('#confirm-btn').attr('data-id', id)

            const url = '{{ route('order.show', '-id-') }}'.replace('-id-', id)
            $(`#edit-modal`).find('.modal-body').load(url)
        })

        // Handle Create Data
        $('#create-form').on('submit', function(e) {
            e.preventDefault()
            const form = $(this)

            const formData = new FormData(e.target)
            formData.set('_method', 'POST')
            formData.set('_token', $('meta[name="csrf-token"]').attr('content'))
            formData.set('payment_total', formData.get('payment_total').replace(/[^0-9]/g, ''))

            $.ajax({
                url: '{{ route('order.store') }}',
                method: 'POST',
                contentType: false,
                processData: false,
                data: formData,
                dataType: 'json',
                success: () => {
                    form[0].reset()
                    table.ajax.reload(null, false)
                    $('.modal').modal('hide')

                    iziToast.show({
                        title: 'Success',
                        message: 'Data berhasil disimpan',
                        color: 'green',
                        position: 'topRight'
                    })
                },
                error: (error) => {
                    /* Show Input error */
                    if ( error.status === 400 ) {
                        const data = error.responseJSON?.data

                        $('#customer_name-error').text(data?.customer_name)
                        $('#customer_phone-error').text(data?.customer_phone)
                        $('#note-error').text(data?.note)
                        $('#booking_date-error').text(data?.booking_date)
                        $('#customer_total-error').text(data?.customer_total)
                        $('#customer_seat-error').text(data?.customer_seat)
                        $('#order_items-error').text(data?.order_items)
                        $('#payment_method-error').text(data?.payment_method)
                        $('#payment_total-error').text(data?.payment_total)
                        $('#image-error').text(data?.image)

                        return
                    }

                    iziToast.show({
                        title: 'Error',
                        message: 'Data gagal disimpan',
                        color: 'red',
                        position: 'topRight'
                    })
                },
                beforeSend: () => {
                    form.find('#create-btn-submit').prop('disabled', true)
                    form.find('#create-btn-submit').find('#loading').removeClass('d-none')
                    form.find('#create-btn-submit').find('span').addClass('d-none')

                    /* Reset Error */
                    $('#customer_name-error').text('')
                    $('#customer_phone-error').text('')
                    $('#note-error').text('')
                    $('#booking_date-error').text('')
                    $('#customer_total-error').text('')
                    $('#customer_seat-error').text('')
                    $('#order_items-error').text('')
                    $('#payment_method-error').text('')
                    $('#payment_total-error').text('')
                    $('#image-error').text('')
                },
                complete: () => {
                    form.find('#create-btn-submit').prop('disabled', false)
                    form.find('#create-btn-submit').find('#loading').addClass('d-none')
                    form.find('#create-btn-submit').find('span').removeClass('d-none')
                }
            })
        })

        // Handle Edit Data
        $('#confirm-btn').on('click', function() {
            const id = $(this).attr('data-id')

            $.ajax({
                url: '{{ route('order.confirm-order', '-id-') }}'.replace('-id-', id),
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: () => {
                    table.ajax.reload(null, false)
                    $('.modal').modal('hide')

                    iziToast.show({
                        title: 'Success',
                        message: 'Pesanan berhasil dikonfirmasi',
                        color: 'green',
                        position: 'topRight'
                    })
                },
                error: (error) => {
                    iziToast.show({
                        title: 'Error',
                        message: 'Pesanan gagal dikonfirmas',
                        color: 'red',
                        position: 'topRight'
                    })
                },
                beforeSend: () => {
                    $(`#confirm-btn`).prop('disabled', true)
                    $(`#confirm-btn`).find('#loading').removeClass('d-none')
                    $(`#confirm-btn`).find('span').addClass('d-none')
                },
                complete: () => {
                    $(`#confirm-btn`).prop('disabled', false)
                    $(`#confirm-btn`).find('#loading').addClass('d-none')
                    $(`#confirm-btn`).find('span').removeClass('d-none')
                }
            })
        })
    })
</script>