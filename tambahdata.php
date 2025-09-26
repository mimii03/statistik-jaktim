<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['kelurahan'] !== ($_GET['kelurahan'] ?? '')) {
    $current_url = $_SERVER['REQUEST_URI']; 
    header("Location: login_admin.php?kelurahan=" . urlencode($_GET['kelurahan'] ?? '') . "&redirect=" . urlencode($current_url));
    exit;
}

$type = $_GET['type'] ?? '';
$kelurahan = $_GET['kelurahan'] ?? '';
if (is_array($kelurahan)) {
    $kelurahan = reset($kelurahan);
}

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
$all_data = [];
$data = [];

if ($type !== '') {
    $data_file = "data_{$type}.json";

    if (!file_exists($data_file)) {
        file_put_contents($data_file, json_encode([]));
    }}

    $json_all = file_get_contents($data_file);
    $all_data = json_decode($json_all, true);
    if (!is_array($all_data)) {
        $all_data = [];
    }

    if (in_array($type, ['kependudukan','pendidikan','kesehatan','ekonomi'])) {
        $data = array_values(array_filter($all_data, function($row) use ($kelurahan) {
            return isset($row['kelurahan']) && $row['kelurahan'] === $kelurahan;
        }));
    } else {
        $data = $all_data;
    }

if (isset($_GET['hapus'])) {
    $index = (int) $_GET['hapus'];
    if (isset($data[$index])) {
        $old = $data[$index];
        foreach ($all_data as $key => $row) {
            if ($row == $old) {
                unset($all_data[$key]);
                break;
            }
        }
        file_put_contents($data_file, json_encode(array_values($all_data), JSON_PRETTY_PRINT));
    }
    header("Location: tambahdata.php?type=$type&kelurahan=" . urlencode($kelurahan));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($type == 'pendidikan') {
        $record = [
            'kelurahan' => $kelurahan,
            'jenjang'   => $_POST['jenjang'] ?? '',
            'jumlah'    => (int)($_POST['jumlah'] ?? 0),
        ];
    } elseif ($type == 'kesehatan') {
        $record = [
            'kelurahan'           => $kelurahan,
            'fasilitas_kesehatan' => $_POST['fasilitas_kesehatan'] ?? '',
            'jumlah'              => (int)($_POST['jumlah_kesehatan'] ?? 0),
        ];
    } elseif ($type == 'ekonomi') {
        $record = [
            'kelurahan' => $kelurahan,
            'fasilitas' => $_POST['fasilitas'] ?? '',
            'jumlah'    => (int)($_POST['jumlah_fasilitas'] ?? 0),
        ];
    } elseif ($type == 'kependudukan') {
        $record = [
            'kelurahan'       => $kelurahan,
            'jenis_kelamin'   => $_POST['jenis_kelamin'] ?? '',
            'kelompok_umur'   => $_POST['kelompok_umur'] ?? '',
            'jumlah_penduduk' => (int)($_POST['jumlah_penduduk'] ?? 0),
        ];
    }

    if (isset($_POST['edit_index']) && $_POST['edit_index'] !== '') {
        $old = $data[(int)$_POST['edit_index']];
        foreach ($all_data as $key => $row) {
            if ($row == $old) {
                $all_data[$key] = $record;
                break;
            }
        }
    } else {
        $all_data[] = $record;
    }

    file_put_contents($data_file, json_encode(array_values($all_data), JSON_PRETTY_PRINT));
    header("Location: $type.php?kelurahan=" . urlencode($kelurahan));
    exit;
}

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
    <a href="tentang.php"> Tentang </a>
    <a href="pandun.php"> Panduan </a>
  </div>

<div class="navbar">
    <span class="toggle-btn" onclick="toggleSidebar()">☰</span>
    <a href="index.php" class="beranda-link">Beranda</a>

