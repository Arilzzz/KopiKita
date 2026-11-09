<?php

namespace App\Http\Controllers;

use App\Models\DataMenu;
use App\Models\Kategori;
use Illuminate\Http\Request;

class DataMenuController extends Controller
{
    // 1. Menampilkan Data Menu
    public function index(Request $request)
    {
        $DataMenu = DataMenu::with('kategori')
            ->when($request->search, function ($query, $search) {
                $query->where('nama_menu', 'like', "%{$search}%");
            })
            ->when($request->category_id, function ($query, $categoryId) {
                $query->where('kategori_id', $categoryId);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = Kategori::all();

        // Menghitung jumlah kategori menu
        $jumlahKategori = Kategori::count();

        $jumlahMenu = DataMenu::count();

        return view('DataMenu.index', compact(
            'DataMenu',
            'categories',
            'jumlahKategori',
            'jumlahMenu'
        ));
    }


    // 2. Menampilkan Form Tambah Menu
    public function create()
    {
        $categories = Kategori::all();

        return view('DataMenu.create', compact('categories'));
    }


    // 3. Menyimpan Menu Baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:100',
            'kategori_id' => 'required|exists:kategoris,id',
            'harga' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        DataMenu::create([
            'nama_menu' => $request->nama_menu,
            'kategori_id' => $request->kategori_id,
            'harga' => $request->harga,
            'stock' => $request->stock,
        ]);

        return redirect()
            ->route('DataMenu.index')
            ->with('success', 'Menu berhasil ditambahkan.');
    }


    // 4. Menampilkan Form Edit Menu
    public function edit(DataMenu $DataMenu)
    {
        $categories = Kategori::all();

        return view('DataMenu.edit', compact(
            'DataMenu',
            'categories'
        ));
    }


    // 5. Memperbarui Menu
    public function update(Request $request, DataMenu $DataMenu)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:100',
            'kategori_id' => 'required|exists:kategoris,id',
            'harga' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $DataMenu->update([
            'nama_menu' => $request->nama_menu,
            'kategori_id' => $request->kategori_id,
            'harga' => $request->harga,
            'stock' => $request->stock,
        ]);

        return redirect()
            ->route('DataMenu.index')
            ->with('success', 'Menu berhasil diperbarui.');
    }


    // 6. Menghapus Menu
    public function destroy(DataMenu $menu)
    {
        $menu->delete();

        return redirect()
            ->route('DataMenu.index')
            ->with('success', 'Menu berhasil dihapus.');
    }
}
