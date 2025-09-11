<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['kelurahan'] !== ($_GET['kelurahan'] ?? '')) {
    header("Location: login_admin.php?kelurahan=" . urlencode($_GET['kelurahan'] ?? ''));
    exit;
}
?>

<?php
$type = "tambahdata"; 
$kelurahan = $_GET['kelurahan'] ?? '';
if (is_array($kelurahan)) {
    $kelurahan = reset($kelurahan);
}
?>

<?php
$type = $_GET['type'] ?? '';
if ($type === '') {
    $basename = basename($_SERVER['PHP_SELF'], '.php');
    $mapType = [
        'kependudukan' => 'kependudukan',
        'pendidikan'   => 'pendidikan',
        'kesehatan'    => 'kesehatan',
        'ekonomi'      => 'ekonomi',
        'tambahdata'   => ''
    ];
    $type = $mapType[$basename] ?? '';
}

$data_file = '';
if ($type !== '') {
    $data_file = "data_{$type}_" . urlencode($kelurahan) . ".json";
}

$data = [];
if ($data_file && file_exists($data_file)) {
    $json = file_get_contents($data_file);
    $data = json_decode($json, true) ?? [];
}

// 🔹 FIX: Hapus data lebih aman
if (isset($_GET['hapus'])) {
    $index = (int) $_GET['hapus'];
    if (isset($data[$index])) {
        array_splice($data, $index, 1);
        file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT));
    }

    $kelurahan = urlencode($_GET['kelurahan'] ?? '');
    header("Location: tambahdata.php?type=$type&kelurahan=$kelurahan");
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
            'jenis_kelamin'   => $_POST['jenis_kelamin'],
            'kelompok_umur'   => $_POST['kelompok_umur'],
            'jumlah_penduduk' => $_POST['jumlah_penduduk'],
        ];
    }

    if (isset($_POST['edit_index']) && $_POST['edit_index'] !== '') {
        $data[(int)$_POST['edit_index']] = $record;
    } else {
        $data[] = $record;
    }

    file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT));

    $kelurahan = isset($_POST['kelurahan']) ? urlencode($_POST['kelurahan']) : '';

    echo "<p>✅ Data berhasil ditambahkan!</p>";
    if ($type == 'pendidikan') {
        echo "<a href='pendidikan.php?kelurahan=$kelurahan' class='btn-kembali'>⬅ Kembali</a>";
    } elseif ($type == 'ekonomi') {
        echo "<a href='ekonomi.php?kelurahan=$kelurahan' class='btn-kembali'>⬅ Kembali</a>";
    } elseif ($type == 'kesehatan') {
        echo "<a href='kesehatan.php?kelurahan=$kelurahan' class='btn-kembali'>⬅ Kembali</a>";
    } elseif ($type == 'kependudukan') {
        echo "<a href='kependudukan.php?kelurahan=$kelurahan' class='btn-kembali'>⬅ Kembali</a>";
    }
    exit;
}

