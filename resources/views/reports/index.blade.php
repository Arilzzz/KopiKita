<x-app-layout>

    <div class="container-fluid">

        {{-- Header --}}
        <div class="mb-4">
            <h2 class="fw-bold mb-2">
                Laporan & Detail Struk
            </h2>

            <p class="text-muted mb-0">
                Telusuri transaksi selesai dan cetak kembali struk belanja pelanggan.
            </p>
        </div>


        {{-- Filter Tanggal --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">

                <form action="{{ route('reports.index') }}"
                    method="GET"
                    class="row g-3 align-items-end">

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Tanggal Mulai
                        </label>

                        <input
                            type="date"
                            name="start_date"
                            class="form-control"
                            value="{{ request('start_date') }}"
                        >
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Tanggal Akhir
                        </label>

                        <input
                            type="date"
                            name="end_date"
                            class="form-control"
                            value="{{ request('end_date') }}"
                        >
                    </div>

                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-dark">
                            Terapkan Filter
                        </button>

                        <a href="{{ route('reports.index') }}"
                            class="btn btn-outline-secondary">
                            Atur Ulang
                        </a>
                    </div>

                </form>

            </div>
        </div>


        {{-- Ringkasan Laporan --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-6">
                        <small class="text-muted">
                            Jumlah Transaksi
                        </small>

                        <h5 class="fw-bold mb-0">
                            {{ $jumlahTransaksi }} Transaksi
                        </h5>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted">
                            Total Penjualan
                        </small>

                        <h5 class="fw-bold mb-0">
                            Rp{{ number_format($totalPenjualan, 0, ',', '.') }}
                        </h5>
                    </div>

                </div>

            </div>
        </div>


        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>
            </div>
        @endif


        <div class="row g-4">

            {{-- Riwayat Transaksi --}}
            <div class="col-lg-8">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold mb-0">
                            Riwayat Transaksi
                        </h5>
                    </div>

                    <div class="card-body">

                        {{-- Pencarian --}}
                        <form action="{{ route('reports.index') }}"
                            method="GET"
                            class="mb-3">

                            <input type="hidden"
                                name="start_date"
                                value="{{ request('start_date') }}">

                            <input type="hidden"
                                name="end_date"
                                value="{{ request('end_date') }}">

                            <label class="form-label fw-semibold">
                                Cari Transaksi
                            </label>

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                value="{{ request('search') }}"
                                placeholder="Cari nomor transaksi atau nama kasir..."
                            >

                        </form>


                        {{-- Tabel --}}
                        <div class="table-responsive">

                            <table class="table table-hover align-middle">

                                <thead class="table-light">
                                    <tr>
                                        <th>No. Transaksi</th>
                                        <th>Waktu</th>
                                        <th>Kasir</th>
                                        <th>Total</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @forelse ($transaksi as $item)

                                        <tr>

                                            <td class="fw-semibold">
                                                {{ $item->invoice }}
                                            </td>

                                            <td>
                                                {{ $item->created_at->format('d/m/Y H:i') }}
                                            </td>

                                            <td>
                                                {{ $item->user->name ?? 'Admin' }}
                                            </td>

                                            <td>
                                                Rp{{ number_format($item->total, 0, ',', '.') }}
                                            </td>

                                            <td class="text-center">
                                                <a href="{{ route('reports.show', $item->id) }}"
                                                    class="btn btn-sm btn-outline-dark">
                                                    Detail
                                                </a>
                                            </td>

                                        </tr>

                                    @empty

                                        <tr>
                                            <td colspan="5"
                                                class="text-center py-5">

                                                <i class="bi bi-inbox fs-1 text-muted"></i>

                                                <p class="text-muted mt-2 mb-0">
                                                    Belum ada transaksi.
                                                </p>

                                            </td>
                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>


                        {{-- Pagination --}}
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                            <small class="text-muted">
                                Menampilkan {{ $transaksi->firstItem() ?? 0 }}
                                - {{ $transaksi->lastItem() ?? 0 }}
                                dari {{ $transaksi->total() }} transaksi
                            </small>

                            {{ $transaksi->links('pagination::bootstrap-5') }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- Informasi --}}
            <div class="col-lg-4">

                <div class="card border-0 shadow-sm">

                    <div class="card-body text-center py-5">

                        <i class="bi bi-receipt fs-1 text-muted"></i>

                        <h5 class="fw-bold mt-3">
                            Detail Struk
                        </h5>

                        <p class="text-muted mb-0">
                            Pilih transaksi pada tabel untuk melihat
                            rincian pembelian dan mencetak struk.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
