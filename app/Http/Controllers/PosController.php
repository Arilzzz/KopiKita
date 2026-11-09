<?php

namespace App\Http\Controllers;

use App\Models\DataMenu;
use App\Models\Kategori;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PosController extends Controller
{
    // Menampilkan halaman POS
    public function index(Request $request)
    {
        $DataMenu = DataMenu::with('kategori')
            ->when($request->search, function ($query, $search) {
                $query->where('nama', 'like', "%{$search}%");
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


    // Menambahkan menu ke keranjang
    public function add(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:data_menus,id',
        ]);

        $menu = DataMenu::findOrFail($request->menu_id);

        $cart = session()->get('cart', []);

        $quantity = isset($cart[$menu->id])
            ? $cart[$menu->id]['quantity'] + 1
            : 1;

        if ($quantity > $menu->stock) {
            return back()->with('error', 'Stok menu tidak mencukupi.');
        }

        $cart[$menu->id] = [
            'nama' => $menu->nama,
            'price' => $menu->price,
            'quantity' => $quantity,
            'stock' => $menu->stock,
        ];

        session()->put('cart', $cart);

        return back()->with('success', 'Menu berhasil ditambahkan.');
    }


    // Mengubah jumlah menu
    public function update(Request $request, string $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (!isset($cart[$id])) {
            return back()->with('error', 'Menu tidak ditemukan di keranjang.');
        }

        $menu = DataMenu::findOrFail($id);

        if ($request->quantity > $menu->stock) {
            return back()->with('error', 'Jumlah pesanan melebihi stok.');
        }

        $cart[$id]['quantity'] = $request->quantity;
        $cart[$id]['stock'] = $menu->stock;

        session()->put('cart', $cart);

        return back()->with('success', 'Jumlah menu berhasil diperbarui.');
    }


    // Menghapus menu dari keranjang
    public function remove(string $id)
    {
        $cart = session()->get('cart', []);

        unset($cart[$id]);

        session()->put('cart', $cart);

        return back()->with('success', 'Menu berhasil dihapus dari keranjang.');
    }


    // Menyimpan transaksi
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
        ]);

        try {

            $transaction = DB::transaction(function () use ($cart, $total, $request) {

                // Memeriksa stok terbaru
                foreach ($cart as $id => $item) {
                    $menu = DataMenu::whereKey($id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($menu->stock < $item['quantity']) {
                        throw new \Exception(
                            'Stok ' . $menu->nama . ' tidak mencukupi.'
                        );
                    }
                }

                // Membuat transaksi utama
                $transaction = Transaksi::create([
                    'invoice' => 'INV-' . date('YmdHis') . '-' . Str::upper(Str::random(4)),
                    'user_id' => auth()->$id,
                    'total' => $total,
                    'paid' => $request->paid,
                    'change' => $request->paid - $total,
                ]);

                // Menyimpan detail dan mengurangi stok
                foreach ($cart as $id => $item) {

                    TransaksiDetail::create([
                        'transaction_id' => $transaction->id,
                        'menu_id' => $id,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['price'] * $item['quantity'],
                    ]);

                    DataMenu::whereKey($id)->decrement(
                        'stock',
                        $item['quantity']
                    );
                }

                return $transaction;
            });

            // Mengosongkan keranjang setelah transaksi berhasil
            session()->forget('cart');

            return redirect()
                ->route('reports.show', $transaction->id)
                ->with('success', 'Transaksi berhasil disimpan.');

        } catch (\Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }
}
