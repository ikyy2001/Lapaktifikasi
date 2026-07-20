<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice Premium - {{ $pembelian->order_id }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 10px;
            font-size: 14px;
            line-height: 1.5;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            border: 1px solid #000;
            border-radius: 8px;
            padding: 30px;
        }
        .header {
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header table {
            width: 100%;
        }
        .logo-title {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: -0.5px;
        }
        .invoice-title {
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            color: #555;
        }
        .info-table {
            width: 100%;
            margin-bottom: 30px;
        }
        .info-table td {
            vertical-align: top;
            width: 50%;
        }
        .info-label {
            font-weight: bold;
            color: #000;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
            display: block;
        }
        .info-value {
            line-height: 1.4;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 35px;
        }
        .details-table th {
            background-color: #000;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
            padding: 10px;
            border: 1px solid #000;
        }
        .details-table td {
            padding: 12px 10px;
            border: 1px solid #e5e5e5;
        }
        .details-table tr.total-row td {
            font-weight: bold;
            border-top: 2px solid #000;
            background-color: #fafafa;
        }
        .footer {
            border-top: 1px solid #e5e5e5;
            padding-top: 15px;
            text-align: center;
            font-size: 12px;
            color: #777;
            font-style: italic;
        }
        .status-stamp {
            display: inline-block;
            border: 2px solid #28a745;
            color: #28a745;
            font-weight: bold;
            text-transform: uppercase;
            padding: 5px 15px;
            font-size: 14px;
            border-radius: 4px;
            margin-top: 10px;
            transform: rotate(-5deg);
        }
    </style>
</head>
<body>

<div class="invoice-box">
    <div class="header">
        <table>
            <tr>
                <td>
                    <span class="logo-title">Lapaktifikasi</span><br>
                    <small style="color: #666;">Marketplace Akun Premium Multi-Seller</small>
                </td>
                <td class="invoice-title">
                    INVOICE
                </td>
            </tr>
        </table>
    </div>

    <table class="info-table">
        <tr>
            <td>
                <span class="info-label">Diterbitkan Oleh (Toko):</span>
                <div class="info-value">
                    <strong>{{ $pembelian->varianLayanan->tipeLayanan->produk->toko->nama_toko }}</strong><br>
                    No. Telp Toko: {{ $pembelian->varianLayanan->tipeLayanan->produk->toko->no_telp }}<br>
                    Telegram: @{{ $pembelian->varianLayanan->tipeLayanan->produk->toko->akun_telegram }}
                </div>
            </td>
            <td style="text-align: right;">
                <span class="info-label">Rincian Pembayaran:</span>
                <div class="info-value">
                    Order ID: <strong>{{ $pembelian->order_id }}</strong><br>
                    Tanggal Transaksi: {{ $pembelian->created_at->format('d F Y H:i') }} WIB<br>
                    Metode Pembayaran: {{ strtoupper($pembelian->pembayaran->first()->metode_pembayaran ?? 'Midtrans') }}<br>
                    Tanggal Bayar: {{ $pembelian->pembayaran->first() && $pembelian->pembayaran->first()->tanggal_bayar ? $pembelian->pembayaran->first()->tanggal_bayar->format('d F Y H:i') . ' WIB' : '-' }}
                </div>
                <div class="status-stamp">LUNAS / PAID</div>
            </td>
        </tr>
        <tr>
            <td style="padding-top: 15px;">
                <span class="info-label">Tujuan Pembelian:</span>
                <div class="info-value">
                    Nama Customer: {{ $pembelian->customer->user->name }}<br>
                    Email: {{ $pembelian->customer->user->email }}<br>
                    Nomor WhatsApp: {{ $pembelian->customer->nomor_telepon ?? '-' }}
                </div>
            </td>
            <td></td>
        </tr>
    </table>

    <table class="details-table">
        <thead>
            <tr>
                <th style="text-align: left; width: 60%;">Deskripsi Produk</th>
                <th style="text-align: center; width: 15%;">Durasi</th>
                <th style="text-align: right; width: 25%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>{{ $pembelian->varianLayanan->tipeLayanan->produk->nama_produk }}</strong><br>
                    <span style="color: #666; font-size: 12px;">
                        Tipe: {{ $pembelian->varianLayanan->tipeLayanan->nama_tipe }} - Varian: {{ $pembelian->varianLayanan->nama_varian }}
                    </span>
                </td>
                <td style="text-align: center;">{{ $pembelian->varianLayanan->durasi_hari }} Hari</td>
                <td style="text-align: right;">Rp {{ number_format($pembelian->harga_saat_beli, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="2" style="text-align: right; font-weight: bold;">TOTAL BAYAR</td>
                <td style="text-align: right; font-weight: bold;">Rp {{ number_format($pembelian->harga_saat_beli, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Terima kasih atas pembelian Anda di Lapaktifikasi!<br>
        Invoice ini sah dan dikeluarkan secara elektronik oleh sistem. Jika Anda memiliki kendala, hubungi admin Lapaktifikasi.
    </div>
</div>

</body>
</html>
