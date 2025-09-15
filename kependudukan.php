<?php
$type = "kependudukan"; 
$kelurahan = $_GET['kelurahan'] ?? '';
if (is_array($kelurahan)) {
    $kelurahan = reset($kelurahan);
}
?>

<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['login'])) {
    $redirectUrl = "kependudukan.php";
    if (isset($_GET['kelurahan'])) {
        $redirectUrl .= "?kelurahan=" . urlencode($_GET['kelurahan']);
    }
    header("Location: login.php?redirect=" . urlencode($redirectUrl));
    exit;
}

?>



<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Grafik Kependudukan</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    
<div class="auth-buttons">
  <?php if(isset($_SESSION['username'])): ?>
    <div class="user-menu">
      <button class="user-btn" onclick="toggleDropdown()">
        <?php echo htmlspecialchars($_SESSION['username']); ?> ⬇
      </button>
      <div id="userDropdown" class="user-dropdown">
        <a href="logout.php" class="logout-btn">Logout</a>
      </div>
    </div>
  <?php else: ?>
    <a href="login.php" class="btn-login">Login</a>
    <a href="register.php" class="btn-register">Register</a>
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
            echo "<a href='kependudukan.php?kelurahan=" . urlencode($nama) . "'>$nama</a>";
        }
        ?>
      </div>
    </div>
  </div>

  <?php $kelurahan = $_GET['kelurahan']; ?>
  <h2>Grafik Statistik Kependudukan - <?php echo htmlspecialchars($kelurahan); ?></h2>
  <canvas id="chartKependudukan"></canvas>
  <br>
  <center><a href="download.php?kategori=kependudukan&kelurahan=<?php echo urlencode($kelurahan); ?>" class="btn-download">⬇️ Download CSV</a></center>
  <center><button class="btn-download" data-chart="chartKependudukan">📥 Download PNG</button></center>

  <h3>Belum ada data?
    <a href="tambahdata.php?type=kependudukan&kelurahan=<?php echo urlencode($kelurahan); ?>">tambah data</a>
  </h3>

 <script>
async function fetchData() {
  try {
const response = await fetch("getdata.php?kategori=<?php echo urlencode($type); ?>&kelurahan=<?php echo urlencode($kelurahan); ?>");
    const result = await response.json();
    return result;
  } catch (err) {
    console.error("Gagal mengambil data:", err);
    return null;
  }
}

async function renderChart() {
  const data = await fetchData();
  if (!data || !data.labels || !data.laki_laki || !data.perempuan) {
    console.error("Data kosong atau format tidak sesuai:", data);
    return;
  }

  // Gabungkan data
  let gabung = data.labels.map((label, i) => ({
    label: label.trim(),
    laki: Number(data.laki_laki[i]) || 0,
    perempuan: Number(data.perempuan[i]) || 0
  }));

  // Urutkan berdasarkan angka awal kelompok umur (contoh "0-4", "5-9", dst)
  gabung.sort((a, b) => {
    const numA = parseInt(a.label);
    const numB = parseInt(b.label);
    return numA - numB;
  });

  // Pisahkan setelah urut
  const labels = gabung.map(d => d.label);
  const dataLaki = gabung.map(d => -Math.abs(d.laki));       // laki dibuat negatif
  const dataPerempuan = gabung.map(d => Math.abs(d.perempuan));

  // Cari nilai max untuk skala X
  const maxValue = Math.max(
    ...gabung.map(d => d.laki),
    ...gabung.map(d => d.perempuan)
  );

  const ctx = document.getElementById("chartKependudukan").getContext("2d");

  new Chart(ctx, {
    type: "bar",
    data: {
      labels: labels,
      datasets: [
        {
          label: "Laki-laki",
          data: dataLaki,
          backgroundColor: "rgba(54, 162, 235, 0.7)"
        },
        {
          label: "Perempuan",
          data: dataPerempuan,
          backgroundColor: "rgba(255, 99, 132, 0.7)"
        }
      ]
    },
    options: {
      indexAxis: "y",
      responsive: true,
      plugins: {
        tooltip: {
          callbacks: {
            label: function (context) {
              return context.dataset.label + ": " + Math.abs(context.parsed.x);
            }
          }
        }
      },
      scales: {
        x: {
          stacked: true,
          min: -(maxValue + 200),
          max: maxValue + 200,
          ticks: {
            callback: value => Math.abs(value)
          },
          title: { display: true, text: "Jumlah Penduduk" }
        },
        y: {
          stacked: true,
          title: { display: true, text: "Kelompok Umur" }
        }
      }
    }
  });
}

renderChart();
</script>
  <center><a href="data.php?type=<?php echo urlencode($type); ?>&kelurahan=<?php echo urlencode($kelurahan); ?>" class="btn-kembali bg-blue-500 hover:bg-blue-600 text-white text-lg font-semibold px-9 py-4 rounded-lg inline-block">
   ⬅ Kembali ke  Kategori Data 
</a></center>
</body>
</html>
