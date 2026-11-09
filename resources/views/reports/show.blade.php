<x-app-layout>

    <div class="container-fluid px-2 px-md-3">

        {{-- Header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
            <div>
                <h2 class="fw-bold mb-1">Pratinjau Nota / Struk Belanja</h2>
                <p class="text-muted mb-0">
                    Bukti transaksi resmi kasir Kafe Kopi Kita.
                </p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat
                </a>
                <a href="{{ route('reports.pdf', $transaksi->id) }}" target="_blank" class="btn btn-danger">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Unduh PDF Struk
                </a>
                <a href="{{ route('pos.index') }}" class="btn btn-dark">
                    <i class="bi bi-plus-lg me-1"></i> Transaksi Baru
                </a>
            </div>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mx-auto" style="max-width: 450px;">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Tampilan Struk Kasir --}}
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5 col-xl-4">

                <div class="card border shadow-sm rounded-3 print-area bg-white">
                    <div class="card-body p-4 font-monospace">

                        {{-- Header Kafe --}}
                        <div class="text-center mb-3">
                            <h4 class="fw-bold mb-0 text-dark">KOPI KITA</h4>
                            <small class="text-muted d-block">Sistem Kasir & Manajemen Penjualan</small>
                            <small class="text-muted d-block">Jl. Tunas Harapan Pati</small>
                        </div>

                        <div class="border-top border-dashed my-3" style="border-top: 1px dashed #777 !important;"></div>

                        {{-- Info Transaksi --}}
                        <div class="small">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">No. Invoice:</span>
                                <span class="fw-bold">{{ $transaksi->invoice_number }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Tanggal:</span>
                                <span>{{ $transaksi->created_at->format('d/m/Y H:i:s') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Kasir:</span>
                                <span>{{ $transaksi->user->name ?? 'Admin Kasir' }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Metode Bayar:</span>
                                <span class="badge bg-light text-dark border">{{ $transaksi->metode_pembayaran ?? 'Tunai' }}</span>
                            </div>
                        </div>

                        <div class="border-top border-dashed my-3" style="border-top: 1px dashed #777 !important;"></div>

                        {{-- Item Belanja --}}
                        <div class="small mb-3">
                            <div class="fw-bold text-muted mb-2 text-uppercase" style="font-size: 11px;">Rincian Pesanan</div>

                            @foreach ($transaksi->details as $detail)
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between fw-bold text-dark">
                                        <span>{{ $detail->dataMenu->nama_menu ?? ($detail->menu->nama_menu ?? 'Item Menu') }}</span>
                                        <span>Rp{{ number_format($detail->jumlah * $detail->harga, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="text-muted" style="font-size: 11px;">
                                        {{ $detail->jumlah }} x Rp{{ number_format($detail->harga, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-top border-dashed my-3" style="border-top: 1px dashed #777 !important;"></div>

                        {{-- Kalkulasi Total --}}
                        <div class="small">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Total Belanja:</span>
                                <span class="fw-bold text-dark">Rp{{ number_format($transaksi->totalharga, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Uang Dibayar:</span>
                                <span>Rp{{ number_format($transaksi->uang_bayar, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between fw-bold fs-6 pt-1 text-success border-top">
                                <span>Kembalian:</span>
                                <span>Rp{{ number_format($transaksi->kembalian, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="border-top border-dashed my-3" style="border-top: 1px dashed #777 !important;"></div>

                        {{-- Footer Struk --}}
                        <div class="text-center text-muted small mt-3">
                            <p class="mb-1 fw-semibold">Terima Kasih Atas Kunjungan Anda!</p>
                            <small style="font-size: 10px;">Simpan nota struk ini sebagai bukti pembayaran sah.</small>
                        </div>

                    </div>
                </div>

                {{-- Action Tombol Cetak Langsung Browser --}}
                <div class="d-grid gap-2 mt-3 no-print">
                    <button type="button" class="btn btn-dark py-2" onclick="window.print()">
                        <i class="bi bi-printer-fill me-2"></i> Cetak Struk (Printer Thermal)
                    </button>
                    <a href="{{ route('reports.pdf', $transaksi->id) }}" target="_blank" class="btn btn-outline-danger py-2">
                        <i class="bi bi-download me-2"></i> Simpan File PDF Struk
                    </a>
                </div>

            </div>
        </div>

    </div>

    {{-- Gaya Khusus Print CSS --}}
    <style>
        @media print {
            body * {
                visibility: hidden !important;
            }
            .print-area, .print-area * {
                visibility: visible !important;
            }
            .print-area {
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                width: 100% !important;
                max-width: 80mm !important;
                margin: 0 auto !important;
                border: none !important;
                box-shadow: none !important;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>

</x-app-layout>
