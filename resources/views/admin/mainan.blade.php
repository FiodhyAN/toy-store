<x-app-layout>
    @section('title', 'Manajemen Produk')

    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_produk" class="form-label">Nama Produk</label>
                            <input type="text" class="form-control" id="nama_produk" name="nama_produk">
                            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 nama_produk-error">
                            </ul>
                        </div>
                        <div class="mb-3">
                            <label for="id_kategori" class="form-label">Kategori</label>
                            <select class="form-select" id="id_kategori" name="id_kategori">
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                @endforeach
                            </select>
                            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 id_kategori-error">
                            </ul>
                        </div>
                        <div class="mb-3">
                            <label for="harga_produk" class="form-label">Harga</label>
                            <input type="number" class="form-control" id="harga_produk" name="harga_produk">
                            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 harga_produk-error">
                            </ul>
                        </div>
                        <div class="mb-3">
                            <label for="foto_produk" class="form-label">Foto</label>
                            <input type="file" class="form-control" id="foto_produk" name="foto_produk"
                                accept="image/*">
                            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 foto_produk-error">
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
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="old_produk_id" id="old_produk_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_produk" class="form-label">Nama Produk</label>
                            <input type="text" class="form-control" id="nama_produk_edit" name="nama_produk"
                                required>
                            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 nama_produk-error">
                            </ul>
                        </div>
                        <div class="mb-3">
                            <label for="id_kategori" class="form-label">Kategori</label>
                            <select class="form-select" id="id_kategori_edit" name="id_kategori" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                @endforeach
                            </select>
                            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 id_kategori-error">
                            </ul>
                        </div>
                        <div class="mb-3">
                            <label for="harga_produk" class="form-label">Harga</label>
                            <input type="number" class="form-control" id="harga_produk_edit" name="harga_produk"
                                required>
                            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 harga_produk-error">
                            </ul>
                        </div>
                        <div class="mb-3">
                            <label for="foto_produk" class="form-label">Foto</label>
                            <input type="file" class="form-control" id="foto_produk_edit" name="foto_produk"
                                accept="image/*">
                            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 foto_produk-error">
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
            Manajemen Produk
            <div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="bx bx-list-plus me-1"></i> Tambah Produk
                </button>
            </div>
        </h5>
        <div class="table-responsive text-nowrap">
            <table class="table datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>harga</th>
                        <th>foto</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($mainans as $mainan)
                        <tr>
                            <td><i class="fab fa-angular fa-lg text-danger me-3"></i>
                                <strong>{{ $loop->iteration }}</strong>
                            </td>
                            <td>{{ $mainan->nama }}</td>
                            <td>
                                {{ $mainan->kategori->nama }}
                            </td>
                            <td>
                                {{ $mainan->harga }}
                            </td>
                            <td>
                                <a href="{{ asset('storage/products/' . $mainan->foto) }}" target="_blank"
                                    class="btn btn-primary"
                                    style="padding: 0.5rem 1rem; font-size: 0.875rem; line-height: 1.5; border-radius: 0.375rem;">
                                    Lihat Gambar
                                </a>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <button type="button" class="dropdown-item edit_btn"
                                            value="{{ $mainan->id }}" data-bs-toggle="modal"
                                            data-bs-target="#editModal"><i class="bx bx-edit-alt me-1"></i>
                                            Edit
                                        </button>
                                        <button type="button" class="dropdown-item delete_btn"
                                            value="{{ $mainan->id }}"><i class="bx bx-trash me-1"></i>
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

                let formData = new FormData(this);
                console.log(formData);

                $.ajax({
                    url: "{{ route('mainan.store') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
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
                            console.log(errors);
                            if ('nama_produk' in errors) {
                                $('.nama_produk-error').html('');
                                errors.nama_produk.forEach(error => {
                                    $('.nama_produk-error').append(`<li>${error}</li>`);
                                });
                            } else {
                                $('.nama_produk-error').html('');
                            }

                            if ('id_kategori' in errors) {
                                $('.id_kategori-error').html('');
                                errors.id_kategori.forEach(error => {
                                    $('.id_kategori-error').append(`<li>${error}</li>`);
                                });
                            } else {
                                $('.id_kategori-error').html('');
                            }

                            if ('harga_produk' in errors) {
                                $('.harga_produk-error').html('');
                                errors.harga_produk.forEach(error => {
                                    $('.harga_produk-error').append(`<li>${error}</li>`);
                                });
                            } else {
                                $('.harga_produk-error').html('');
                            }

                            if ('foto_produk' in errors) {
                                $('.foto_produk-error').html('');
                                errors.foto_produk.forEach(error => {
                                    $('.foto_produk-error').append(`<li>${error}</li>`);
                                });
                            } else {
                                $('.foto_produk-error').html('');
                            }
                        })
                    }
                });
            })

            $('.edit_btn').on('click', function() {
                let id = $(this).val();
                console.log(id);
                $.ajax({
                    url: "{{ route('mainan.edit') }}",
                    data: {
                        id: id
                    },
                    type: "GET",
                    success: function(response) {
                        console.log(response);
                        $('#old_produk_id').val(response.data.id);
                        $('#nama_produk_edit').val(response.data.nama);
                        $('#id_kategori_edit').val(response.data.id_kategori);
                        $('#harga_produk_edit').val(response.data.harga);
                    }
                });
            });

            $('#editForm').on('submit', function(e) {
                e.preventDefault();
                $('#editModal').modal('hide');
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

                let formData = new FormData(this);
                formData.append('_method', 'PUT');
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                $.ajax({
                    url: "{{ route('mainan.update') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
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
                            console.log(xhr);
                            $('#editModal').modal('show');
                            let errors = xhr.responseJSON.errors;
                            console.log(errors);
                            if ('nama_produk' in errors) {
                                $('.nama_produk-error').html('');
                                errors.nama_produk.forEach(error => {
                                    $('.nama_produk-error').append(`<li>${error}</li>`);
                                });
                            } else {
                                $('.nama_produk-error').html('');
                            }

                            if ('id_kategori' in errors) {
                                $('.id_kategori-error').html('');
                                errors.id_kategori.forEach(error => {
                                    $('.id_kategori-error').append(`<li>${error}</li>`);
                                });
                            } else {
                                $('.id_kategori-error').html('');
                            }

                            if ('harga_produk' in errors) {
                                $('.harga_produk-error').html('');
                                errors.harga_produk.forEach(error => {
                                    $('.harga_produk-error').append(`<li>${error}</li>`);
                                });
                            } else {
                                $('.harga_produk-error').html('');
                            }

                            if ('foto_produk' in errors) {
                                $('.foto_produk-error').html('');
                                errors.foto_produk.forEach(error => {
                                    $('.foto_produk-error').append(`<li>${error}</li>`);
                                });
                            } else {
                                $('.foto_produk-error').html('');
                            }
                        })
                    }
                });
            });

            $('.delete_btn').on('click', function() {
                let id = $(this).val();
                console.log(id);
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
                            url: "{{ route('mainan.destroy') }}",
                            type: "DELETE",
                            data: {
                                id: id,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                ).then(function() {
                                    window.location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    'Failed to delete data',
                                    'error'
                                );
                            }
                        });
                    }
                })
            });
        </script>
    @endsection
</x-app-layout>
