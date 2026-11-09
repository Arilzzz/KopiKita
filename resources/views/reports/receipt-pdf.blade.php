<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <title>Struk {{ $transaksi->invoice }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #222;
        }

        .receipt {
            width: 100%;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .line {
            border-top: 1px dashed #555;
            margin: 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 4px 0;
            vertical-align: top;
        }

        .right {
            text-align: right;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
        }

        .total {
            font-size: 13px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="receipt">

        <div class="center">
            <div class="title">KOPI KITA</div>
            <p>Kasir & Manajemen Penjualan</p>
        </div>

        <div class="line"></div>

        <table>
            <tr>
                <td>No. Transaksi</td>
                <td class="right">
                    {{ $transaksi->invoice }}
                </td>
            </tr>

            <tr>
                <td>Tanggal</td>
                <td class="right">
                    {{ $transaksi->created_at->format('d/m/Y H:i') }}
                </td>
            </tr>

            <tr>
                <td>Kasir</td>
                <td class="right">
                    {{ $transaksi->user->name ?? 'Admin' }}
                </td>
            </tr>
        </table>

        <div class="line"></div>

        <table>

            @foreach ($transaksi->details as $detail)

                <tr>
                    <td colspan="2" class="bold">
                        {{ $detail->menu->nama ?? 'Menu Dihapus' }}
                    </td>
                </tr>

                <tr>
                    <td>
                        {{ $detail->quantity }} x
                        Rp{{ number_format($detail->price, 0, ',', '.') }}
                    </td>

                    <td class="right">
                        Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                    </td>
                </tr>

            @endforeach

        </table>

        <div class="line"></div>

        <table>
            <tr>
                <td>Total Belanja</td>
                <td class="right">
                    Rp{{ number_format($transaksi->total, 0, ',', '.') }}
                </td>
            </tr>

            <tr>
                <td>Uang Dibayar</td>
                <td class="right">
                    Rp{{ number_format($transaksi->paid, 0, ',', '.') }}
                </td>
            </tr>

            <tr>
                <td class="total">Kembalian</td>
                <td class="right total">
                    Rp{{ number_format($transaksi->change, 0, ',', '.') }}
                </td>
            </tr>
        </table>

        <div class="line"></div>

        <div class="center">
            <p>Terima kasih atas kunjungan Anda.</p>
            <p>Simpan struk sebagai bukti belanja.</p>
        </div>

    </div>

</body>

</html>
