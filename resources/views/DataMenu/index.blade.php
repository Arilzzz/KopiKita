<x-app-layout>

    <div class="container-fluid px-2 px-md-3">

        {{-- Header Halaman --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
            <div>
                <h2 class="fw-bold mb-1">Manajemen Data Menu</h2>
                <p class="text-muted mb-0">
                    Kelola data menu makanan, minuman, dan stok kafe Kopi Kita.
                </p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('kategori.index') }}" class="btn btn-outline-dark">
                    <i class="bi bi-tags me-1"></i> Kelola Kategori
                </a>
                <a href="{{ route('DataMenu.create') }}" class="btn btn-dark">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Menu
                </a>
            </div>
        </div>

        {{-- Ringkasan Menu --}}
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted fw-semibold">Total Menu Terdaftar</small>
                                <h3 class="fw-bold text-dark mt-1 mb-0">{{ $DataMenu->total() }}</h3>
                            </div>
                            <div class="bg-primary-subtle text-primary p-3 rounded-3">
                                <i class="bi bi-cup-hot-fill fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted fw-semibold">Total Kategori</small>
                                <h3 class="fw-bold text-dark mt-1 mb-0">{{ $jumlahKategori }}</h3>
                            </div>
                            <div class="bg-secondary-subtle text-secondary p-3 rounded-3">
                                <i class="bi bi-tags-fill fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter dan Pencarian --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('DataMenu.index') }}" method="GET" class="row g-3">
                    <div class="col-md-7">
                        <label class="form-label fw-semibold small">Cari Nama Menu</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input
                                type="text"
                                name="search"
                                value="{{ request()->query('search', '') }}"
                                class="form-control"
                                placeholder="Cari berdasarkan nama menu..."
                            >
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">Kategori</label>
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

                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-dark w-100">
                            Cari
                        </button>
                        <a href="{{ route('DataMenu.index') }}" class="btn btn-outline-secondary" title="Reset filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </form>
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

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Tabel Data Menu --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Daftar Menu Kopi Kita</h5>
                <small class="text-muted">Total: {{ $DataMenu->total() }} Item</small>
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
                                    <td class="px-4 text-muted">
                                        {{ $DataMenu->firstItem() + $loop->index }}
                                    </td>
                                    <td class="fw-bold text-dark">
                                        {{ $menu->nama_menu }}
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $menu->kategori->nama_kategori ?? 'Tanpa Kategori' }}
                                        </span>
                                    </td>
                                    <td class="fw-bold text-primary">
                                        Rp{{ number_format($menu->harga, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        @if($menu->stock > 5)
                                            <span class="badge bg-success-subtle text-success">
                                                {{ $menu->stock }} pcs
                                            </span>
                                        @elseif($menu->stock > 0)
                                            <span class="badge bg-warning-subtle text-warning">
                                                {{ $menu->stock }} pcs (Menipis)
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">
                                                Habis
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('DataMenu.edit', $menu->id) }}"
                                                class="btn btn-outline-primary"
                                                title="Edit Menu">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                            <form action="{{ route('DataMenu.destroy', $menu->id) }}"
                                                method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus menu {{ $menu->nama_menu }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Hapus Menu">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="bi bi-inbox fs-1 text-muted"></i>
                                        <p class="text-muted mt-2 mb-0">Belum ada data menu.</p>
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
                        Menampilkan {{ $DataMenu->firstItem() ?? 0 }} - {{ $DataMenu->lastItem() ?? 0 }} dari {{ $DataMenu->total() }} menu
                    </small>
                    {{ $DataMenu->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

    </div>

</x-app-layout>
