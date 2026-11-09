<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportsController extends Controller
{
    // Menampilkan riwayat transaksi
    public function index(Request $request)
    {
        $transaksi = Transaksi::with('user')
            ->when($request->start_date, function ($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->end_date, function ($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('invoice', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalPenjualan = (clone $transaksi->getCollection())
            ->sum('total');
        $jumlahTransaksi = (clone $transaksi->getCollection())
            ->count();

        return view('reports.index', compact(
            'transaksi',
            'jumlahTransaksi',
            'totalPenjualan'
        ));
    }


    // Menampilkan detail transaksi
    public function show(string $id)
    {
        $transaksi = Transaksi::with([
            'user',
            'details.menu'
        ])->findOrFail($id);

        return view('reports.show', compact('transaksi'));
    }


    // Mengunduh struk PDF
    public function pdf(string $id)
    {
        $transaksi = Transaksi::with([
            'user',
            'details.menu'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('reports.receipt-pdf', compact('transaksi'));

        $pdf->setPaper([0, 0, 226.77, 600], 'portrait');

        return $pdf->download('Struk-' . $transaksi->invoice . '.pdf');
    }
}
