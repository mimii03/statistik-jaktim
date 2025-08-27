<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login_admin.php?kelurahan=" . urlencode($_GET['kelurahan'] ?? ''));
    exit;
}

$type = $_GET['type'] ?? 'pendidikan';

$map = [
    'pendidikan'   => 'data_pendidikan.json',
    'kesehatan'    => 'data_kesehatan.json',
    'ekonomi'      => 'data_ekonomi.json',
    'kependudukan' => 'data_kependudukan.json',
];

$data_file = $map[$type] ?? $map['pendidikan'];

$data = [];
if (file_exists($data_file)) {
    $json = file_get_contents($data_file);
    $data = json_decode($json, true) ?? [];
}

// Hapus data
if (isset($_GET['hapus'])) {
    $index = $_GET['hapus'];
    array_splice($data, $index, 1);
    file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT));
    header("Location: tambahdata.php?type=$type");
    exit;
}

// Tambah / Edit data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($type == 'pendidikan') {
        $record = [
            'jenjang' => $_POST['jenjang'],
            'jumlah'  => $_POST['jumlah'],
        ];

        // Cek duplikat jenjang
        $duplicate = false;
        foreach ($data as $i => $row) {
            if ($row['jenjang'] === $record['jenjang'] && (!isset($_POST['edit_index']) || $i != $_POST['edit_index'])) {
                $duplicate = true;
                break;
            }
        }
        if ($duplicate) {
            echo "<p style='color:red'>❌ Data dengan jenjang <b>{$record['jenjang']}</b> sudah ada. Silakan edit saja.</p>";
            echo "<a href='tambahdata.php?type=$type'>⬅ Kembali</a>";
            exit;
        }

    } elseif ($type == 'kesehatan') {
        $record = [
            'fasilitas_kesehatan' => $_POST['fasilitas_kesehatan'],
            'jumlah'              => $_POST['jumlah_kesehatan'],
        ];

        // Cek duplikat fasilitas_kesehatan
        $duplicate = false;
        foreach ($data as $i => $row) {
            if ($row['fasilitas_kesehatan'] === $record['fasilitas_kesehatan'] && (!isset($_POST['edit_index']) || $i != $_POST['edit_index'])) {
                $duplicate = true;
                break;
            }
        }
        if ($duplicate) {
            echo "<p style='color:red'>❌ Data fasilitas <b>{$record['fasilitas_kesehatan']}</b> sudah ada. Silakan edit saja.</p>";
            echo "<a href='tambahdata.php?type=$type'>⬅ Kembali</a>";
            exit;
        }

    } elseif ($type == 'ekonomi') {
        $record = [
            'fasilitas' => $_POST['fasilitas'],
            'jumlah'    => $_POST['jumlah_fasilitas'],
        ];

        // Cek duplikat fasilitas ekonomi
        $duplicate = false;
        foreach ($data as $i => $row) {
            if ($row['fasilitas'] === $record['fasilitas'] && (!isset($_POST['edit_index']) || $i != $_POST['edit_index'])) {
                $duplicate = true;
                break;
            }
        }
        if ($duplicate) {
            echo "<p style='color:red'>❌ Data fasilitas <b>{$record['fasilitas']}</b> sudah ada. Silakan edit saja.</p>";
            echo "<a href='tambahdata.php?type=$type'>⬅ Kembali</a>";
            exit;
        }

    } elseif ($type == 'kependudukan') {
        $record = [
            'jumlah_penduduk' => $_POST['jumlah_penduduk'],
        ];

        // Karena kependudukan cuma 1 data, jangan boleh ada lebih dari 1
        if (count($data) > 0 && (!isset($_POST['edit_index']) || $_POST['edit_index'] === '')) {
            echo "<p style='color:red'>❌ Data kependudukan sudah ada. Silakan edit saja.</p>";
            echo "<a href='tambahdata.php?type=$type'>⬅ Kembali</a>";
            exit;
        }
    }

    // Simpan data (edit atau tambah baru)
    if (isset($_POST['edit_index']) && $_POST['edit_index'] !== '') {
        $data[(int)$_POST['edit_index']] = $record;
    } else {
        $data[] = $record;
    }

    file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT));

    // ambil kelurahan dari form hidden
    $kelurahan = isset($_POST['kelurahan']) ? urlencode($_POST['kelurahan']) : '';

    echo "<p>✅ Data berhasil disimpan!</p>";
    if ($type == 'pendidikan') {
        echo "<a href='pendidikan.php?kelurahan=$kelurahan' class='btn-kembali'>⬅ Kembali ke Data Pendidikan</a>";
    } else {
        echo "<a href='tambahdata.php?type=$type' class='btn-kembali'>⬅ Kembali</a>";
    }
    exit;
}

