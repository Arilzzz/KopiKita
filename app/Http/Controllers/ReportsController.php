<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    /**
     * Menampilkan riwayat transaksi dan ringkasan laporan penjualan
     */
    public function index(Request $request)
    {
        $query = Transaksi::with(['user', 'details.dataMenu'])
            ->when($request->start_date, function ($q, $date) {
                $q->whereDate('tanggal_transaksi', '>=', $date);
            })
            ->when($request->end_date, function ($q, $date) {
                $q->whereDate('tanggal_transaksi', '<=', $date);
            })
            ->when($request->search, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('invoice_number', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%");
                        });
                });
            });

        // Hitung total sebelum pagination agar mencakup seluruh data filter
        $totalPenjualan = (clone $query)->sum('totalharga');
        $jumlahTransaksi = (clone $query)->count();

        $transaksi = $query->latest()
            ->paginate(10)
            ->withQueryString();

        return view('reports.index', compact(
            'transaksi',
            'jumlahTransaksi',
            'totalPenjualan'
        ));
    }

    /**
     * Menampilkan detail struk transaksi
     */
    public function show(string $id)
    {
        $transaksi = Transaksi::with([
            'user',
            'details.dataMenu',
        ])->findOrFail($id);

        return view('reports.show', compact('transaksi'));
    }

    /**
     * Mengunduh / mencetak struk transaksi dalam bentuk PDF
     */
    public function pdf(string $id)
    {
        $transaksi = Transaksi::with([
            'user',
            'details.dataMenu',
        ])->findOrFail($id);

        $pdf = Pdf::loadView('reports.receipt-pdf', compact('transaksi'));

        // Ukuran kertas thermal kasir (sekitar 80mm lebar)
        $pdf->setPaper([0, 0, 226.77, 650], 'portrait');

        return $pdf->stream('Struk-' . $transaksi->invoice_number . '.pdf');
    }

    /**
     * Mencetak laporan penjualan berkala / harian dalam bentuk PDF
     */
    public function exportPdf(Request $request)
    {
        $transaksis = Transaksi::with(['user', 'details.dataMenu'])
            ->when($request->start_date, function ($q, $date) {
                $q->whereDate('tanggal_transaksi', '>=', $date);
            })
            ->when($request->end_date, function ($q, $date) {
                $q->whereDate('tanggal_transaksi', '<=', $date);
            })
            ->when($request->search, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('invoice_number', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->get();

        $totalPenjualan = $transaksis->sum('totalharga');
        $jumlahTransaksi = $transaksis->count();
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $pdf = Pdf::loadView('reports.report-pdf', compact(
            'transaksis',
            'totalPenjualan',
            'jumlahTransaksi',
            'startDate',
            'endDate'
        ));

        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan-Penjualan-Kopi-Kita-' . date('Ymd') . '.pdf');
    }
}
