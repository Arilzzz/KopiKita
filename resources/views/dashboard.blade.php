<x-app-layout>

    <div class="container-fluid px-2 px-md-3">

        {{-- Header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
            <div>
                <h2 class="fw-bold mb-1">Dashboard</h2>
                <p class="text-muted mb-0">
                    Ringkasan performa penjualan dan aktivitas kasir Kafe Kopi Kita.
                </p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('pos.index') }}" class="btn btn-dark">
                    <i class="bi bi-cart-plus me-1"></i> Buka Kasir (POS)
                </a>
            </div>
        </div>

        {{-- Statistik Cards --}}
        <div class="row g-3 mb-4">

            {{-- Total Penjualan Hari Ini --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted fw-semibold">Total Penjualan Hari Ini</small>
                                <h3 class="fw-bold text-success mt-1 mb-0">
                                    Rp{{ number_format($totalPenjualanHariIni, 0, ',', '.') }}
                                </h3>
                            </div>
                            <div class="bg-success-subtle text-success p-3 rounded-3">
                                <i class="bi bi-cash-stack fs-3"></i>
                            </div>
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
                                <small class="text-muted fw-semibold">Jumlah Menu Terdaftar</small>
                                <h3 class="fw-bold text-primary mt-1 mb-0">
                                    {{ $jumlahMenu }}
                                </h3>
                            </div>
                            <div class="bg-primary-subtle text-primary p-3 rounded-3">
                                <i class="bi bi-cup-hot-fill fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Jumlah Transaksi Hari Ini --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted fw-semibold">Transaksi Hari Ini</small>
                                <h3 class="fw-bold text-warning mt-1 mb-0">
                                    {{ $jumlahTransaksiHariIni }}
                                </h3>
                            </div>
                            <div class="bg-warning-subtle text-warning p-3 rounded-3">
                                <i class="bi bi-receipt fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Grafik Penjualan (Chart.js) --}}
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-bar-chart-line-fill me-2 text-primary"></i>Tren Penjualan 7 Hari Terakhir
                        </h5>
                        <span class="badge bg-light text-dark border">
                            <i class="bi bi-graph-up me-1 text-success"></i>Statistik Realtime
                        </span>
                    </div>
                    <div class="card-body p-3 p-md-4">
                        <div style="height: 280px; position: relative;">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Transaksi Terbaru --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-clock-history me-2 text-primary"></i>5 Transaksi Terbaru
                </h5>
                <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-dark">
                    Lihat Semua Laporan <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4">No</th>
                                <th>No. Invoice</th>
                                <th>Waktu</th>
                                <th>Kasir</th>
                                <th>Metode</th>
                                <th>Total Pembayaran</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($transaksiTerbaru as $transaksi)
                                <tr>
                                    <td class="px-4 text-muted">{{ $loop->iteration }}</td>
                                    <td class="fw-bold font-monospace text-dark">
                                        {{ $transaksi->invoice_number }}
                                    </td>
                                    <td>
                                        {{ $transaksi->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $transaksi->user->name ?? 'Kasir' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-secondary">
                                            {{ $transaksi->metode_pembayaran ?? 'Tunai' }}
                                        </span>
                                    </td>
                                    <td class="fw-bold text-primary">
                                        Rp{{ number_format($transaksi->totalharga, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('reports.show', $transaksi->id) }}" class="btn btn-sm btn-outline-dark">
                                            <i class="bi bi-receipt me-1"></i> Struk
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="bi bi-inbox fs-2 text-muted"></i>
                                        <p class="text-muted mb-0 mt-2">
                                            Belum ada transaksi tercatat.
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

    {{-- Integrasi Library Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('salesChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($chartLabels),
                        datasets: [{
                            label: 'Total Penjualan (Rp)',
                            data: @json($chartTotals),
                            borderColor: '#0d6efd',
                            backgroundColor: 'rgba(13, 110, 253, 0.12)',
                            fill: true,
                            tension: 0.35,
                            borderWidth: 2,
                            pointBackgroundColor: '#0d6efd',
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Rp' + context.raw.toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp' + value.toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>

</x-app-layout>
