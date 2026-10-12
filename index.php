<?php
$transaksi = false;
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $no_parkir = trim($_POST["no_parkir"] ?? "");
    $no_kendaraan = strtoupper(trim($_POST["no_kendaraan"] ?? ""));
    $jenis = $_POST["jenis"] ?? "";
    $lama = filter_var($_POST["lama"] ?? "", FILTER_VALIDATE_INT);
    $nama_petugas = trim($_POST["nama_petugas"] ?? "");

    // Tarif jam pertama
    $tarif_awal = [
        "Motor" => 2000,
        "Mobil" => 3000,
        "Truk"  => 7000
    ];

    // Tarif jam berikutnya
    $tarif_lanjutan = [
        "Motor" => 2000,
        "Mobil" => 3000,
        "Truk"  => 3000
    ];

    // Validasi data dan perhitungan
    if (
        $no_parkir !== "" &&
        $no_kendaraan !== "" &&
        isset($tarif_awal[$jenis]) &&
        $lama !== false &&
        $lama > 0 &&
        $nama_petugas !== ""
    ) {
        $harga_awal = $tarif_awal[$jenis];
        $harga_lanjut = $tarif_lanjutan[$jenis];

        // Menghitung total pembayaran
        $total = $harga_awal + (($lama - 1) * $harga_lanjut);

        $tanggal = date("d-m-Y H:i:s");
        $transaksi = true;
    } else {
        $error = "Mohon isi semua data dengan benar!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Parkir</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>FORM PEMBAYARAN PARKIR</h1>

<?php if ($transaksi): ?>

    <!-- STRUK PEMBAYARAN -->
    <div class="tiket">

        <div class="tiket-header">
            <h2>PARKIR</h2>
            <p>BUKTI PEMBAYARAN PARKIR</p>
            <span>Terima kasih telah menggunakan layanan kami</span>
        </div>

        <div class="garis-putus"></div>

        <div class="tiket-info">

            <div class="baris">
                <span>No. Tiket</span>
                <strong>#<?= htmlspecialchars($no_parkir) ?></strong>
            </div>

            <div class="baris">
                <span>Tanggal</span>
                <strong><?= $tanggal ?></strong>
            </div>

            <div class="baris">
                <span>No. Kendaraan</span>
                <strong><?= htmlspecialchars($no_kendaraan) ?></strong>
            </div>

            <div class="baris">
                <span>Jenis Kendaraan</span>
                <strong><?= htmlspecialchars($jenis) ?></strong>
            </div>

            <div class="baris">
                <span>Lama Parkir</span>
                <strong><?= number_format($lama, 0, ',', '.') ?> jam</strong>
            </div>

            <div class="baris">
                <span>Tarif Jam Pertama</span>
                <strong>
                    Rp <?= number_format($harga_awal, 0, ',', '.') ?>
                </strong>
            </div>

            <div class="baris">
                <span>Tarif Jam Berikutnya</span>
                <strong>
                    Rp <?= number_format($harga_lanjut, 0, ',', '.') ?>/jam
                </strong>
            </div>

        </div>

        <div class="garis-putus"></div>

        <div class="total">
            <span>TOTAL PEMBAYARAN</span>
            <h2>Rp <?= number_format($total, 0, ',', '.') ?></h2>
        </div>

        <div class="tiket-footer">
            <p>Petugas: <?= htmlspecialchars($nama_petugas) ?></p>
            <p>Simpan struk ini sebagai bukti pembayaran.</p>
            <strong>TERIMA KASIH</strong>
        </div>

    </div>

    <!-- TOMBOL AKSI -->
    <div class="aksi">
        <button type="button" onclick="window.print()">
            Cetak Struk
        </button>

        <button type="button" class="btn-kembali"
            onclick="window.location.href = window.location.pathname">
            Kembali
        </button>
    </div>

<?php else: ?>

    <!-- PESAN KESALAHAN -->
    <?php if ($error !== ""): ?>
        <div class="alert">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- FORM INPUT -->
    <form method="POST" action="">

        <label for="no_parkir">No parkir</label>
        <input type="number" id="no_parkir" name="no_parkir"
            value="<?= htmlspecialchars($_POST['no_parkir'] ?? '') ?>"
            required>

        <label for="no_kendaraan">No kendaraan</label>
        <input type="text" id="no_kendaraan" name="no_kendaraan"
            placeholder="Contoh: H 1234 AB"
            value="<?= htmlspecialchars($_POST['no_kendaraan'] ?? '') ?>"
            required>

        <label for="jenis">Jenis kendaraan</label>
        <select id="jenis" name="jenis" required>
            <option value="">Pilih kendaraan</option>

            <option value="Motor"
                <?= (($_POST['jenis'] ?? '') === 'Motor') ? 'selected' : '' ?>>
                Motor
            </option>

            <option value="Mobil"
                <?= (($_POST['jenis'] ?? '') === 'Mobil') ? 'selected' : '' ?>>
                Mobil
            </option>

            <option value="Truk"
                <?= (($_POST['jenis'] ?? '') === 'Truk') ? 'selected' : '' ?>>
                Truk
            </option>
        </select>

        <label for="lama">Lama parkir (jam)</label>
        <input type="number" id="lama" name="lama" min="1"
            value="<?= htmlspecialchars($_POST['lama'] ?? '') ?>"
            required>

        <label for="nama_petugas">Nama petugas</label>
        <input type="text" id="nama_petugas" name="nama_petugas"
            value="<?= htmlspecialchars($_POST['nama_petugas'] ?? '') ?>"
            required>

        <button type="submit">Submit</button>

    </form>

<?php endif; ?>

</body>
</html>