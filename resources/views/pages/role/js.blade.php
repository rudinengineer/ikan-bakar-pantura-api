<script>
    $(function() {
        const accessUpdate = Boolean({{ check_user_access('role', 'update') }})
        const accessDelete = Boolean({{ check_user_access('role', 'delete') }})

        const table = $('#table').DataTable({
            ajax: {
                url: "{{ route('role.datatables') }}",
                method: 'GET',
            },
            pageLength: 10,
            serverSide: true,
            processing: true,
            scrollX: true,
            order: [
                [0, 'desc']
            ],
            columnDefs: [{
                targets: [3],
                orderable: false
            }, {
                targets: [0, 2, 3],
                className: 'text-center'
            }],
            columns: [{
                    data: 'id',
                    render: (data, type, row, meta) => {
                        return `<div class="text-center">${meta.row + meta.settings._iDisplayStart + 1}</div>`
                    }
                },
                {
                    data: 'name',
                },
                {
                    data: 'level',
                },
                {
                    data: 'id',
                    render: (m, t, data) => {
                        return `
                        <div class="d-flex justify-content-center align-items-center gap-1">
                            ${accessUpdate && Number(data.level) !== 1 ? `
                            <a href="{{ url('#user-access') }}/${data.id}">
                                <button class="btn btn-sm btn-success">
                                    <i class="fas fa-lock"></i>
                                    <span>Edit Akses</span>
                                </button>
                            </a>
                            ` : ''}

                            ${accessUpdate && Number(data.level) !== 1 ? `
                            <button title="Edit Data" class="edit-btn btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#edit-modal" data-id="${data.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            ` : ''}
                            
                            ${accessDelete && Number(data.level) !== 1 ? `
                            <button title="Hapus Data" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal-${data.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                            <div id="deleteModal-${data.id}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title mt-0" id="myModalLabel">Are you sure?</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                
                                            </button>
                                        </div>
                                        <div class="modal-body">Data will be deleted!</div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Cancel</button>
                                            <button id="delete-btn-${data.id}" type="button" class="delete-btn btn btn-danger waves-effect waves-light" data-id="${data.id}">
                                                @include('components.loading.button')
                                                <span>Delete Data</span>
                                            </button>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div>
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
            $('#add-modal').find('.modal-body').load('{{ route('role.create') }}')
        })

        // Show Edit Modal
        $('#app').on('click', '.edit-btn', function() {
            const id = $(this).attr('data-id')
            const url = '{{ route('role.edit', '-id-') }}'.replace('-id-', id)
            $(`#edit-modal`).find('.modal-body').load(url)
        })

        // Handle Create Data
        $('#create-form').on('submit', function(e) {
            e.preventDefault()
            const form = $(this)

            const formData = new FormData(e.target)
            formData.set('_method', 'POST')
            formData.set('_token', $('meta[name="csrf-token"]').attr('content'))

            $.ajax({
                url: '{{ route('role.store') }}',
                method: 'POST',
                contentType: false,
                processData: false,
                data: formData,
                dataType: 'json',
                success: () => {
                    form[0].reset()
                    table.ajax.reload(null, false)

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

                        $('#name-error').text(data?.name)
                        $('#level-error').text(data?.level)

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
                    $('#name-error').text('')
                    $('#level-error').text('')
                },
                complete: () => {
                    form.find('#create-btn-submit').prop('disabled', false)
                    form.find('#create-btn-submit').find('#loading').addClass('d-none')
                    form.find('#create-btn-submit').find('span').removeClass('d-none')
                }
            })
        })

        // Handle Edit Data
        $('#edit-form').on('submit', function(e) {
            e.preventDefault()
            const form = $(this)

            const formData = new FormData(e.target)
            const id = formData.get('hidden_id')

            formData.set('_method', 'PUT')
            formData.set('_token', $('meta[name="csrf-token"]').attr('content'))

            $.ajax({
                url: '{{ route('role.update', '-id-') }}'.replace('-id-', id),
                method: 'POST',
                contentType: false,
                processData: false,
                data: formData,
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

                        $('#name-edit-error').text(data?.name)
                        $('#level-edit-error').text(data?.level)

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
                    form.find(`#edit-btn`).prop('disabled', true)
                    form.find(`#edit-btn`).find('#loading').removeClass('d-none')
                    form.find(`#edit-btn`).find('span').addClass('d-none')

                    /* Reset Error */
                    $('#name-edit-error').text('')
                    $('#level-edit-error').text('')
                },
                complete: () => {
                    form.find(`#edit-btn`).prop('disabled', false)
                    form.find(`#edit-btn`).find('#loading').addClass('d-none')
                    form.find(`#edit-btn`).find('span').removeClass('d-none')
                }
            })
        })

        // Handle Delete Data
        $('#app').on('click', '.delete-btn', function() {
            const id = $(this).attr('data-id')

            $.ajax({
                url: '{{ route('role.destroy', '-id-') }}'.replace('-id-', id),
                method: 'POST',
                data: {
                     _method: 'DELETE',
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: () => {
                    table.ajax.reload(null, false)
                    $('.modal').modal('hide')

                    iziToast.show({
                        title: 'Success',
                        message: 'Data berhasil dihapus',
                        color: 'green',
                        position: 'topRight'
                    })
                },
                error: () => {
                    iziToast.show({
                        title: 'Error',
                        message: 'Data gagal dihapus',
                        color: 'red',
                        position: 'topRight'
                    })
                },
                beforeSend: () => {
                    $(`#delete-btn-${id}`).prop('disabled', true)
                    $(`#delete-btn-${id}`).find('#loading').removeClass('d-none')
                    $(`#delete-btn-${id}`).find('span').addClass('d-none')
                },
                complete: () => {
                    $(`#delete-btn-${id}`).prop('disabled', false)
                    $(`#delete-btn-${id}`).find('#loading').addClass('d-none')
                    $(`#delete-btn-${id}`).find('span').removeClass('d-none')
                }
            })
        })
    })
</script>