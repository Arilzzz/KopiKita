```blade
<x-app-layout>

    <div class="container-fluid">

        {{-- Header --}}
        <div class="mb-4">
            <h2 class="fw-bold mb-2">
                Dashboard
            </h2>

            <p class="text-muted mb-0">
                Selamat datang di halaman Dashboard Kopi Kita.
            </p>
        </div>


        {{-- Statistik --}}
        <div class="row g-3 mb-4">

            {{-- Total Penjualan --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">

                            <div>
                                <p class="text-muted mb-1">
                                    Total Penjualan Hari Ini
                                </p>

                                <h4 class="fw-bold mb-0">
                                    Rp{{ number_format($totalPenjualanHariIni, 0, ',', '.') }}
                                </h4>
                            </div>

                            <i class="bi bi-cash-stack fs-2 text-success"></i>

                        </div>
                    </div>
                </div>
            </div>


            {{-- Jumlah Menu --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">

                            <div>
                                <p class="text-muted mb-1">
                                    Jumlah Menu
                                </p>

                                <h4 class="fw-bold mb-0">
                                    {{ $jumlahMenu }}
                                </h4>
                            </div>

                            <i class="bi bi-cup-hot fs-2 text-primary"></i>

                        </div>
                    </div>
                </div>
            </div>


            {{-- Jumlah Transaksi --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">

                            <div>
                                <p class="text-muted mb-1">
                                    Transaksi Hari Ini
                                </p>

                                <h4 class="fw-bold mb-0">
                                    {{ $jumlahTransaksiHariIni }}
                                </h4>
                            </div>

                            <i class="bi bi-receipt fs-2 text-warning"></i>

                        </div>
                    </div>
                </div>
            </div>

        </div>


        {{-- Transaksi Terbaru --}}
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="fw-bold mb-0">
                        Transaksi Terbaru
                    </h5>

                    <a href="{{ route('laporan.index') }}"
                        class="btn btn-sm btn-outline-dark">
                        Lihat Semua
                    </a>

                </div>
            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th class="px-4">No</th>
                                <th>Invoice</th>
                                <th>Tanggal</th>
                                <th>Total Pembayaran</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($transaksiTerbaru as $transaksi)

                                <tr>

                                    <td class="px-4">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="fw-semibold">
                                        {{ $transaksi->invoice }}
                                    </td>

                                    <td>
                                        {{ $transaksi->created_at->format('d-m-Y H:i') }}
                                    </td>

                                    <td>
                                        Rp{{ number_format($transaksi->total, 0, ',', '.') }}
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <i class="bi bi-inbox fs-2 text-muted"></i>

                                        <p class="text-muted mb-0 mt-2">
                                            Belum ada transaksi.
                                        </p>
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
