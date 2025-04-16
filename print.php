<?php 
@ob_start();
session_start();

if (!empty($_SESSION['admin'])) {
    $id = $_SESSION['admin']['id_member'];
} else {
    echo '<script>window.location="login.php";</script>';
    exit;
}

require 'config.php';
include $view;

$lihat = new view($config);
$toko = $lihat->toko();
$hsl = $lihat->penjualan();
$hasil = $lihat->jumlah();

// Ambil data kasir dari session
$kasir = $_SESSION['admin']['nm_member'];

// Ambil data bayar dan kembali dari parameter GET
$bayar = isset($_GET['bayar']) ? $_GET['bayar'] : 0;
$kembali = isset($_GET['kembali']) ? $_GET['kembali'] : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pembelian - <?php echo $toko['nama_toko']; ?></title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 13px;
            background-color: #f7f7f7;
        }
        .struk-box {
            max-width: 400px;
            margin: 30px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 0;
        }
        .alamat {
            font-size: 13px;
            color: #666;
        }
        .table-custom {
            font-size: 13px;
            margin-top: 10px;
        }
        .table-custom th {
            background-color: #f0f0f0;
            text-align: left;
        }
        .total-box {
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 6px;
            margin-top: 10px;
        }
        .total-box p {
            margin: 4px 0;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .footer-note {
            font-size: 12px;
            margin-top: 15px;
            color: #555;
        }
        hr {
            border-top: 1px dashed #bbb;
        }
        .brand-logo {
            font-size: 28px;
            font-weight: bold;
            color: #008080;
            margin-bottom: 5px;
        }
    </style>
</head>
<body onload="window.print();">
    <div class="struk-box">
        <div class="text-center">
            <div class="brand-logo"><?php echo strtoupper($toko['nama_toko']); ?></div>
            <p class="alamat"><?php echo $toko['alamat_toko']; ?></p>
            <p>Tanggal: <?php echo date("j F Y, G:i"); ?></p>
            <p>Kasir: <strong><?php echo htmlentities($kasir); ?></strong></p>
        </div>

        <hr>

        <table class="table table-bordered table-custom">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($hsl as $isi): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $isi['nama_barang']; ?></td>
                    <td><?php echo $isi['jumlah']; ?></td>
                    <td>Rp.<?php echo number_format($isi['total']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="total-box">
            <p>Total: Rp.<?php echo number_format($hasil['bayar']); ?>,-</p>
            <p>Bayar: Rp.<?php echo number_format($bayar); ?>,-</p>
            <p>Kembali: Rp.<?php echo number_format($kembali); ?>,-</p>
        </div>

        <hr>

        <div class="text-center footer-note">
            <p>Terima Kasih telah berbelanja 🙏</p>
            <p>~ Ajeng, Kasir Anda ~</p>
        </div>
    </div>
</body>
</html>
