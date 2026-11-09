```blade
<x-app-layout>

    <div class="container-fluid">

        {{-- Header --}}
        <div class="mb-4">
            <h2 class="fw-bold mb-2">
                Edit Menu
            </h2>

            <p class="text-muted mb-0">
                Perbarui informasi menu Kopi Kita.
            </p>
        </div>


        {{-- Form Edit Menu --}}
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">
                    Form Edit Menu
                </h5>
            </div>

            <div class="card-body">

                <form action="{{ route('DataMenu.update', $DataMenu->id) }}"
                    method="POST">

                    @csrf
                    @method('PUT')

                    {{-- Nama Menu --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Nama Menu
                        </label>

                        <input
                            type="text"
                            name="nama_menu"
                            class="form-control"
                            value="{{ old('nama_menu', $DataMenu->nama_menu) }}"
                            placeholder="Masukkan nama menu"
                        >

                        @error('nama_menu')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>


                    {{-- Kategori --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Kategori
                        </label>

                        <select name="kategori_id" class="form-select">

                            <option value="">
                                Pilih Kategori
                            </option>

                            @foreach ($categories as $kategori)
                                <option value="{{ $kategori->id }}"
                                    {{ old('kategori_id', $DataMenu->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach

                        </select>

                        @error('kategori_id')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>


                    {{-- Harga --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Harga
                        </label>

                        <input
                            type="number"
                            name="harga"
                            class="form-control"
                            value="{{ old('harga', $DataMenu->harga) }}"
                            placeholder="Masukkan harga menu"
                        >

                        @error('harga')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>


                    {{-- Stok --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Stok
                        </label>

                        <input
                            type="number"
                            name="stock"
                            class="form-control"
                            value="{{ old('stock', $DataMenu->stock) }}"
                            placeholder="Masukkan jumlah stok"
                        >

                        @error('stock')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>


                    {{-- Tombol --}}
                    <div class="d-flex justify-content-end gap-2 mt-4">

                        <a href="{{ route('DataMenu.index') }}"
                            class="btn btn-outline-secondary">
                            Kembali
                        </a>

                        <button type="submit" class="btn btn-dark">
                            <i class="bi bi-save me-1"></i>
                            Update Menu
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>
