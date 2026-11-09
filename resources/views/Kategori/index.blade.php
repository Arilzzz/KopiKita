
<x-app-layout>

    <div class="container-fluid px-4 py-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h2 class="fw-bold text-dark mb-1">
                    Manajemen Kategori
                </h2>

                <p class="text-muted mb-0">
                    Kelola kategori makanan dan minuman Kopi Kita.
                </p>
            </div>

            <a href="{{ route('kategori.create') }}"
                class="btn btn-dark px-4 py-2">
                <i class="bi bi-plus-lg me-1"></i>
                Tambah Kategori
            </a>

        </div>


        <!-- Notifikasi -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}

                <button type="button" class="btn-close"
                    data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}

                <button type="button" class="btn-close"
                    data-bs-dismiss="alert"></button>
            </div>
        @endif


        <!-- Ringkasan -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-light rounded p-3">
                        <i class="bi bi-tags fs-3 text-dark"></i>
                    </div>

                    <div>
                        <h4 class="fw-bold mb-0">
                            {{ $kategori->total() }}
                        </h4>

                        <small class="text-muted">
                            Total Kategori Terdaftar
                        </small>
                    </div>
                </div>
            </div>
        </div>


        <!-- Tabel Kategori -->
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">
                    Daftar Kategori
                </h5>
            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th class="px-4">No</th>
                                <th>Nama Kategori</th>
                                <th>Deskripsi</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($kategori as $category)

                                <tr>

                                    <td class="px-4">
                                        {{ $kategori->firstItem() + $loop->index }}
                                    </td>

                                    <td class="fw-semibold">
                                        {{ $category->nama_kategori ?? '-' }}
                                    </td>

                                    <td class="text-muted">
                                        {{ $category->deskripsi ?? '-' }}
                                    </td>

                                    <td class="text-center">

                                        <!-- Edit -->
                                        <a href="{{ route('kategori.edit', $category->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil-square"></i>
                                            Edit
                                        </a>

                                        <!-- Hapus -->
                                        <form
                                            action="{{ route('kategori.destroy', $category->id) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">

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
                                    <td colspan="4" class="text-center py-5">

                                        <i class="bi bi-inbox fs-1 text-muted"></i>

                                        <p class="text-muted mt-2 mb-0">
                                            Belum ada kategori.
                                        </p>

                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            <!-- Pagination -->
            <div class="card-footer bg-white">

                <div class="d-flex justify-content-between align-items-center">

                    <small class="text-muted">
                        Menampilkan {{ $kategori->firstItem() ?? 0 }}
                        - {{ $kategori->lastItem() ?? 0 }}
                        dari {{ $kategori->total() }} kategori
                    </small>

                    {{ $kategori->links('pagination::bootstrap-5') }}

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
