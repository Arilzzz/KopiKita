
<x-app-layout>

    <div class="container-fluid px-4 py-4">

        <div class="mx-auto" style="max-width: 800px;">

            <!-- Header -->
            <div class="mb-4">

                <a href="{{ route('kategori.index') }}"
                    class="text-decoration-none text-secondary small">

                    <i class="bi bi-arrow-left me-1"></i>
                    Kembali ke Kategori

                </a>

                <h2 class="fw-bold text-dark mt-3 mb-1">
                    Edit Kategori
                </h2>

                <p class="text-muted mb-0">
                    Perbarui informasi kategori produk.
                </p>

            </div>


            <!-- Form -->
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">
                        Informasi Kategori
                    </h5>
                </div>

                <div class="card-body p-4">

                    <form action="{{ route('kategori.update', $kategori->id) }}"
                        method="POST">

                        @csrf
                        @method('PUT')


                        <!-- Nama Kategori -->
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Nama Kategori
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                name="nama_kategori"
                                value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                                class="form-control @error('nama_kategori') is-invalid @enderror">

                            @error('nama_kategori')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        <!-- Deskripsi -->
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Deskripsi
                            </label>

                            <textarea name="deskripsi"
                                rows="4"
                                class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>

                            @error('deskripsi')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        <hr class="my-4">


                        <!-- Tombol -->
                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('kategori.index') }}"
                                class="btn btn-outline-secondary px-4">
                                Batal
                            </a>

                            <button type="submit"
                                class="btn btn-dark px-4">

                                <i class="bi bi-save me-1"></i>
                                Perbarui Kategori

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
