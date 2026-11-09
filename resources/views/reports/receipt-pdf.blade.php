<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk {{ $transaksi->invoice_number }}</title>
    <style>
        @page {
            margin: 10px;
        }
        body {
            font-family: 'DejaVu Sans', 'Courier New', Courier, monospace;
            font-size: 11px;
            color: #000;
            line-height: 1.3;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-left {
            text-align: left;
        }
        .fw-bold {
            font-weight: bold;
        }
        .dashed-line {
            border-top: 1px dashed #444;
            margin: 8px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 2px 0;
            vertical-align: top;
        }
        .header-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .sub-header {
            font-size: 10px;
            color: #444;
        }
        .total-row {
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="text-center">
        <div class="header-title">KOPI KITA</div>
        <div class="sub-header">Sistem Kasir & Manajemen Penjualan</div>
        <div class="sub-header">SMK Tunas Harapan Pati</div>
    </div>

    <div class="dashed-line"></div>

    <table>
        <tr>
            <td class="text-left">No. Invoice</td>
            <td class="text-right fw-bold">{{ $transaksi->invoice_number }}</td>
        </tr>
        <tr>
            <td class="text-left">Tanggal</td>
            <td class="text-right">{{ $transaksi->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td class="text-left">Kasir</td>
            <td class="text-right">{{ $transaksi->user->name ?? 'Kasir' }}</td>
        </tr>
        <tr>
            <td class="text-left">Metode Bayar</td>
            <td class="text-right">{{ $transaksi->metode_pembayaran ?? 'Tunai' }}</td>
        </tr>
    </table>

    <div class="dashed-line"></div>

    <table>
        @foreach ($transaksi->details as $detail)
            <tr>
                <td colspan="2" class="fw-bold">
                    {{ $detail->dataMenu->nama_menu ?? ($detail->menu->nama_menu ?? 'Item Menu') }}
                </td>
            </tr>
            <tr>
                <td class="text-left" style="color: #444; font-size: 10px;">
                    {{ $detail->jumlah }} x Rp{{ number_format($detail->harga, 0, ',', '.') }}
                </td>
                <td class="text-right fw-bold">
                    Rp{{ number_format($detail->jumlah * $detail->harga, 0, ',', '.') }}
                </td>
            </tr>
        @endforeach
    </table>

    <div class="dashed-line"></div>

    <table>
        <tr>
            <td class="text-left">Total Tagihan</td>
            <td class="text-right fw-bold">
                Rp{{ number_format($transaksi->totalharga, 0, ',', '.') }}
            </td>
        </tr>
        <tr>
            <td class="text-left">Uang Diterima</td>
            <td class="text-right">
                Rp{{ number_format($transaksi->uang_bayar, 0, ',', '.') }}
            </td>
        </tr>
        <tr class="total-row">
            <td class="text-left">Kembalian</td>
            <td class="text-right">
                Rp{{ number_format($transaksi->kembalian, 0, ',', '.') }}
            </td>
        </tr>
    </table>

    <div class="dashed-line"></div>

    <div class="text-center" style="margin-top: 10px; font-size: 10px;">
        <p style="margin: 2px 0;">Terima kasih atas kunjungan Anda!</p>
        <p style="margin: 2px 0; color: #555;">Simpan struk ini sebagai bukti pembayaran sah.</p>
    </div>

</body>
</html>
