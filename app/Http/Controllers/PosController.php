<?php

namespace App\Http\Controllers;

use App\Models\dataMenu;
use App\Models\Kategori;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PosController extends Controller
{
    /**
     * Menampilkan halaman POS (Katalog menu dan Keranjang belanja)
     */
    public function index(Request $request)
    {
        $DataMenu = dataMenu::with('kategori')
            ->when($request->search, function ($query, $search) {
                $query->where('nama_menu', 'like', "%{$search}%");
            })
            ->when($request->category_id, function ($query, $categoryId) {
                $query->where('kategori_id', $categoryId);
            })
            ->latest()
            ->get();

        $categories = Kategori::all();

        $cart = session()->get('cart', []);

        $total = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return view('pos.index', compact(
            'DataMenu',
            'categories',
            'cart',
            'total'
        ));
    }

    /**
     * Menambahkan menu ke keranjang
     */
    public function add(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:data_menus,id',
        ]);

        $menu = dataMenu::findOrFail($request->menu_id);

        if ($menu->stock <= 0) {
            return back()->with('error', 'Stok menu ' . $menu->nama_menu . ' telah habis.');
        }

        $cart = session()->get('cart', []);

        $quantity = isset($cart[$menu->id])
            ? $cart[$menu->id]['quantity'] + 1
            : 1;

        if ($quantity > $menu->stock) {
            return back()->with('error', 'Stok ' . $menu->nama_menu . ' tidak mencukupi (sisa ' . $menu->stock . ').');
        }

        $cart[$menu->id] = [
            'id' => $menu->id,
            'nama' => $menu->nama_menu,
            'price' => (float) $menu->harga,
            'quantity' => $quantity,
            'stock' => $menu->stock,
        ];

        session()->put('cart', $cart);

        return back()->with('success', 'Menu ' . $menu->nama_menu . ' berhasil ditambahkan ke keranjang.');
    }

    /**
     * Mengubah jumlah menu di keranjang
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (!isset($cart[$id])) {
            return back()->with('error', 'Menu tidak ditemukan di keranjang.');
        }

        $menu = dataMenu::findOrFail($id);

        if ($request->quantity > $menu->stock) {
            return back()->with('error', 'Jumlah pesanan melebihi stok yang tersedia (sisa ' . $menu->stock . ').');
        }

        $cart[$id]['quantity'] = (int) $request->quantity;
        $cart[$id]['stock'] = $menu->stock;

        session()->put('cart', $cart);

        return back()->with('success', 'Jumlah menu berhasil diperbarui.');
    }

    /**
     * Menghapus 1 menu dari keranjang
     */
    public function remove(string $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $nama = $cart[$id]['nama'] ?? 'Menu';
            unset($cart[$id]);
            session()->put('cart', $cart);
            return back()->with('success', $nama . ' berhasil dihapus dari keranjang.');
        }

        return back()->with('error', 'Item tidak ditemukan di keranjang.');
    }

    /**
     * Mengosongkan seluruh keranjang belanja
     */
    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Keranjang belanja berhasil dikosongkan.');
    }

    /**
     * Menyimpan transaksi (Checkout)
     */
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Keranjang masih kosong.');
        }

        $total = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $request->validate([
            'paid' => 'required|numeric|min:' . $total,
            'metode_pembayaran' => 'nullable|in:Tunai,QRIS,Debit',
        ], [
            'paid.required' => 'Nominal uang pembayaran wajib diisi.',
            'paid.numeric' => 'Nominal uang pembayaran harus berupa angka.',
            'paid.min' => 'Uang pembayaran kurang dari total tagihan (Rp' . number_format($total, 0, ',', '.') . ').',
        ]);

        try {
            $transaction = DB::transaction(function () use ($cart, $total, $request) {
                // 1. Validasi ketersediaan stok terbaru
                foreach ($cart as $id => $item) {
                    $menu = dataMenu::whereKey($id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($menu->stock < $item['quantity']) {
                        throw new \Exception(
                            'Stok ' . $menu->nama_menu . ' tidak mencukupi (sisa: ' . $menu->stock . ').'
                        );
                    }
                }

                // 2. Simpan transaksi utama
                $transaction = Transaksi::create([
                    'invoice_number' => 'INV-' . date('YmdHis') . '-' . Str::upper(Str::random(4)),
                    'user_id' => auth()->id(),
                    'totalharga' => $total,
                    'uang_bayar' => $request->paid,
                    'metode_pembayaran' => $request->metode_pembayaran ?? 'Tunai',
                    'kembalian' => $request->paid - $total,
                    'tanggal_transaksi' => now()->toDateString(),
                ]);

                // 3. Simpan detail item dan kurangi stok
                foreach ($cart as $id => $item) {
                    TransaksiDetail::create([
                        'transaksi_id' => $transaction->id,
                        'data_menu_id' => $id,
                        'jumlah' => $item['quantity'],
                        'harga' => $item['price'],
                    ]);

                    dataMenu::whereKey($id)->decrement(
                        'stock',
                        $item['quantity']
                    );
                }

                return $transaction;
            });

            // 4. Kosongkan keranjang belanja
            session()->forget('cart');

            return redirect()
                ->route('reports.show', $transaction->id)
                ->with('success', 'Transaksi berhasil disimpan. No. Invoice: ' . $transaction->invoice_number);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }
}
