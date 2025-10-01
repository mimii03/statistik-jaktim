<?php
$type = "ekonomi"; 
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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Grafik Kependudukan</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    
  <div class="sidebar hidden" id="sidebar">
    <a href="tentang.php"> Tentang </a>
    <a href="panduan.php"> Panduan </a>
  </div>

  <div class="navbar">
  <span class="toggle-btn" onclick="toggleSidebar()">☰</span>
  <a href="index.php" class="beranda-link">Beranda</a>

  <div class="right-section">
    <div class="auth-buttons" style="margin-bottom: 15px;">
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
        <div class="guest-menu">
          <a href="login.php" class="btn-login">Login</a>
          <a href="register.php" class="btn-register">Register</a>
        </div>
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

    <?php $kelurahan = $_GET['kelurahan'] ?? ''; ?>

    <h2>Grafik Statistik Kependudukan - <?php echo htmlspecialchars($kelurahan); ?></h2>
    <canvas id="chartKependudukan"></canvas>
     <br>
  <center>
<a href="download_json.php?kategori=kependudukan&kelurahan=<?php echo urlencode($kelurahan); ?>" class="btn-download">
    Download CSV
</a>
  </center>
  <center>
    <button class="btn-download" data-chart="chartPendidikan">📥 Download PNG</button>
  </center>

    <h3>
        Belum ada data? <a href="tambahdata.php?type=kependudukan&kelurahan=<?php echo urlencode($kelurahan); ?>">tambah data</a>
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
                            ticks: {
                                callback: value => Math.abs(value)
                            },
                            title: { display: true, text: "Jumlah Penduduk" }
                        },
                        y: {
                            title: { display: true, text: "Kelompok Umur" }
                        }
                    }
                }
            });
        }

        renderChart();
    </script>
      <center><a href="data.php?type=<?php echo urlencode($type); ?>&kelurahan=<?php echo urlencode($kelurahan); ?>" class="btn-kembali">
   ⬅ Kembali ke  Kategori Data 
</a></center>

</body>
</html>
