<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan - Kopi Kita</title>
    <style>
        @page {
            margin: 25px;
        }
        body {
            font-family: 'DejaVu Sans', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header h2 {
            margin: 0 0 5px 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            color: #666;
            font-size: 11px;
        }
        .meta-info {
            margin-bottom: 15px;
        }
        .meta-info table {
            width: 100%;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #ccc;
            padding: 7px;
            text-align: left;
        }
        table.data-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .fw-bold {
            font-weight: bold;
        }
        .summary-box {
            width: 45%;
            float: right;
            border: 1px solid #ddd;
            padding: 8px;
            background-color: #fafafa;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>KAFE KOPI KITA</h2>
        <p>Laporan Rekapitulasi Transaksi Penjualan</p>
        <p>SMK Tunas Harapan Pati</p>
    </div>

    <div class="meta-info">
        <table>
            <tr>
                <td><strong>Periode:</strong> {{ $startDate ? date('d/m/Y', strtotime($startDate)) : 'Awal' }} s/d {{ $endDate ? date('d/m/Y', strtotime($endDate)) : 'Sekarang' }}</td>
                <td class="text-right"><strong>Tanggal Cetak:</strong> {{ date('d/m/Y H:i') }} WIB</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">No</th>
                <th style="width: 25%;">No. Invoice</th>
                <th style="width: 18%;">Waktu</th>
                <th style="width: 18%;">Kasir</th>
                <th style="width: 14%;">Metode</th>
                <th style="width: 20%;" class="text-right">Total Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksis as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="fw-bold">{{ $item->invoice_number }}</td>
                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $item->user->name ?? 'Kasir' }}</td>
                    <td>{{ $item->metode_pembayaran ?? 'Tunai' }}</td>
                    <td class="text-right fw-bold">Rp{{ number_format($item->totalharga, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px;">Tidak ada data transaksi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #f9f9f9; font-weight: bold;">
                <td colspan="5" class="text-right">TOTAL KESELURUHAN ({{ $jumlahTransaksi }} Transaksi):</td>
                <td class="text-right">Rp{{ number_format($totalPenjualan, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div style="clear: both; margin-top: 40px;">
        <table style="width: 100%;">
            <tr>
                <td style="width: 70%;"></td>
                <td style="text-align: center;">
                    <p style="margin-bottom: 50px;">Pati, {{ date('d F Y') }}<br>Penanggung Jawab Kasir,</p>
                    <p><strong>( ____________________ )</strong></p>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>

