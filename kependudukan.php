<?php
session_start();
$type = "kependudukan"; 
$kelurahan = $_GET['kelurahan'] ?? '';
if (is_array($kelurahan)) {
    $kelurahan = reset($kelurahan);
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
        const response = await fetch("getdata.php?kategori=kependudukan&kelurahan=<?php echo urlencode($kelurahan); ?>");
        if (!response.ok) throw new Error("Gagal ambil data");
        return await response.json();
      } catch (err) {
        console.error("Error fetch data:", err);
        return null;
      }
    }

    async function renderChart() {
      const data = await fetchData();
      if (!data) return;

      const labels = data.labels;
      // laki-laki dibuat negatif biar tampil ke kiri
      const dataLaki = data.laki_laki.map(v => -Math.abs(v));
      const dataPerempuan = data.perempuan.map(v => Math.abs(v));

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
                label: function(context) {
                  return context.dataset.label + ": " + Math.abs(context.parsed.x);
                }
              }
            }
          },
          scales: {
            x: {
              stacked: true,
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
</body>
</html>
