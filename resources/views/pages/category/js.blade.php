<script>
    $(function() {
        const accessUpdate = Boolean({{ check_user_access('category', 'update') }})
        const accessDelete = Boolean({{ check_user_access('category', 'delete') }})

        const table = $('#table').DataTable({
            ajax: {
                url: "{{ route('category.datatables') }}",
                method: 'GET',
            },
            pageLength: 10,
            serverSide: true,
            processing: true,
            scrollX: true,
            order: [
                [0, 'desc']
            ],
            columnDefs: [
            @if (auth()->user()->role->level <= 1)
            {
                targets: [1, 3],
                orderable: false
            },
            @else
            {
                targets: [2],
                orderable: false
            },
            @endif
            {
                targets: [0, 2],
                className: 'text-center'
            }],
            columns: [{
                    data: 'id',
                    render: (data, type, row, meta) => {
                        return `<div class="text-center">${meta.row + meta.settings._iDisplayStart + 1}</div>`
                    }
                },
                @if (auth()->user()->role->level <= 1)
                {
                    data: 'store.name'
                },
                @endif
                {
                    data: 'name',
                },
                {
                    data: 'id',
                    render: (m, t, data) => {
                        return `
                        <div class="d-flex justify-content-center align-items-center gap-1">
                            ${accessUpdate ? `
                            <button title="Edit Data" class="edit-btn btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#edit-modal" data-id="${data.id}">
                                <i class="fas fa-edit"></i>
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

        // Show Edit Modal
        $('#app').on('click', '.edit-btn', function() {
            const id = $(this).attr('data-id')
            const url = '{{ route('category.edit', '-id-') }}/'.replace('-id-', id)
            $(`#edit-modal`).find('.modal-body').load(url)
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
                url: '{{ route('category.update', '-id-') }}/'.replace('-id-', id),
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
    })
</script>