// Mode edit
$edit_data = null;
$edit_index = null;
if (isset($_GET['edit'])) {
    $edit_index = $_GET['edit'];
    $edit_data = $data[$edit_index];
}
?>

<!DOCTYPE html>
<html lang="en" class="bg-[#e0f2fe] dark:bg-gray-900">
<head>
    <meta charset="UTF-8">
    <title>Input data pendidikan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="sidebar hidden" id="sidebar">
    <h3>Statistik</h3>
    <a href="pendidikan.php">📚 Pendidikan</a>
    <a href="kependudukan.php">🧑‍🤝‍🧑 Kependudukan</a>
    <a href="ekonomi.php">💼 Ekonomi</a>
    <a href="kesehatan.php">🏥 Kesehatan</a>
  </div>

  <div class="navbar">
    <span class="toggle-btn" onclick="toggleSidebar()">☰</span>
    <a href="index.php" class="beranda-link">Beranda</a>
    
    <div class="dropdown">
      <input type="text" class="search-input" id="searchKel" onkeyup="filterKelurahan()" placeholder="Cari kelurahan...">
      <div class="dropdown-content">
        <div id="kelurahanList">
          <a href="data.php?kelurahan=Balimester">Balimester</a>
          <a href="data.php?kelurahan=Batu Ampar">Batu Ampar</a>
          <a href="data.php?kelurahan=Baru">Baru</a>
          <a href="data.php?kelurahan=Batuampar">Batuampar</a>
          <a href="data.php?kelurahan=Bidaracina">Bidaracina</a>
          <a href="data.php?kelurahan=Bambu Apus">Bambu Apus</a>
          <a href="data.php?kelurahan=Cawang">Cawang</a>
          <a href="data.php?kelurahan=Ceger">Ceger</a>
          <a href="data.php?kelurahan=Cibubur">Cibubur</a>
          <a href="data.php?kelurahan=Cipinang">Cipinang</a>
          <a href="data.php?kelurahan=Cipinang Besar Selatan">Cipinang Besar Selatan</a>
          <a href="data.php?kelurahan=Cipinang Besar Utara">Cipinang Besar Utara</a>
          <a href="data.php?kelurahan=Cipinang Cempedak">Cipinang Cempedak</a>
          <a href="data.php?kelurahan=Cipinang Melayu">Cipinang Melayu</a>
          <a href="data.php?kelurahan=Cipinang Muara">Cipinang Muara</a>
          <a href="data.php?kelurahan=Cilangkap">Cilangkap</a>
          <a href="data.php?kelurahan=Ciracas">Ciracas</a>
          <a href="data.php?kelurahan=Duren Sawit">Duren Sawit</a>
          <a href="data.php?kelurahan=Dukuh">Dukuh</a>
          <a href="data.php?kelurahan=Gedong">Gedong</a>
          <a href="data.php?kelurahan=Halim Perdana Kusumah">Halim Perdana Kusumah</a>
          <a href="data.php?kelurahan=Jatinegara">Jatinegara</a>
          <a href="data.php?kelurahan=Jatinegara Kaum">Jatinegara Kaum</a>
          <a href="data.php?kelurahan=Jati">Jati</a>
          <a href="data.php?kelurahan=Kampung Dukuh">Kampung Dukuh</a>
          <a href="data.php?kelurahan=Kampung Melayu">Kampung Melayu</a>
          <a href="data.php?kelurahan=Kayu Manis">Kayu Manis</a>
          <a href="data.php?kelurahan=Kayu Putih">Kayu Putih</a>
          <a href="data.php?kelurahan=Kebon Manggis">Kebon Manggis</a>
          <a href="data.php?kelurahan=Kramat Jati">Kramat Jati</a>
          <a href="data.php?kelurahan=Klender">Klender</a>
          <a href="data.php?kelurahan=Lubang Buaya">Lubang Buaya</a>
          <a href="data.php?kelurahan=Malaka Jaya">Malaka Jaya</a>
          <a href="data.php?kelurahan=Malaka Sari">Malaka Sari</a>
          <a href="data.php?kelurahan=Makasar">Makasar</a>
          <a href="data.php?kelurahan=Matraman">Matraman</a>
          <a href="data.php?kelurahan=Munjul">Munjul</a>
          <a href="data.php?kelurahan=Palmeriam">Palmeriam</a>
          <a href="data.php?kelurahan=Pasar Rebo">Pasar Rebo</a>
          <a href="data.php?kelurahan=Pekayon">Pekayon</a>
          <a href="data.php?kelurahan=Penggilingan">Penggilingan</a>
          <a href="data.php?kelurahan=Pinang Ranti">Pinang Ranti</a>
          <a href="data.php?kelurahan=Pisangan Baru">Pisangan Baru</a>
          <a href="data.php?kelurahan=Pondok Bambu">Pondok Bambu</a>
          <a href="data.php?kelurahan=Pondok Kelapa">Pondok Kelapa</a>
          <a href="data.php?kelurahan=Pondok Kopi">Pondok Kopi</a>
          <a href="data.php?kelurahan=Pulogadung">Pulogadung</a>
          <a href="data.php?kelurahan=Pulo Gebang">Pulo Gebang</a>
          <a href="data.php?kelurahan=Rambutan">Rambutan</a>
          <a href="data.php?kelurahan=Rawa Bunga">Rawa Bunga</a>
          <a href="data.php?kelurahan=Rawa Terate">Rawa Terate</a>
          <a href="data.php?kelurahan=Rawamangun">Rawamangun</a>
          <a href="data.php?kelurahan=Setu">Setu</a>
          <a href="data.php?kelurahan=Susukan">Susukan</a>
          <a href="data.php?kelurahan=Utan Kayu Selatan">Utan Kayu Selatan</a>
          <a href="data.php?kelurahan=Utan Kayu Utara">Utan Kayu Utara</a>
        </div>
      </div>
    </div>
  </div>

