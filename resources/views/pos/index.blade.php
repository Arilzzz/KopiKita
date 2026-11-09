```blade
<x-app-layout>

    <div class="container-fluid">

        {{-- Header --}}
        <div class="mb-4">
            <h2 class="fw-bold mb-2">Transaksi Kasir</h2>
            <p class="text-muted mb-0">
                Pilih menu dan lakukan transaksi pembelian.
            </p>
        </div>


        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif


        <div class="row g-4">

            {{-- Katalog Menu --}}
            <div class="col-lg-7">

                <div class="card border-0 shadow-sm">
                    <div class="card-body">

                        <h5 class="fw-bold mb-3">Katalog Menu</h5>

                        {{-- Pencarian dan Filter --}}
                        <form action="{{ route('pos.index') }}"
                            method="GET"
                            class="row g-2 mb-4">

                            <div class="col-md-7">
                                <input
                                    type="text"
                                    name="search"
                                    class="form-control"
                                    value="{{ request('search') }}"
                                    placeholder="Cari nama menu..."
                                >
                            </div>

                            <div class="col-md-5">
                                <select name="category_id" class="form-select">
                                    <option value="">Semua Kategori</option>

                                    @foreach ($categories as $kategori)
                                        <option value="{{ $kategori->id }}"
                                            {{ request('category_id') == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->nama_kategori }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-dark btn-sm">
                                    <i class="bi bi-search me-1"></i>
                                    Cari Menu
                                </button>

                                <a href="{{ route('pos.index') }}"
                                    class="btn btn-outline-secondary btn-sm">
                                    Reset
                                </a>
                            </div>

                        </form>


                        {{-- Daftar Menu --}}
                        <div class="row g-3">

                            @forelse ($DataMenu as $menu)

                                <div class="col-md-6 col-xl-4">

                                    <div class="card h-100 border">

                                        <div class="card-body">

                                            <h6 class="fw-bold mb-1">
                                                {{ $menu->nama_menu }}
                                            </h6>

                                            <small class="text-muted">
                                                {{ $menu->kategori->nama_kategori ?? 'Tanpa Kategori' }}
                                            </small>

                                            <h6 class="fw-bold text-primary mt-3 mb-1">
                                                Rp{{ number_format($menu->harga, 0, ',', '.') }}
                                            </h6>

                                            <small class="text-muted">
                                                Stok: {{ $menu->stock }}
                                            </small>

                                            <form action="{{ route('pos.add') }}"
                                                method="POST"
                                                class="mt-3">

                                                @csrf

                                                <input type="hidden"
                                                    name="menu_id"
                                                    value="{{ $menu->id }}">

                                                <button type="submit"
                                                    class="btn btn-dark btn-sm w-100"
                                                    {{ $menu->stock <= 0 ? 'disabled' : '' }}>

                                                    <i class="bi bi-plus-lg me-1"></i>
                                                    Tambah

                                                </button>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            @empty

                                <div class="col-12">
                                    <div class="text-center py-5">
                                        <i class="bi bi-inbox fs-1 text-muted"></i>
                                        <p class="text-muted mt-2 mb-0">
                                            Menu tidak ditemukan.
                                        </p>
                                    </div>
                                </div>

                            @endforelse

                        </div>

                    </div>
                </div>

            </div>


            {{-- Keranjang --}}
            <div class="col-lg-5">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-cart3 me-2"></i>
                            Keranjang Belanja
                        </h5>
                    </div>


                    <div class="card-body">

                        @if (count($cart) > 0)

                            @foreach ($cart as $id => $item)

                                <div class="border-bottom pb-3 mb-3">

                                    <div class="d-flex justify-content-between">

                                        <div>
                                            <h6 class="fw-semibold mb-1">
                                                {{ $item['nama'] }}
                                            </h6>

                                            <small class="text-muted">
                                                Rp{{ number_format($item['price'], 0, ',', '.') }}
                                            </small>
                                        </div>

                                        <form action="{{ route('pos.remove', $id) }}"
                                            method="POST">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>

                                        </form>

                                    </div>


                                    <div class="d-flex justify-content-between align-items-center mt-3">

                                        {{-- Ubah Jumlah --}}
                                        <form action="{{ route('pos.update', $id) }}"
                                            method="POST"
                                            class="d-flex align-items-center gap-2">

                                            @csrf
                                            @method('PUT')

                                            <input
                                                type="number"
                                                name="quantity"
                                                class="form-control form-control-sm"
                                                style="width: 75px;"
                                                min="1"
                                                max="{{ $item['stock'] }}"
                                                value="{{ $item['quantity'] }}"
                                            >

                                            <button type="submit"
                                                class="btn btn-sm btn-outline-dark">
                                                Update
                                            </button>

                                        </form>


                                        {{-- Subtotal --}}
                                        <strong>
                                            Rp{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                        </strong>

                                    </div>

                                </div>

                            @endforeach


                            {{-- Total --}}
                            <div class="d-flex justify-content-between mb-3">
                                <h5 class="fw-bold">Total</h5>

                                <h5 class="fw-bold text-primary">
                                    Rp{{ number_format($total, 0, ',', '.') }}
                                </h5>
                            </div>


                            {{-- Checkout --}}
                            <form action="{{ route('pos.checkout') }}"
                                method="POST">

                                @csrf

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        Uang Pembayaran
                                    </label>

                                    <input
                                        type="number"
                                        name="paid"
                                        class="form-control"
                                        min="{{ $total }}"
                                        value="{{ old('paid') }}"
                                        placeholder="Masukkan uang pembayaran"
                                        required
                                    >

                                    @error('paid')
                                        <small class="text-danger">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>

                                <button type="submit"
                                    class="btn btn-success w-100">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Proses Pembayaran
                                </button>

                            </form>

                        @else

                            <div class="text-center py-5">

                                <i class="bi bi-cart-x fs-1 text-muted"></i>

                                <h6 class="fw-semibold mt-3">
                                    Keranjang Masih Kosong
                                </h6>

                                <p class="text-muted mb-0">
                                    Silakan pilih menu terlebih dahulu.
                                </p>

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>

