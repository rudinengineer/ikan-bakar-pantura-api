<script>
    $(function() {
        const accessUpdate = Boolean({{ check_user_access('role', 'update') }})
        const accessDelete = Boolean({{ check_user_access('role', 'delete') }})

        const table = $('#table').DataTable({
            ajax: {
                url: "{{ route('user-access.datatables', $role->id) }}",
                method: 'GET'
            },
            pageLength: 10,
            serverSide: true,
            processing: true,
            scrollX: true,
            order: [
                [0, 'desc']
            ],
            columnDefs: [{
                targets: [2, 3, 4, 5, 6, 7, 8, 9],
                orderable: false
            }, {
                targets: [0, 2, 3, 4, 5, 6, 7, 8, 9],
                className: 'text-center'
            }],
            columns: [{
                    data: 'id',
                    render: (data, type, row, meta) => {
                        return `<div class="text-center">${meta.row + meta.settings._iDisplayStart + 1}</div>`
                    }
                },
                {
                    data: 'access_name',
                },
                {
                    data: 'id',
                    render: (m, t, data) => {
                        if (Number(data.access_menu)) {
                            return `
                            <input class="form-check-input change-access-checkbox change-access-checkbox-${data.id}" type="checkbox" data-role="access_menu" data-access-item-id="${data.id}" data-id="${data.user_access?.id ?? ''}" value="" ${Number(data.user_access?.access_menu) && 'checked'}>
                            `
                        } else {
                            return ''
                        }
                    }
                },
                {
                    data: 'id',
                    render: (m, t, data) => {
                        if (Number(data.access_read)) {
                            return `
                            <input class="form-check-input change-access-checkbox change-access-checkbox-${data.id}" type="checkbox" data-role="access_read" data-access-item-id="${data.id}" data-id="${data.user_access?.id ?? ''}" value="" ${Number(data.user_access?.access_read) && 'checked'}>
                            `
                        } else {
                            return ''
                        }
                    }
                },
                {
                    data: 'id',
                    render: (m, t, data) => {
                        if (Number(data.access_create)) {
                            return `
                            <input class="form-check-input change-access-checkbox change-access-checkbox-${data.id}" type="checkbox" data-role="access_create" data-access-item-id="${data.id}" data-id="${data.user_access?.id ?? ''}" value="" ${Number(data.user_access?.access_create) && 'checked'}>
                            `
                        } else {
                            return ''
                        }
                    }
                },
                {
                    data: 'id',
                    render: (m, t, data) => {
                        if (Number(data.access_update)) {
                            return `
                            <input class="form-check-input change-access-checkbox change-access-checkbox-${data.id}" type="checkbox" data-role="access_update" data-access-item-id="${data.id}" data-id="${data.user_access?.id ?? ''}" value="" ${Number(data.user_access?.access_update) && 'checked'}>
                            `
                        } else {
                            return ''
                        }
                    }
                },
                {
                    data: 'id',
                    render: (m, t, data) => {
                        if (Number(data.access_delete)) {
                            return `
                            <input class="form-check-input change-access-checkbox change-access-checkbox-${data.id}" type="checkbox" data-role="access_delete" data-access-item-id="${data.id}" data-id="${data.user_access?.id ?? ''}" value="" ${Number(data.user_access?.access_delete) && 'checked'}>
                            `
                        } else {
                            return ''
                        }
                    }
                },
                {
                    data: 'id',
                    render: (m, t, data) => {
                        if (Number(data.access_approve)) {
                            return `
                        <input class="form-check-input change-access-checkbox change-access-checkbox-${data.id}" type="checkbox" data-id="${data.user_access?.id ?? ''}" data-role="access_approve" data-access-item-id="${data.id}" value="" ${Number(data.user_access?.access_approve) && 'checked'}>
                        `
                        } else {
                            return ''
                        }
                    }
                },
                {
                    data: 'id',
                    render: (m, t, data) => {
                        if (Number(data.access_member)) {
                            return `
                        <input class="form-check-input change-access-checkbox change-access-checkbox-${data.id}" type="checkbox" data-role="access_member" data-id="${data.user_access?.id ?? ''}" data-access-item-id="${data.id}" value="" ${Number(data.user_access?.access_member) && 'checked'}>
                        `
                        } else {
                            return ''
                        }
                    }
                },
                {
                    data: 'id',
                    render: (m, t, data) => {
                        return `
                        <div class="d-flex justify-content-center align-items-center gap-1">
                            ${accessUpdate ? `
                            <button class="btn btn-sm btn-success select-all-btn" data-access-item-id="${data.id}" data-id="${data.user_access?.id ?? ''}">
                                <i class="fas fa-check"></i>
                                <span>Semua</span>
                            </button>

                            <button class="btn btn-sm btn-danger unselect-all-btn" data-access-item-id="${data.id}" data-id="${data.user_access?.id ?? ''}">
                                <i class="fas fa-times"></i>
                                <span>Hapus</span>
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

        $('#app').on('change', '.change-access-checkbox', function() {
            const name = $(this).attr('data-role')
            const id = $(this).attr('data-id')
            const access_item_id = $(this).attr('data-access-item-id')
            const elem = $(this)

            const data = {
                user_access_id: id,
                user_access_item_id: access_item_id,
                _method: 'POST',
                _token: $('meta[name="csrf-token"]').attr('content')
            }

            data[name] = Boolean($(this).is(':checked')) ? 1 : 0

            $.ajax({
                url: '{{ route('user-access.update', $role->id) }}',
                method: 'POST',
                data: data,
                success: () => {
                    iziToast.show({
                        title: 'Success',
                        message: 'Data berhasil disimpan',
                        color: 'green',
                        position: 'topRight'
                    })

                    table.ajax.reload(null, false)
                },
                error: () => {
                    elem.prop('checked', !data[name])

                    iziToast.show({
                        title: 'Error',
                        message: 'Data gagal disimpan',
                        color: 'red',
                        position: 'topRight'
                    })
                },
                beforeSend: () => {
                    elem.prop('checked', data[name] === "active")
                    elem.prop('disabled', true)
                },
                complete: () => {
                    elem.prop('disabled', false)
                }
            })
        })

        $('#app').on('click', '.select-all-btn', function() {
            const access_item_id = $(this).attr('data-access-item-id')
            const user_access_id = $(this).attr('data-id')

            $.ajax({
                url: '{{ route('user-access.update-all', $role->id) }}',
                method: 'POST',
                data: {
                    user_access_item_id: access_item_id,
                    user_access_id: user_access_id,
                    access_value: 1,
                    _method: 'POST',
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: () => {
                    iziToast.show({
                        title: 'Success',
                        message: 'Data berhasil disimpan',
                        color: 'green',
                        position: 'topRight'
                    })

                    table.ajax.reload(null, false)
                },
                error: () => {
                    table.ajax.reload(null, false)

                    iziToast.show({
                        title: 'Error',
                        message: 'Data gagal disimpan',
                        color: 'red',
                        position: 'topRight'
                    })
                },
                beforeSend: () => {
                    $('.change-access-checkbox-' + access_item_id).prop('checked', true)
                    $('.change-access-checkbox-' + access_item_id).prop('disabled', true)
                },
                complete: () => {
                    $('.change-access-checkbox-' + access_item_id).prop('disabled', false)
                }
            })
        })

        $('#app').on('click', '.unselect-all-btn', function() {
            const access_item_id = $(this).attr('data-access-item-id')
            const user_access_id = $(this).attr('data-id')

            $.ajax({
                url: '{{ route('user-access.update-all', $role->id) }}',
                method: 'POST',
                data: {
                    user_access_item_id: access_item_id,
                    user_access_id: user_access_id,
                    access_value: 0,
                    _method: 'POST',
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: () => {
                    iziToast.show({
                        title: 'Success',
                        message: 'Data berhasil disimpan',
                        color: 'green',
                        position: 'topRight'
                    })

                    table.ajax.reload(null, false)
                },
                error: () => {
                    table.ajax.reload(null, false)

                    iziToast.show({
                        title: 'Error',
                        message: 'Data gagal disimpan',
                        color: 'red',
                        position: 'topRight'
                    })
                },
                beforeSend: () => {
                    $('.change-access-checkbox-' + access_item_id).prop('checked', false)
                    $('.change-access-checkbox-' + access_item_id).prop('disabled', true)
                },
                complete: () => {
                    $('.change-access-checkbox-' + access_item_id).prop('disabled', false)
                }
            })
        })
    })
</script>