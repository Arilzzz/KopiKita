<x-app-layout>

    <div class="container-fluid px-2 px-md-3">

        {{-- Header --}}
        <div class="mb-4">
            <h2 class="fw-bold mb-1">Transaksi Kasir (POS)</h2>
            <p class="text-muted mb-0">
                Pilih menu pesanan, sesuaikan jumlah item, dan lakukan proses pembayaran kasir.
            </p>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">

            {{-- Katalog Menu --}}
            <div class="col-lg-7">

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3 p-md-4">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0">
                                <i class="bi bi-grid-fill me-2 text-primary"></i>Katalog Menu
                            </h5>
                            <span class="badge bg-light text-dark border">
                                {{ $DataMenu->count() }} Menu Tersedia
                            </span>
                        </div>

                        {{-- Pencarian dan Filter Kategori --}}
                        <form action="{{ route('pos.index') }}" method="GET" class="mb-3">
                            <div class="row g-2">
                                <div class="col-md-7">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="bi bi-search text-muted"></i>
                                        </span>
                                        <input
                                            type="text"
                                            name="search"
                                            class="form-control border-start-0"
                                            value="{{ request('search') }}"
                                            placeholder="Cari nama menu..."
                                        >
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <select name="category_id" class="form-select" onchange="this.form.submit()">
                                        <option value="">Semua Kategori</option>
                                        @foreach ($categories as $kategori)
                                            <option value="{{ $kategori->id }}"
                                                {{ request('category_id') == $kategori->id ? 'selected' : '' }}>
                                                {{ $kategori->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            @if(request('search') || request('category_id'))
                                <div class="mt-2 d-flex align-items-center gap-2">
                                    <small class="text-muted">Filter aktif:</small>
                                    @if(request('search'))
                                        <span class="badge bg-secondary">"{{ request('search') }}"</span>
                                    @endif
                                    @if(request('category_id'))
                                        <span class="badge bg-secondary">
                                            {{ $categories->firstWhere('id', request('category_id'))->nama_kategori ?? '' }}
                                        </span>
                                    @endif
                                    <a href="{{ route('pos.index') }}" class="btn btn-sm btn-link text-danger p-0 ms-1 text-decoration-none">
                                        <i class="bi bi-x-circle"></i> Reset Filter
                                    </a>
                                </div>
                            @endif
                        </form>

                        {{-- Quick Category Pills --}}
                        <div class="d-flex gap-2 overflow-auto pb-2 mb-3">
                            <a href="{{ route('pos.index', ['search' => request('search')]) }}"
                               class="btn btn-sm {{ !request('category_id') ? 'btn-dark' : 'btn-outline-secondary' }} rounded-pill px-3 text-nowrap">
                                Semua
                            </a>
                            @foreach ($categories as $kategori)
                                <a href="{{ route('pos.index', ['category_id' => $kategori->id, 'search' => request('search')]) }}"
                                   class="btn btn-sm {{ request('category_id') == $kategori->id ? 'btn-dark' : 'btn-outline-secondary' }} rounded-pill px-3 text-nowrap">
                                    {{ $kategori->nama_kategori }}
                                </a>
                            @endforeach
                        </div>

                        {{-- Daftar Kartu Menu --}}
                        <div class="row g-3">
                            @forelse ($DataMenu as $menu)
                                <div class="col-sm-6 col-md-6 col-xl-4">
                                    <div class="card h-100 border rounded-3 shadow-none hover-shadow transition">
                                        <div class="card-body p-3 d-flex flex-column justify-content-between">
                                            <div>
                                                <div class="d-flex justify-content-between align-items-start mb-1">
                                                    <span class="badge bg-light text-secondary border small">
                                                        {{ $menu->kategori->nama_kategori ?? 'Umum' }}
                                                    </span>
                                                    @if($menu->stock > 5)
                                                        <span class="badge bg-success-subtle text-success small">
                                                            Stok: {{ $menu->stock }}
                                                        </span>
                                                    @elseif($menu->stock > 0)
                                                        <span class="badge bg-warning-subtle text-warning small">
                                                            Stok: {{ $menu->stock }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger-subtle text-danger small">
                                                            Habis
                                                        </span>
                                                    @endif
                                                </div>

                                                <h6 class="fw-bold mb-1 mt-2 text-dark text-truncate" title="{{ $menu->nama_menu }}">
                                                    {{ $menu->nama_menu }}
                                                </h6>

                                                <h5 class="fw-bold text-primary mb-3">
                                                    Rp{{ number_format($menu->harga, 0, ',', '.') }}
                                                </h5>
                                            </div>

                                            <form action="{{ route('pos.add') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                                <button type="submit"
                                                    class="btn {{ $menu->stock <= 0 ? 'btn-secondary disabled' : 'btn-dark' }} btn-sm w-100 py-2 rounded-2"
                                                    {{ $menu->stock <= 0 ? 'disabled' : '' }}>
                                                    <i class="bi bi-plus-lg me-1"></i>
                                                    {{ $menu->stock <= 0 ? 'Habis' : 'Tambah' }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="text-center py-5 bg-light rounded">
                                        <i class="bi bi-inbox fs-1 text-muted"></i>
                                        <p class="text-muted mt-2 mb-0">
                                            Menu tidak ditemukan. Silakan gunakan kata kunci lain.
                                        </p>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                    </div>
                </div>

            </div>

            {{-- Keranjang Belanja (Current Order) --}}
            <div class="col-lg-5">

                <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">

                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-cart3 me-2 text-primary"></i>Pesanan Pelanggan
                        </h5>
                        @if (count($cart) > 0)
                            <form action="{{ route('pos.clear') }}" method="POST" onsubmit="return confirm('Kosongkan semua pesanan di keranjang?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2">
                                    <i class="bi bi-trash3 me-1"></i>Reset
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="card-body p-3 p-md-4">

                        @if (count($cart) > 0)

                            {{-- List Item Keranjang --}}
                            <div class="cart-items mb-3" style="max-height: 280px; overflow-y: auto;">
                                @foreach ($cart as $id => $item)
                                    <div class="border rounded p-2 mb-2 bg-light-subtle">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="fw-bold mb-0 text-dark">{{ $item['nama'] }}</h6>
                                                <small class="text-muted">
                                                    @ Rp{{ number_format($item['price'], 0, ',', '.') }}
                                                </small>
                                            </div>

                                            <div class="text-end">
                                                <span class="fw-bold text-dark">
                                                    Rp{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                                </span>
                                                <form action="{{ route('pos.remove', $id) }}" method="POST" class="d-inline ms-2">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm text-danger p-0 border-0" title="Hapus item">
                                                        <i class="bi bi-x-circle-fill"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        {{-- Quantity controls --}}
                                        <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                                            <small class="text-muted">Sisa Stok: {{ $item['stock'] }}</small>

                                            <div class="d-flex align-items-center gap-1">
                                                @if($item['quantity'] > 1)
                                                    <form action="{{ route('pos.update', $id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="quantity" value="{{ $item['quantity'] - 1 }}">
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary px-2 py-0">-</button>
                                                    </form>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-outline-secondary px-2 py-0 disabled">-</button>
                                                @endif

                                                <span class="px-2 fw-semibold">{{ $item['quantity'] }}</span>

                                                @if($item['quantity'] < $item['stock'])
                                                    <form action="{{ route('pos.update', $id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary px-2 py-0">+</button>
                                                    </form>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-outline-secondary px-2 py-0 disabled" title="Stok maksimal">+</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <hr class="my-3">

                            {{-- Ringkasan Tagihan --}}
                            <div class="bg-light p-3 rounded mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Total Tagihan</span>
                                    <h4 class="fw-bold text-primary mb-0" id="display-total">
                                        Rp{{ number_format($total, 0, ',', '.') }}
                                    </h4>
                                </div>
                            </div>

                            {{-- Form Pembayaran --}}
                            <form action="{{ route('pos.checkout') }}" method="POST" id="checkout-form">
                                @csrf

                                {{-- Metode Pembayaran --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small mb-1">Metode Pembayaran</label>
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" class="btn-check" name="metode_pembayaran" id="pay-cash" value="Tunai" checked>
                                        <label class="btn btn-outline-dark btn-sm py-2" for="pay-cash">
                                            <i class="bi bi-cash me-1"></i> Tunai
                                        </label>

                                        <input type="radio" class="btn-check" name="metode_pembayaran" id="pay-qris" value="QRIS">
                                        <label class="btn btn-outline-dark btn-sm py-2" for="pay-qris">
                                            <i class="bi bi-qr-code me-1"></i> QRIS
                                        </label>

                                        <input type="radio" class="btn-check" name="metode_pembayaran" id="pay-debit" value="Debit">
                                        <label class="btn btn-outline-dark btn-sm py-2" for="pay-debit">
                                            <i class="bi bi-credit-card me-1"></i> Debit
                                        </label>
                                    </div>
                                </div>

                                {{-- Quick Cash Buttons --}}
                                <div class="mb-2">
                                    <label class="form-label fw-semibold small mb-1">Pilihan Nominal Cepat</label>
                                    <div class="d-flex flex-wrap gap-1">
                                        <button type="button" class="btn btn-outline-secondary btn-sm py-1 px-2 quick-cash" data-amount="{{ $total }}">
                                            Uang Pas
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm py-1 px-2 quick-cash" data-amount="20000">
                                            20.000
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm py-1 px-2 quick-cash" data-amount="50000">
                                            50.000
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm py-1 px-2 quick-cash" data-amount="100000">
                                            100.000
                                        </button>
                                    </div>
                                </div>

                                {{-- Input Nominal Bayar --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small mb-1">Uang Diterima (Rp) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white">Rp</span>
                                        <input
                                            type="number"
                                            id="paid-input"
                                            name="paid"
                                            class="form-control form-control-lg fw-bold"
                                            min="{{ $total }}"
                                            value="{{ old('paid', $total) }}"
                                            placeholder="Contoh: {{ $total }}"
                                            required
                                        >
                                    </div>
                                    @error('paid')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Realtime Kembalian --}}
                                <div class="d-flex justify-content-between align-items-center p-2 rounded mb-3 bg-white border">
                                    <span class="fw-semibold text-secondary">Kembalian:</span>
                                    <span class="fw-bold fs-5 text-success" id="display-kembalian">
                                        Rp0
                                    </span>
                                </div>

                                {{-- Tombol Bayar --}}
                                <button type="submit" class="btn btn-dark w-100 py-3 fw-bold rounded-2 shadow-sm" id="btn-submit-order">
                                    <i class="bi bi-printer me-2"></i>
                                    Bayar & Cetak Struk
                                </button>
                            </form>

                        @else

                            <div class="text-center py-5">
                                <i class="bi bi-cart-x fs-1 text-muted"></i>
                                <h6 class="fw-bold mt-3 mb-1">Keranjang Masih Kosong</h6>
                                <p class="text-muted small mb-0">
                                    Pilih menu pada katalog di samping untuk menambahkan ke pesanan.
                                </p>
                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Script Realtime Kalkulasi Kembalian & Quick Cash --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const total = {{ (float) $total }};
            const paidInput = document.getElementById('paid-input');
            const displayKembalian = document.getElementById('display-kembalian');
            const quickCashBtns = document.querySelectorAll('.quick-cash');

            function updateKembalian() {
                if (!paidInput) return;
                const paid = parseFloat(paidInput.value) || 0;
                const change = paid - total;

                if (change >= 0) {
                    displayKembalian.innerText = 'Rp' + change.toLocaleString('id-ID');
                    displayKembalian.className = 'fw-bold fs-5 text-success';
                } else {
                    displayKembalian.innerText = '-Rp' + Math.abs(change).toLocaleString('id-ID') + ' (Kurang)';
                    displayKembalian.className = 'fw-bold fs-6 text-danger';
                }
            }

            if (paidInput) {
                paidInput.addEventListener('input', updateKembalian);
                updateKembalian();
            }

            quickCashBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const amount = parseFloat(this.getAttribute('data-amount'));
                    if (paidInput) {
                        paidInput.value = amount;
                        updateKembalian();
                    }
                });
            });
        });
    </script>

</x-app-layout>