// 🔹 FIX: Edit data lebih aman
$edit_data = null;
$edit_index = null;
if (isset($_GET['edit'])) {
    $edit_index = (int) $_GET['edit'];
    if (isset($data[$edit_index])) {
        $edit_data = $data[$edit_index];
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="bg-[#e0f2fe] dark:bg-gray-900">
<head>
    <meta charset="UTF-8">
    <title>Input data</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="sidebar hidden" id="sidebar">
    <h4>Statistik</h4>
    <a href="pendidikan.php">📚 Pendidikan</a>
    <a href="kependudukan.php">🧑‍🤝‍🧑 Kependudukan</a>
    <a href="ekonomi.php">💼 Ekonomi</a>
    <a href="kesehatan.php">🏥 Kesehatan</a>
</div>

<div class="navbar">
    <span class="toggle-btn" onclick="toggleSidebar()">☰</span>
    <a href="index.php" class="beranda-link">Beranda</a>
    
    <div class="admin-auth">
      <?php if(isset($_SESSION['admin'])): ?>
        <div class="admin-menu">
          <button class="admin-btn" onclick="toggleAdminDropdown()">
            <?php echo htmlspecialchars($_SESSION['admin']); ?> ⬇
          </button>
          <div id="adminDropdown" class="admin-dropdown">
            <a href="logout_admin.php" class="admin-logout">Logout</a>
          </div>
        </div>
      <?php else: ?>
        <a href="login_admin.php" class="admin-login">Login Admin</a>
      <?php endif; ?>
    </div>

    <div class="dropdown">
      <input type="text" class="search-input" id="searchKel" onkeyup="filterKelurahan()" placeholder="Cari kelurahan...">
      <div class="dropdown-content" id="kelurahanList">
        <?php
        $listKelurahan = [
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

        foreach ($listKelurahan as $nama) {
            echo "<a href='data.php?kelurahan=" . urlencode($nama) . "'>$nama</a>";
        }
        ?>
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
                <input type="text" name="fasilitas_kesehatan" placeholder="Nama Fasilitas" value="<?= $edit_data['fasilitas_kesehatan'] ?? '' ?>" required class="w-full px-3 py-2 border rounded">
                <input type="number" name="jumlah_kesehatan" placeholder="Jumlah" value="<?= $edit_data['jumlah'] ?? '' ?>" required class="w-full px-3 py-2 border rounded">

            <?php elseif ($type == 'ekonomi'): ?>
                <input type="text" name="fasilitas" placeholder="Nama Fasilitas Ekonomi" value="<?= $edit_data['fasilitas'] ?? '' ?>" required class="w-full px-3 py-2 border rounded">
                <input type="number" name="jumlah_fasilitas" placeholder="Jumlah" value="<?= $edit_data['jumlah'] ?? '' ?>" required class="w-full px-3 py-2 border rounded">

            <?php elseif ($type == 'kependudukan'): ?>
                <select name="jenis_kelamin" required class="w-full px-3 py-2 border rounded">
                    <option value="">Jenis Kelamin</option>
                    <?php foreach (['Perempuan','Laki-laki'] as $j): ?>
                        <option value="<?= $j ?>" <?= isset($edit_data['jenis_kelamin']) && $edit_data['jenis_kelamin'] === $j ? 'selected' : '' ?>><?= $j ?></option>
                    <?php endforeach ?>
                </select>
                <select name="kelompok_umur" required class="w-full px-3 py-2 border rounded">
                    <option value="">Kelompok Umur</option>
                    <?php foreach (['00-04','05-09','10-14','15-19','20-24','25-29','30-34','35-39','40-44','45-49','50-54','55-59','60-64','65-69','70-75','75+'] as $j): ?>
                        <option value="<?= $j ?>" <?= isset($edit_data['kelompok_umur']) && $edit_data['kelompok_umur'] === $j ? 'selected' : '' ?>><?= $j ?></option>
                    <?php endforeach ?>
                </select>
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
                            <th class="border px-2 py-1">Jenis Kelamin</th>
                            <th class="border px-2 py-1">Kelompok Umur</th>
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
                                <a href="?type=<?= urlencode($type) ?>&edit=<?= $i ?>&kelurahan=<?= urlencode($_GET['kelurahan'] ?? '') ?>" 
                                   class="text-blue-500 hover:underline">Edit</a>
                                
                                <a href="?type=<?= urlencode($type) ?>&hapus=<?= $i ?>&kelurahan=<?= urlencode($_GET['kelurahan'] ?? '') ?>" 
                                   onclick="return confirm('Yakin ingin menghapus?')" 
                                   class="text-red-500 hover:underline">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>

            <a href="<?php echo $type; ?>.php?kelurahan=<?php echo urlencode($kelurahan); ?>" class="btn-kembali">
                ⬅ Kembali ke Data <?php echo ucfirst($type); ?>
            </a>
        </div>
    </main>
</div>

<script>
function toggleAdminDropdown() {
  document.getElementById("adminDropdown").classList.toggle("show");
}
</script>

</body>
</html>
