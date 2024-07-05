<x-app-layout>
    @section('title', 'Dashboard Produk')

    <div class="modal fade" id="purchaseForm" tabindex="-1" aria-labelledby="purchaseFormLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="purchaseFormLabel">Beli</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="purchaseForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id_produk" id="id_produk">
                        <div class="mb-3">
                            <label for="nama_produk" class="form-label">Nama Produk</label>
                            <input type="text" class="form-control" id="nama_produk" name="nama_produk" readonly>
                            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 nama_produk-error">
                            </ul>
                        </div>
                        <div class="mb-3">
                            <label for="harga_produk" class="form-label">Harga</label>
                            <input type="text" class="form-control" id="harga_produk" name="harga_produk" readonly>
                            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 harga_produk-error">
                            </ul>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah_barang" class="form-label">Jumlah</label>
                            <input type="number" class="form-control" id="jumlah_barang" name="jumlah_barang" onkeyup="hitungTotal()">
                            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 jumlah_barang-error">
                            </ul>
                        </div>
                        <div class="mb-3">
                            <label for="total_harga" class="form-label">Total Harga</label>
                            <input type="text" class="form-control" id="total_harga" name="total_harga" readonly>
                            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 total_harga-error">
                            </ul>
                        </div>
                        <div class="mb-3">
                            <label for="bank" class="form-label">Bank</label>
                            <select class="form-select" id="bank" name="bank">
                                <option value="" disabled selected>Pilih Bank</option>
                                <option value="BCA">BCA</option>
                                <option value="BRI">BRI</option>
                            </select>
                            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 bank-error">
                        </div>
                        <div class="mb-3">
                            <label for="rekening" class="form-label">Rekening</label>
                            <input type="text" class="form-control" id="rekening" name="rekening" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="bukti_pembayaran" class="form-label">Bukti Pembayaran</label>
                            <input type="file" class="form-control" id="bukti_pembayaran" name="bukti_pembayaran"
                                accept="image/*">
                            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1 bukti_pembayaran-error">
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
    <div class="container">
        <h3 class="mb-3">List Produk{{ $kategori }}</h3>
        <div class="row align-items-stretch">
            @foreach ($mainan as $item)
                <div class="col-md-3">
                    <div class="card mb-3">
                        <img src="{{ asset('storage/products/' . $item->foto) }}" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">{{ $item->nama }}</h5>
                            <p class="card-text">Harga: Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                            @if ($isLogin)
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#purchaseForm"
                                    onclick="purchaseProduct()" 
                                    data-harga="{{ $item->harga }}" data-nama="{{ $item->nama }}" data-id="{{ $item->id }}"
                                    >
                                    Beli
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary">Beli</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @section('styles')
        <style>
            .card {
                overflow: hidden;
            }
            .card .card-img-top{
                height: 150px;
                object-fit: contain;
            }
        </style>
    @endsection

    @section('scripts')
        <script>
            $(document).ready(function() {
                var hargaProduk = 0;
                $('#purchaseForm').on('submit', function(e) {
                    e.preventDefault();
                    $('#purchaseForm').modal('hide');
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

                    let formData = new FormData();
                    formData.append('id_produk', $('#id_produk').val());
                    formData.append('jumlah_barang', $('#jumlah_barang').val());
                    formData.append('bukti_pembayaran', $('#bukti_pembayaran')[0].files[0]);
                    formData.append('_token', $('input[name="_token"]').val());
                    formData.append('bank', $('#bank').val());

                    $.ajax({
                        url: "{{ route('purchase.store') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('input[name="_token"]').val(),
                        },
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
                                window.location.href = "{{ route('purchase.history') }}";
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to add new data',
                            }).then((result) => {
                                $('#purchaseForm').modal('show');
                                let errors = xhr.responseJSON.errors;
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

                                if ('bukti_pembayaran' in errors) {
                                    $('.bukti_pembayaran-error').html('');
                                    errors.bukti_pembayaran.forEach(error => {
                                        $('.bukti_pembayaran-error').append(`<li>${error}</li>`);
                                    });
                                } else {
                                    $('.bukti_pembayaran-error').html('');
                                }

                                if ('jumlah_barang' in errors) {
                                    $('.jumlah_barang-error').html('');
                                    errors.jumlah_barang.forEach(error => {
                                        $('.jumlah_barang-error').append(`<li>${error}</li>`);
                                    });
                                } else {
                                    $('.jumlah_barang-error').html('');
                                }

                                if ('bank' in errors) {
                                    $('.bank-error').html('');
                                    errors.bank.forEach(error => {
                                        $('.bank-error').append(`<li>${error}</li>`);
                                    });
                                } else {
                                    $('.bank-error').html('');
                                }
                            })
                        }
                    });
                })

                $('#bank').on('change', function() {
                    let bank = $('#bank').val();
                    if (bank == 'BCA') {
                        $('#rekening').val('1234567890 a.n. Toy Store');
                    } else {
                        $('#rekening').val('0987654321 a.n. Toy Store');
                    }
                });

                $('#purchaseForm').on('hidden.bs.modal', function() {
                    $('.nama_produk-error').html('');
                    $('.id_kategori-error').html('');
                    $('.harga_produk-error').html('');
                    $('.bukti_pembayaran-error').html('');
                    $('#purchaseForm').trigger('reset');
                });
            });

            function purchaseProduct() {
                let harga = event.target.getAttribute('data-harga');
                let nama = event.target.getAttribute('data-nama');
                let id = event.target.getAttribute('data-id');
                hargaProduk = parseInt(harga);
                $('#harga_produk').val(formatRupiah(harga, true));
                $('#nama_produk').val(nama);
                $('#total_harga').val(formatRupiah(harga, true));
                $('#id_produk').val(id);
            }

            function formatRupiah(angka, prefix) {
                var number_string = angka.replace(/[^,\d]/g, '').toString(),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
            }

            function hitungTotal() {
                let jumlah = $('#jumlah_barang').val();
                let total = hargaProduk * jumlah;
                $('#total_harga').val(formatRupiah(total.toString(), true));
            }
        </script>
    @endsection
</x-app-layout>
