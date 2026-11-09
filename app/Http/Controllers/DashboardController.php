<?php

namespace App\Http\Controllers;

use App\Models\dataMenu;
use App\Models\Transaksi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $totalPenjualanHariIni = Transaksi::whereDate('tanggal_transaksi', $today)
            ->sum('totalharga');

        $jumlahMenu = dataMenu::count();

        $jumlahTransaksiHariIni = Transaksi::whereDate('tanggal_transaksi', $today)
            ->count();

        $transaksiTerbaru = Transaksi::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Data Grafik Penjualan 7 Hari Terakhir (Chart.js)
        $chartLabels = [];
        $chartTotals = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->translatedFormat('d M');
            $chartTotals[] = (float) Transaksi::whereDate('tanggal_transaksi', $date)
                ->sum('totalharga');
        }

        return view('dashboard', compact(
            'totalPenjualanHariIni',
            'jumlahMenu',
            'jumlahTransaksiHariIni',
            'transaksiTerbaru',
            'chartLabels',
            'chartTotals'
        ));
    }
}
