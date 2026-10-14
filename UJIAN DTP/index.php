
<?php
require_once "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST["nama_pelapor"] ?? "");
    $fasilitas = trim($_POST["nama_fasilitas"] ?? "");
    $lokasi = trim($_POST["lokasi"] ?? "");
    $kategori = $_POST["id_kategori"] ?? "";
    $deskripsi = trim($_POST["deskripsi"] ?? "");
    $prioritas = $_POST["prioritas"] ?? "";

    if (
        $nama != "" && $fasilitas != "" &&
        $lokasi != "" && $kategori != "" &&
        $deskripsi != "" &&
        in_array($prioritas, ["Rendah", "Sedang", "Tinggi"])
    ) {
        $sql = "INSERT INTO laporan_kerusakan
                (nama_pelapor, nama_fasilitas, lokasi,
                 id_kategori, deskripsi, prioritas, status)
                VALUES (?, ?, ?, ?, ?, ?, 'Menunggu')";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $nama, $fasilitas, $lokasi,
            $kategori, $deskripsi, $prioritas
        ]);

        header("Location: index.php#daftar-laporan");
        exit;
    }
}

$query = $pdo->query(
    "SELECT id_laporan, nama_pelapor, nama_fasilitas,
            lokasi, prioritas, status, tanggal_laporan
     FROM laporan_kerusakan
     ORDER BY id_laporan DESC"
);

$data_laporan = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelaporan Kerusakan Fasilitas Sekolah</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Sistem Pelaporan Kerusakan Fasilitas Sekolah</h1>
        <p>Catat dan pantau laporan kerusakan fasilitas sekolah.</p>

        <nav class="navigasi">
            <a href="#form-laporan">Buat Laporan</a>
            <a href="#daftar-laporan">Daftar Laporan</a>
        </nav>
    </header>

    <main>
        <section id="form-laporan">
            <h2>Form Pelaporan Kerusakan</h2>
            <p>Lengkapi data berikut untuk melaporkan fasilitas yang rusak.</p>

            <form action="" method="post">
                <fieldset>
                    <legend>Data Pelaporan</legend>

                    <p>
                        <label for="nama_pelapor">Nama pelapor</label>
                        <input type="text" id="nama_pelapor"
                               name="nama_pelapor" maxlength="100" required>
                    </p>

                    <p>
                        <label for="nama_fasilitas">Nama fasilitas</label>
                        <input type="text" id="nama_fasilitas"
                               name="nama_fasilitas" maxlength="100" required>
                    </p>

                    <p>
                        <label for="lokasi">Lokasi kerusakan</label>
                        <input type="text" id="lokasi" name="lokasi"
                               maxlength="100" required>
                    </p>

                    <p>
                        <label for="id_kategori">Kategori fasilitas</label>
                        <select id="id_kategori" name="id_kategori" required>
                            <option value="">-- Pilih kategori --</option>
                            <option value="1">Elektronik</option>
                            <option value="2">Furnitur</option>
                            <option value="3">Jaringan</option>
                            <option value="4">Peralatan Kelas</option>
                        </select>
                    </p>

                    <p>
                        <label for="deskripsi">Deskripsi kerusakan</label>
                        <textarea id="deskripsi" name="deskripsi"
                                  rows="4" required></textarea>
                    </p>

                    <p>
                        <label for="prioritas">Prioritas</label>
                        <select id="prioritas" name="prioritas" required>
                            <option value="">-- Pilih prioritas --</option>
                            <option value="Rendah">Rendah</option>
                            <option value="Sedang">Sedang</option>
                            <option value="Tinggi">Tinggi</option>
                        </select>
                    </p>

                    <p>
                        <button type="submit">Kirim Laporan</button>
                    </p>
                </fieldset>
            </form>
        </section>

        <section id="daftar-laporan">
            <h2>Daftar Laporan Kerusakan</h2>
            <p>Berikut laporan kerusakan yang sudah tercatat.</p>

            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Fasilitas</th>
                        <th>Lokasi</th>
                        <th>Pelapor</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (count($data_laporan) > 0): ?>
                        <?php $no = 1; ?>

                        <?php foreach ($data_laporan as $laporan): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($laporan["nama_fasilitas"]) ?></td>
                                <td><?= htmlspecialchars($laporan["lokasi"]) ?></td>
                                <td><?= htmlspecialchars($laporan["nama_pelapor"]) ?></td>
                                <td><?= htmlspecialchars($laporan["prioritas"]) ?></td>
                                <td><?= htmlspecialchars($laporan["status"]) ?></td>
                                <td><?= htmlspecialchars($laporan["tanggal_laporan"]) ?></td>
                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="7">Belum ada laporan yang terdaftar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Sistem Pelaporan Kerusakan Fasilitas Sekolah.</p>
    </footer>
</body>
</html>