<div class="min-h-screen text-gray-800 dark:text-white">
    <main class="max-w-5xl mx-auto p-6">
        <form method="POST" enctype="multipart/form-data" class="space-y-3 mb-10">
            <input type="hidden" name="edit_index" value="<?= $edit_index ?>">

            <?php if ($type == 'pendidikan'): ?>
                <select name="jenjang" required class="w-full px-3 py-2 border rounded">
                    <option value="">Pilih Jenjang</option>
                    <?php foreach (['SD','MI','SMP','MTS','SMA','SMK','MA','PT/Akademi'] as $j): ?>
                        <option value="<?= $j ?>" <?= isset($edit_data['jenjang']) && $edit_data['jenjang'] === $j ? 'selected' : '' ?>><?= $j ?></option>
                    <?php endforeach ?>
                </select>
                <input type="number" name="jumlah" placeholder="Jumlah" value="<?= $edit_data['jumlah'] ?? '' ?>" required class="w-full px-3 py-2 border rounded">

            <?php elseif ($type == 'kesehatan'): ?>
                <select name="fasilitas" required class="w-full px-3 py-2 border rounded">
                    <option value="">Pilih Fasilitas</option>
                    <?php foreach (['Rumah Sakit','Puskesmas','Pos Kesehatan','Klinik Kesehatan','Tempat Praktek Dokter','Tempat Praktek Bidan','Apotik','Toko Obat'] as $j): ?>
                        <option value="<?= $j ?>" <?= isset($edit_data['fasilitas']) && $edit_data['fasilitas'] === $j ? 'selected' : '' ?>><?= $j ?></option>
                    <?php endforeach ?>
                </select>
                <input type="number" name="jumlah_kesehatan" placeholder="Jumlah" value="<?= $edit_data['jumlah'] ?? '' ?>" required class="w-full px-3 py-2 border rounded">

            <?php elseif ($type == 'ekonomi'): ?>
                <select name="fasilitas" required class="w-full px-3 py-2 border rounded">
                    <option value="">Pilih Fasilitas Ekonomi</option>
                    <?php foreach (['Pasar Lingkungan','Mini Market','Pabrik Industri','Toko','Warung/Warteg','Lokasi Kaki Lima','Bank','POM Bensin','Kuliner'] as $j): ?>
                        <option value="<?= $j ?>" <?= isset($edit_data['fasilitas']) && $edit_data['fasilitas'] === $j ? 'selected' : '' ?>><?= $j ?></option>
                    <?php endforeach ?>
                </select>
                <input type="number" name="jumlah_fasilitas" placeholder="Jumlah" value="<?= $edit_data['jumlah'] ?? '' ?>" required class="w-full px-3 py-2 border rounded">

            <?php elseif ($type == 'kependudukan'): ?>
                <select name="kategori" required class="w-full px-3 py-2 border rounded">
                    <option value="">Pilih Kategori Kependudukan</option>
                    <?php foreach (['(00-04)','(05-09)','(10-14)','(15-19)','(20-24)','(25-29)','(30-34)','(35-39)','(40-44)','(45-49)','(50-54)','(55-59)','(60-64)','(65-69)','(70+)'] as $j): ?>
                        <option value="<?= $j ?>" <?= isset($edit_data['kategori']) && $edit_data['kategori'] === $j ? 'selected' : '' ?>><?= $j ?></option>
                    <?php endforeach ?>
                </select>
            <?php endif ?>
    <input type="hidden" name="kelurahan" value="<?php echo htmlspecialchars($_GET['kelurahan'] ?? ''); ?>">

        <div>
            <h3 class="text-xl font-semibold mb-3">Data <?= ucfirst($type) ?></h3>
            <table class="w-full border text-sm">
                <thead class="bg-blue-100 dark:bg-gray-700">
                    <tr>
                        <?php if ($type == 'pendidikan'): ?>
                            <th class="border px-2 py-1">Jenjang</th>
                            <th class="border px-2 py-1">Jumlah</th>
                        <?php elseif ($type == 'kesehatan'): ?>
                            <th class="border px-2 py-1">Fasilitas</th>
                            <th class="border px-2 py-1">Jumlah</th>
                        <?php elseif ($type == 'ekonomi'): ?>
                            <th class="border px-2 py-1">Fasilitas</th>
                            <th class="border px-2 py-1">Jumlah</th>
                        <?php elseif ($type == 'kependudukan'): ?>
                            <th class="border px-2 py-1">Kelompok Umur</th>
                            <th class="border px-2 py-1">Laki-laki</th>
                            <th class="border px-2 py-1">Perempuan</th>
                        <?php endif ?>
                        <th class="border px-2 py-1">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $i => $row): ?>
                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-800">
                            <?php foreach ($row as $val): ?>
                                <td class="border px-2 py-1"><?= htmlspecialchars($val) ?></td>
                            <?php endforeach ?>
                            <td class="border px-2 py-1 text-center space-x-2">
                                <a href="?type=<?= $type ?>&edit=<?= $i ?>" class="text-blue-500 hover:underline">Edit</a>
                                <a href="?type=<?= $type ?>&hapus=<?= $i ?>" onclick="return confirm('Yakin ingin menghapus?')" class="text-red-500 hover:underline">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach ?>

                </tbody>
            </table>
                <a href="pendidikan.php" class="btn-kembali">⬅ Kembali ke Data Pendidikan</a>

        </div>
    </main>
</div>
</body>
