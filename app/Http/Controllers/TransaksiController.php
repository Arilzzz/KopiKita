<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaksis = Transaksi::all();
        return view('transaksi.index', compact('transaksis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = $request->validate([
            'user_id' => 'required|exists:users,id',
            'invoice_number' => 'required|string|unique:transaksis,invoice_number',
            'totalharga' => 'required|numeric|min:0',
            'uang_bayar' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:Tunai,QRIS,Debit',
            'kembalian' => 'sometimes|numeric|min:0',
        ]);

        Transaksi::create($validation);

        return redirect()
            ->route('transaksi.index')
            ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaksi $transaksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        return view('transaksi.edit', compact('transaksi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        $validation = $request->validate([
            'user_id' => 'required|exists:users,id',
            'invoice_number' => 'required|string|unique:transaksis,invoice_number,' . $transaksi->id,
            'totalharga' => 'required|numeric|min:0',
            'uang_bayar' => 'required|numeric|min:0',
            'kembalian' => 'required|numeric|min:0',
        ]);

        $transaksi->update($validation);

        return redirect()
            ->route('transaksi.index')
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {
        if (!$transaksi) {
            return redirect()
                ->route('transaksi.index')
                ->with('error', 'Transaksi tidak ditemukan.');
        }
        $transaksi->delete();

        return redirect()
            ->route('transaksi.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }
}
