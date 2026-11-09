<x-app-layout>

    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h2 class="fw-bold mb-2">
                    Detail Struk
                </h2>

                <p class="text-muted mb-0">
                    Rincian transaksi Kopi Kita.
                </p>
            </div>

            <div class="d-flex gap-2">

                <a href="{{ route('reports.index') }}"
                    class="btn btn-outline-secondary">
                    Kembali
                </a>

                <a href="{{ route('reports.pdf', $transaksi->id) }}"
                    class="btn btn-dark">
                    <i class="bi bi-file-earmark-pdf me-1"></i>
                    Unduh PDF
                </a>

            </div>

        </div>


        <div class="row justify-content-center">

            <div class="col-lg-6">

                <div class="card border-0 shadow-sm">

                    <div class="card-body p-4">

                        {{-- Identitas Toko --}}
                        <div class="text-center mb-4">

                            <h3 class="fw-bold mb-1">
                                KOPI KITA
                            </h3>

                            <small class="text-muted">
                                Kasir & Manajemen Penjualan
                            </small>

                        </div>


                        <hr>


                        {{-- Informasi Transaksi --}}
                        <div class="d-flex justify-content-between mb-2">
                            <span>No. Transaksi</span>

                            <strong>
                                {{ $transaksi->invoice }}
                            </strong>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Tanggal</span>

                            <span>
                                {{ $transaksi->created_at->format('d/m/Y H:i') }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span>Kasir</span>

                            <span>
                                {{ $transaksi->user->name ?? 'Admin' }}
                            </span>
                        </div>


                        <hr>


                        {{-- Daftar Menu --}}
                        <h6 class="fw-bold mb-3">
                            Detail Pembelian
                        </h6>

                        @foreach ($transaksi->details as $detail)

                            <div class="mb-3">

                                <div class="d-flex justify-content-between">

                                    <strong>
                                        {{ $detail->menu->nama ?? 'Menu Dihapus' }}
                                    </strong>

                                    <strong>
                                        Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </strong>

                                </div>

                                <small class="text-muted">
                                    {{ $detail->quantity }} x
                                    Rp{{ number_format($detail->price, 0, ',', '.') }}
                                </small>

                            </div>

                        @endforeach


                        <hr>


                        {{-- Total Pembayaran --}}
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Belanja</span>

                            <span>
                                Rp{{ number_format($transaksi->total, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Uang Dibayar</span>

                            <span>
                                Rp{{ number_format($transaksi->paid, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Kembalian</span>

                            <span>
                                Rp{{ number_format($transaksi->change, 0, ',', '.') }}
                            </span>
                        </div>


                        <hr>


                        <div class="text-center mt-4">

                            <p class="mb-1">
                                Terima kasih atas kunjungan Anda.
                            </p>

                            <small class="text-muted">
                                Simpan struk sebagai bukti belanja.
                            </small>

                        </div>

                    </div>

                </div>


                {{-- Tombol Cetak --}}
                <button onclick="window.print()"
                    class="btn btn-dark w-100 mt-3">
                    <i class="bi bi-printer me-1"></i>
                    Cetak Struk
                </button>

            </div>

        </div>

    </div>


    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .card,
            .card * {
                visibility: visible;
            }

            .card {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                box-shadow: none !important;
            }

            button,
            a {
                display: none !important;
            }
        }
    </style>

</x-app-layout>
