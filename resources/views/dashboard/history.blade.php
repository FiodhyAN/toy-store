<x-app-layout>
    @section('title', 'Histori Transaksi')

    <div class="card">
        <h5 class="card-header d-flex justify-content-between align-items-center">
            Histori Transaksi
        </h5>
        <div class="table-responsive text-nowrap">
            <table class="table datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        @if (auth()->user()->is_admin)
                            <th>Nama Pembeli</th>
                        @endif
                        <th>Nama Produk</th>
                        <th>Harga Produk</th>
                        <th>jumlah</th>
                        <th>Total Harga</th>
                        <th>Bukti Pembayaran</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($transaksi as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            @if (auth()->user()->is_admin)
                                <td>{{ $item->user->name }}</td>
                            @endif
                            <td>{{ $item->mainan->nama }}</td>
                            <td>Rp {{ number_format($item->mainan->harga, 0, ',', '.') }}</td>
                            <td>{{ $item->jumlah }}</td>
                            <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ asset('storage/bukti_pembayaran/' . $item->foto) }}" target="_blank">
                                    Lihat Bukti Pembayaran
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @section('scripts')
        <script>
            $(document).ready(function() {
                const table = $('.datatable').DataTable({
                    paging: true,
                    responsive: true,
                    searching: false,
                    info: false,
                    lengthChange: false,
                    pageLength: 10,
                    sort: false,
                });
            });
        </script>
    @endsection
</x-app-layout>
