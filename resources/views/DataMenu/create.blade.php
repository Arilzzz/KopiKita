<x-app-layout>

    <div class="container-fluid px-2 px-md-3">

        <div class="mx-auto" style="max-width: 750px;">

            {{-- Header --}}
            <div class="mb-4">
                <a href="{{ route('DataMenu.index') }}" class="text-decoration-none text-secondary small">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Menu
                </a>
                <h2 class="fw-bold text-dark mt-2 mb-1">Tambah Menu Baru</h2>
                <p class="text-muted mb-0">
                    Tambahkan data menu makanan atau minuman baru Kafe Kopi Kita.
                </p>
            </div>

            {{-- Form Tambah Menu --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0">Form Tambah Menu</h5>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('DataMenu.store') }}" method="POST">
                        @csrf

                        {{-- Nama Menu --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Menu <span class="text-danger">*</span></label>
                            <input type="text" name="nama_menu"
                                class="form-control @error('nama_menu') is-invalid @enderror"
                                value="{{ old('nama_menu') }}" placeholder="Contoh: Espresso Romano, Croissant Almond"
                                required>
                            @error('nama_menu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kategori --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror"
                                required>
                                <option value="">-- Pilih Kategori Menu --</option>
                                @foreach ($categories as $kategori)
                                    <option value="{{ $kategori->id }}"
                                        {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Harga --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Harga Satuan (Rp) <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">Rp</span>
                                <input type="number" name="harga"
                                    class="form-control @error('harga') is-invalid @enderror"
                                    value="{{ old('harga') }}" placeholder="Contoh: 18000" min="0" required>
                            </div>
                            @error('harga')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Stok --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Stok Awal (Porsi/Cup) <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="stock"
                                class="form-control @error('stock') is-invalid @enderror"
                                value="{{ old('stock', 10) }}" placeholder="Contoh: 25" min="0" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        {{-- Tombol Aksi --}}
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('DataMenu.index') }}" class="btn btn-outline-secondary px-4">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-dark px-4">
                                <i class="bi bi-save me-1"></i> Simpan Menu
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

    </div>

</x-app-layout>