<div class="right-section">
        <div class="auth-buttons" style="margin-bottom: 15px;">
            <?php if(isset($_SESSION['admin'])): ?>
                <div class="admin-menu user-menu">
                    <button class="admin-btn dropdown-toggle" onclick="toggleDropdown('adminDropdown')"> 
                        <?php echo htmlspecialchars($_SESSION['admin']); ?> ⬇
                    </button>
                    <div id="adminDropdown" class="user-dropdown"> 
                        <a href="logout_admin.php" class="admin-logout logout-btn">Logout</a> 
                    </div>
                </div>
            <?php else: ?>
                <a href="login_admin.php?kelurahan=<?= urlencode($kelurahan) ?>" class="btn-login">Login Admin</a>
            <?php endif; ?>
        </div>
        <div class="dropdown">
            <input type="text" class="search-input" id="searchKel" onkeyup="filterKelurahan()" placeholder="Cari kelurahan...">
            <div class="dropdown-content" id="kelurahanList">
                <?php
                $listKelurahan = include 'kelurahan.php';
                if (is_array($listKelurahan)) {
                    foreach ($listKelurahan as $nama) {
                        echo "<a href='data.php?kelurahan=" . urlencode($nama) . "'>" 
                           . htmlspecialchars($nama) . "</a>";
                    }
                } else {
                    echo "<p style='color:red;'>⚠️ Gagal load daftar kelurahan</p>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

  <div class="page-wrapper">
<div class="min-h-screen text-gray-800 dark:text-white">
  <main class="max-w-5xl mx-auto p-6">
    <form method="POST" class="space-y-3 mb-10">
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
          <?php if (!empty($data)): ?>
            <?php foreach ($data as $i => $row): ?>
              <tr class="hover:bg-gray-100 dark:hover:bg-gray-800">
                <?php if ($type == 'pendidikan'): ?>
                  <td class="border px-2 py-1"><?= htmlspecialchars($row['jenjang'] ?? '') ?></td>
                  <td class="border px-2 py-1"><?= htmlspecialchars($row['jumlah'] ?? '') ?></td>
                <?php elseif ($type == 'kesehatan'): ?>
                  <td class="border px-2 py-1"><?= htmlspecialchars($row['fasilitas_kesehatan'] ?? '') ?></td>
                  <td class="border px-2 py-1"><?= htmlspecialchars($row['jumlah'] ?? '') ?></td>
                <?php elseif ($type == 'ekonomi'): ?>
                  <td class="border px-2 py-1"><?= htmlspecialchars($row['fasilitas'] ?? '') ?></td>
                  <td class="border px-2 py-1"><?= htmlspecialchars($row['jumlah'] ?? '') ?></td>
                <?php elseif ($type == 'kependudukan'): ?>
                  <td class="border px-2 py-1"><?= htmlspecialchars($row['jenis_kelamin'] ?? '') ?></td>
                  <td class="border px-2 py-1"><?= htmlspecialchars($row['kelompok_umur'] ?? '') ?></td>
                  <td class="border px-2 py-1"><?= htmlspecialchars($row['jumlah_penduduk'] ?? '') ?></td>
                <?php endif ?>
                <td class="border px-2 py-1 text-center space-x-2">
                  <a href="?type=<?= $type ?>&kelurahan=<?= urlencode($kelurahan) ?>&edit=<?= $i ?>" class="text-blue-500 hover:underline">Edit</a>
                  <a href="?type=<?= $type ?>&kelurahan=<?= urlencode($kelurahan) ?>&hapus=<?= $i ?>" onclick="return confirm('Yakin ingin menghapus?')" class="text-red-500 hover:underline">Hapus</a>
                </td>
              </tr>
            <?php endforeach ?>
          <?php else: ?>
            <tr>
              <td colspan="4" class="text-center py-2">Belum ada data</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
      <a href="<?php echo $type; ?>.php?kelurahan=<?php echo urlencode($kelurahan); ?>" class="btn-kembali">
        ⬅ Kembali ke Data <?php echo ucfirst($type); ?>
      </a>
    </div>
</div>
          </div>

<script>
    function toggleSidebar() {
      const sidebar = document.getElementById("sidebar");
      sidebar.classList.toggle("hidden");
    }

    function filterKelurahan() {
      const input = document.getElementById("searchKel");
      const filter = input.value.toUpperCase();
      const list = document.getElementById("kelurahanList");
      const links = list.getElementsByTagName("a");

      for (let i = 0; i < links.length; i++) {
        let txtValue = links[i].textContent || links[i].innerText;
        links[i].style.display = txtValue.toUpperCase().indexOf(filter) > -1 ? "" : "none";
      }
    }

    function toggleDropdown(dropdownId) {
       const dropdown = document.getElementById(dropdownId);
       if (dropdown) {
           dropdown.classList.toggle("show");
       }
   }
   document.addEventListener('click', function(event) {
       const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
       const dropdownMenus = document.querySelectorAll('.dropdown-menu');
       let clickedToggle = false;
       dropdownToggles.forEach(toggle => {
           if (toggle.contains(event.target)) {
               clickedToggle = true;
           }
       });
       if (!clickedToggle) {
           dropdownMenus.forEach(menu => {
               menu.classList.remove('show');
           });
       }
   });
</script>

<footer>
  <div class="footer">
    <p>&copy; Statistik Jakarta Timur.<br>
    Dikembangkan oleh Sudin Kominfotik Jakarta Timur.<br>
    Hak Cipta Dilindungi Undang-Undang.</p>
    </div>
</footer>


</body>
</html>