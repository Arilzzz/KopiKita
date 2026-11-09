```blade
<x-app-layout>

    <div class="container-fluid">

        {{-- Header Halaman --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-2">
                    Manajemen Data Menu
                </h2>

                <p class="text-muted mb-0">
                    Kelola data menu Kopi Kita.
                </p>
            </div>

            <a href="{{ route('DataMenu.create') }}"
                class="btn btn-dark px-4 py-2">
                <i class="bi bi-plus-lg me-1"></i>
                Tambah Menu
            </a>
        </div>


        {{-- Ringkasan Menu --}}
        <div class="row g-3 mb-4">

            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <p class="text-muted mb-1">Total Menu</p>
                        <h3 class="fw-bold mb-0">
                            {{ $DataMenu->total() }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <p class="text-muted mb-1">Total Kategori</p>
                        <h3 class="fw-bold mb-0">
                            {{ $jumlahKategori }}
                        </h3>
                    </div>
                </div>
            </div>

        </div>


        {{-- Filter dan Pencarian --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">

                <form action="{{ route('DataMenu.index') }}"
                    method="GET"
                    class="row g-3">

                    {{-- Pencarian Menu --}}
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">
                            Cari Menu
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request()->query('search', '') }}"
                            class="form-control"
                            placeholder="Cari berdasarkan nama menu..."
                        >
                    </div>

                    {{-- Filter Kategori --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Kategori
                        </label>

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

                    {{-- Tombol Pencarian --}}
                    <div class="col-12">
                        <button type="submit" class="btn btn-dark">
                            <i class="bi bi-search me-1"></i>
                            Cari
                        </button>

                        <a href="{{ route('DataMenu.index') }}"
                            class="btn btn-outline-secondary">
                            Reset
                        </a>
                    </div>

                </form>

            </div>
        </div>


        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-1"></i>
                {{ session('success') }}

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-circle me-1"></i>
                {{ session('error') }}

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>
            </div>
        @endif


        {{-- Tabel Data Menu --}}
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="fw-bold mb-0">
                        Daftar Menu
                    </h5>

                    <a href="{{ route('kategori.index') }}"
                        class="btn btn-sm btn-outline-dark">
                        <i class="bi bi-tags me-1"></i>
                        Kelola Kategori
                    </a>

                </div>
            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th class="px-4">No</th>
                                <th>Nama Menu</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>


                        <tbody>

                            @forelse ($DataMenu as $menu)

                                <tr>

                                    {{-- Nomor --}}
                                    <td class="px-4">
                                        {{ $DataMenu->firstItem() + $loop->index }}
                                    </td>

                                    {{-- Nama Menu --}}
                                    <td class="fw-semibold">
                                        {{ $menu->nama_menu }}
                                    </td>

                                    {{-- Kategori --}}
                                    <td>
                                        {{ $menu->kategori->nama_kategori ?? 'Tanpa Kategori' }}
                                    </td>

                                    {{-- Harga --}}
                                    <td>
                                        Rp{{ number_format($menu->harga, 0, ',', '.') }}
                                    </td>

                                    {{-- Jumlah Stok --}}
                                    <td>
                                        {{ $menu->stock }}
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="text-center">

                                        {{-- Edit --}}
                                        <a href="{{ route('DataMenu.edit', $menu->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil-square"></i>
                                            Edit
                                        </a>

                                        {{-- Hapus --}}
                                        <form
                                            action="{{ route('DataMenu.destroy', $menu->id) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Yakin ingin menghapus menu ini?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                                Hapus
                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="6" class="text-center py-5">

                                        <i class="bi bi-inbox fs-1 text-muted"></i>

                                        <p class="text-muted mt-2 mb-0">
                                            Belum ada data menu.
                                        </p>

                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- Pagination --}}
            <div class="card-footer bg-white">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <small class="text-muted">
                        Menampilkan {{ $DataMenu->firstItem() ?? 0 }}
                        - {{ $DataMenu->lastItem() ?? 0 }}
                        dari {{ $DataMenu->total() }} menu
                    </small>

                    {{ $DataMenu->links('pagination::bootstrap-5') }}

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
