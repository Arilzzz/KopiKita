<?php

namespace App\Http\Controllers;

use App\Models\DataMenu;
use App\Models\Transaction;
use App\Models\Transaksi;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPenjualanHariIni = Transaksi::whereDate(
            'created_at',
            today()
        )->sum('totalharga');

        $jumlahMenu = DataMenu::count();

        $jumlahTransaksiHariIni = Transaksi::whereDate(
            'created_at',
            today()
        )->count();

        $transaksiTerbaru = Transaksi::latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalPenjualanHariIni',
            'jumlahMenu',
            'jumlahTransaksiHariIni',
            'transaksiTerbaru'
        ));
    }
}
