<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login_admin.php?kelurahan=" . urlencode($_GET['kelurahan'] ?? ''));
    exit;
}
?>

<?php

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

if (isset($_GET['hapus'])) {
    $index = $_GET['hapus'];
    array_splice($data, $index, 1);
    file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT));
    header("Location: tambahdata.php?type=$type");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($type == 'pendidikan') {
        $record = [
            'jenjang' => $_POST['jenjang'],
            'jumlah'  => $_POST['jumlah'],
        ];
    } elseif ($type == 'kesehatan') {
        $record = [
            'fasilitas_kesehatan' => $_POST['fasilitas_kesehatan'],
            'jumlah'              => $_POST['jumlah_kesehatan'],
        ];
    } elseif ($type == 'ekonomi') {
        $record = [
            'fasilitas' => $_POST['fasilitas'],
            'jumlah'    => $_POST['jumlah_fasilitas'],
        ];
    } elseif ($type == 'kependudukan') {
        $record = [
            'jumlah_penduduk' => $_POST['jumlah_penduduk'],
        ];
    }

    if (isset($_POST['edit_index']) && $_POST['edit_index'] !== '') {
        $data[(int)$_POST['edit_index']] = $record;
    } else {
        $data[] = $record;
    }

    file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT));

    // ambil kelurahan dari form hidden
    $kelurahan = isset($_POST['kelurahan']) ? urlencode($_POST['kelurahan']) : '';

    echo "<p>✅ Data berhasil ditambahkan!</p>";
    if ($type == 'pendidikan') {
        echo "<a href='pendidikan.php?kelurahan=$kelurahan' class='btn-kembali'>⬅ Kembali ke Data Pendidikan</a>";
    } else {
        echo "<a href='tambahdata.php?type=$type' class='btn-kembali'>⬅ Kembali</a>";
    }
    exit;
}


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
  <div id="kelurahanList">
  <?php
  $kelurahan = [
    "Balimester", "Batu Ampar", "Baru", "Batuampar", "Bidaracina",
    "Bambu Apus", "Cawang", "Ceger", "Cibubur", "Cipinang",
    "Cipinang Besar Selatan", "Cipinang Besar Utara", "Cipinang Cempedak",
    "Cipinang Melayu", "Cipinang Muara", "Cilangkap", "Ciracas",
    "Duren Sawit", "Dukuh", "Gedong", "Halim Perdana Kusumah",
    "Jatinegara", "Jatinegara Kaum", "Jati", "Kampung Dukuh",
    "Kampung Melayu", "Kayu Manis", "Kayu Putih", "Kebon Manggis",
    "Kramat Jati", "Klender", "Lubang Buaya", "Malaka Jaya",
    "Malaka Sari", "Makasar", "Matraman", "Munjul", "Palmeriam",
    "Pasar Rebo", "Pekayon", "Penggilingan", "Pinang Ranti",
    "Pisangan Baru", "Pondok Bambu", "Pondok Kelapa", "Pondok Kopi",
    "Pulogadung", "Pulo Gebang", "Rambutan", "Rawa Bunga",
    "Rawa Terate", "Rawamangun", "Setu", "Susukan",
    "Utan Kayu Selatan", "Utan Kayu Utara"
  ];

  foreach ($kelurahan as $nama) {
      echo "<a href='data.php?kelurahan=" . urlencode($nama) . "'>$nama</a>";
  }
  ?>
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
                <input type="text" name="fasilitas_kesehatan" placeholder="Nama Fasilitas" value="<?= $edit_data['fasilitas_kesehatan'] ?? '' ?>" required class="w-full px-3 py-2 border rounded">
                <input type="number" name="jumlah_kesehatan" placeholder="Jumlah" value="<?= $edit_data['jumlah'] ?? '' ?>" required class="w-full px-3 py-2 border rounded">

            <?php elseif ($type == 'ekonomi'): ?>
                <input type="text" name="fasilitas" placeholder="Nama Fasilitas Ekonomi" value="<?= $edit_data['fasilitas'] ?? '' ?>" required class="w-full px-3 py-2 border rounded">
                <input type="number" name="jumlah_fasilitas" placeholder="Jumlah" value="<?= $edit_data['jumlah'] ?? '' ?>" required class="w-full px-3 py-2 border rounded">

            <?php elseif ($type == 'kependudukan'): ?>
                <input type="number" name="jumlah_penduduk" placeholder="Jumlah Penduduk" value="<?= $edit_data['jumlah_penduduk'] ?? '' ?>" required class="w-full px-3 py-2 border rounded">
            <?php endif ?>
    <input type="hidden" name="kelurahan" value="<?php echo htmlspecialchars($_GET['kelurahan'] ?? ''); ?>">

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                <?= $edit_data ? "Update" : "Simpan" ?>
            </button>
        </form>

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
                            <th class="border px-2 py-1">Jumlah Penduduk</th>
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
