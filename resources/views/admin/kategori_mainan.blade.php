<x-app-layout>
    @section('title', 'Manajemen Kategori')

    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 nama-error">
                            </ul>
                        </div>
                        <div class="mb-3">
                            <label for="icon" class="form-label">Icon</label>
                            <input type="text" class="form-control" id="icon" name="icon" required>
                            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 icon-error">
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Tambah Kategori</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editForm">
                    @csrf
                    <input type="hidden" name="old_kategori_id" id="old_kategori_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_kategori" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="nama_kategori_edit" name="nama_kategori">
                            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 nama_kategori-error">
                            </ul>
                        </div>
                        <div class="mb-3">
                            <label for="icon" class="form-label">Icon</label>
                            <input type="text" class="form-control" id="icon_edit" name="icon">
                            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 icon-error">
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card">
        <h5 class="card-header d-flex justify-content-between align-items-center">
            Manajemen Kategori
            <div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="bx bx-list-plus me-1"></i> Tambah Kategori
                </button>
            </div>
        </h5>
        <div class="table-responsive text-nowrap">
            <table class="table datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Icon</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($kategoris as $kategori)
                        <tr>
                            <td><i class="fab fa-angular fa-lg text-danger me-3"></i>
                                <strong>{{ $loop->iteration }}</strong>
                            </td>
                            <td>{{ $kategori->nama }}</td>
                            <td>
                                <i class="bx bx-{{ $kategori->icon }}"></i> {{ $kategori->icon }}
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <button type="button" class="dropdown-item edit_btn"
                                            value="{{ $kategori->id }}" data-bs-toggle="modal"
                                            data-bs-target="#editModal"><i class="bx bx-edit-alt me-1"></i>
                                            Edit
                                        </button>
                                        <button type="button" class="dropdown-item delete_btn"
                                            value="{{ $kategori->id }}"><i class="bx bx-trash me-1"></i>
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @section('scripts')
        <script>
            const table = $('.datatable').DataTable({
                paging: true,
                responsive: true,
                searching: false,
                info: false,
                lengthChange: false,
                pageLength: 10,
                sort: false,
            });

            $('#addForm').on('submit', function(e) {
                e.preventDefault();
                $('#addModal').modal('hide');
                Swal.fire({
                    title: 'Loading',
                    text: 'Adding new data...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    didOpen: () => {
                        Swal.showLoading()
                    },
                });

                $.ajax({
                    url: "{{ route('kategori.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                        }).then(function() {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to add new data',
                        }).then((result) => {
                            console.log(xhr);
                            $('#addModal').modal('show');
                            let errors = xhr.responseJSON.errors;
                            if ('nama' in errors) {
                                $('.nama-error').html('');
                                errors.nama.forEach(error => {
                                    $('.nama-error').append(`<li>${error}</li>`);
                                });
                            } else {
                                $('.nama-error').html('');
                            }

                            if ('icon' in errors) {
                                $('.icon-error').html('');
                                errors.icon.forEach(error => {
                                    $('.icon-error').append(`<li>${error}</li>`);
                                });
                            } else {
                                $('.icon-error').html('');
                            }
                        })
                    }
                });
            })

            $('.edit_btn').click(function() {
                const kategori_id = $(this).val();
                $.ajax({
                    url: "{{ route('kategori.edit') }}",
                    type: "GET",
                    data: {
                        id: kategori_id
                    },
                    success: function(response) {
                        $('#old_kategori_id').val(response.data.id);
                        $('#nama_kategori_edit').val(response.data.nama);
                        $('#icon_edit').val(response.data.icon);
                    },
                    error: function(xhr) {
                        console.log(xhr);
                    }
                });

            })

            $('#editForm').on('submit', function(e) {
                e.preventDefault();
                $('#editModal').modal('hide');
                let old_kategori_id = $('#old_kategori_id').val();
                Swal.fire({
                    title: 'Loading',
                    text: 'Updating data...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    didOpen: () => {
                        Swal.showLoading()
                    },
                });

                $.ajax({
                    url: "{{ route('kategori.update') }}",
                    type: "PUT",
                    data: $(this).serialize() + `&id=${old_kategori_id}`,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                        }).then(function() {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to update data',
                        }).then((result) => {
                            $('#editModal').modal('show');
                            let errors = xhr.responseJSON.errors;
                            if ('nama_kategori' in errors) {
                                $('.nama_kategori-error').html('');
                                errors.nama_kategori.forEach(error => {
                                    $('.nama_kategori-error').append(`<li>${error}</li>`);
                                });
                            } else {
                                $('.nama_kategori-error').html('');
                            }

                            if ('icon' in errors) {
                                $('.icon-error').html('');
                                errors.icon.forEach(error => {
                                    $('.icon-error').append(`<li>${error}</li>`);
                                });
                            } else {
                                $('.icon-error').html('');
                            }
                        })
                    }
                });
            })

            $('.delete_btn').click(function() {
                const kategori_id = $(this).val();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Loading',
                            text: 'Deleting data...',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                            didOpen: () => {
                                Swal.showLoading()
                            },
                        });

                        $.ajax({
                            url: "{{ route('kategori.destroy') }}",
                            type: "DELETE",
                            data: {
                                id: kategori_id,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message,
                                }).then(function() {
                                    window.location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Failed to delete data',
                                });
                            }
                        });
                    }
                })
            })
        </script>
    @endsection
</x-app-layout>
