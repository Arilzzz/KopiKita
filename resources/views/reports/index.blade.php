<x-app-layout>

    <div class="container-fluid px-2 px-md-3">

        {{-- Header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
            <div>
                <h2 class="fw-bold mb-1">Laporan Penjualan & Riwayat Struk</h2>
                <p class="text-muted mb-0">
                    Telusuri seluruh riwayat transaksi penjualan kafe dan cetak ulang nota/struk belanja.
                </p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('reports.export-pdf', request()->query()) }}"
                   target="_blank"
                   class="btn btn-outline-danger">
                    <i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak Laporan PDF
                </a>
                <a href="{{ route('pos.index') }}" class="btn btn-dark">
                    <i class="bi bi-plus-lg me-1"></i> Transaksi Baru
                </a>
            </div>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Kartu Ringkasan Laporan --}}
        <div class="row g-3 mb-4">
            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted fw-semibold">Jumlah Transaksi</small>
                                <h3 class="fw-bold text-dark mt-1 mb-0">{{ $jumlahTransaksi }}</h3>
                            </div>
                            <div class="bg-primary-subtle text-primary p-3 rounded-3">
                                <i class="bi bi-receipt fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted fw-semibold">Total Pendapatan Penjualan</small>
                                <h3 class="fw-bold text-success mt-1 mb-0">
                                    Rp{{ number_format($totalPenjualan, 0, ',', '.') }}
                                </h3>
                            </div>
                            <div class="bg-success-subtle text-success p-3 rounded-3">
                                <i class="bi bi-cash-stack fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter & Pencarian --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('reports.index') }}" method="GET" class="row g-3 align-items-end">

                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">Tanggal Mulai</label>
                        <input
                            type="date"
                            name="start_date"
                            class="form-control"
                            value="{{ request('start_date') }}"
                        >
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">Tanggal Akhir</label>
                        <input
                            type="date"
                            name="end_date"
                            class="form-control"
                            value="{{ request('end_date') }}"
                        >
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Cari Transaksi</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                value="{{ request('search') }}"
                                placeholder="Nomor invoice atau nama kasir..."
                            >
                        </div>
                    </div>

                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-dark w-100">
                            Filter
                        </button>
                        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary" title="Reset filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>

                </form>
            </div>
        </div>

        {{-- Tabel Riwayat Transaksi --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Transaksi Penjualan
                </h5>
                <small class="text-muted">
                    Total: {{ $transaksi->total() }} Data
                </small>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4">No</th>
                                <th>No. Invoice</th>
                                <th>Waktu Transaksi</th>
                                <th>Kasir</th>
                                <th>Metode</th>
                                <th>Total Belanja</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($transaksi as $item)
                                <tr>
                                    <td class="px-4 text-muted">
                                        {{ $transaksi->firstItem() + $loop->index }}
                                    </td>
                                    <td class="fw-bold text-dark font-monospace">
                                        {{ $item->invoice_number }}
                                    </td>
                                    <td>
                                        <div>{{ $item->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $item->created_at->format('H:i:s') }} WIB</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <i class="bi bi-person me-1"></i>
                                            {{ $item->user->name ?? 'Admin Kasir' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $item->metode_pembayaran === 'QRIS' ? 'bg-info-subtle text-info' : ($item->metode_pembayaran === 'Debit' ? 'bg-primary-subtle text-primary' : 'bg-success-subtle text-success') }}">
                                            {{ $item->metode_pembayaran ?? 'Tunai' }}
                                        </span>
                                    </td>
                                    <td class="fw-bold text-primary">
                                        Rp{{ number_format($item->totalharga, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('reports.show', $item->id) }}"
                                               class="btn btn-outline-dark"
                                               title="Lihat Pratinjau Struk">
                                                <i class="bi bi-eye me-1"></i> Struk
                                            </a>
                                            <a href="{{ route('reports.pdf', $item->id) }}"
                                               target="_blank"
                                               class="btn btn-outline-danger"
                                               title="Unduh / Cetak PDF Struk">
                                                <i class="bi bi-file-earmark-pdf"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="bi bi-inbox fs-1 text-muted"></i>
                                        <p class="text-muted mt-2 mb-0">
                                            Belum ada data transaksi yang ditemukan.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white border-top py-3">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <small class="text-muted">
                        Menampilkan {{ $transaksi->firstItem() ?? 0 }} - {{ $transaksi->lastItem() ?? 0 }} dari {{ $transaksi->total() }} transaksi
                    </small>
                    {{ $transaksi->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

    </div>

</x-app-layout>
