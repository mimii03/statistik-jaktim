<?php
session_start();
$type = "pendidikan"; 
$kelurahan = $_GET['kelurahan'] ?? '';
if (is_array($kelurahan)) {
    $kelurahan = reset($kelurahan);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Grafik Pendidikan</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="style.css" />
  <style>
    #chartPendidikan {
      max-width: 800px;
      max-height: 400px;
      margin: 20px auto;
      display: block;
    }
  </style>
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

  <h2>Grafik Statistik Pendidikan - <?php echo htmlspecialchars($kelurahan); ?></h2>
  <canvas id="chartPendidikan"></canvas>
  <br>
  <center>
    <a href="download.php?kategori=pendidikan&kelurahan=<?php echo urlencode($kelurahan); ?>" class="btn-download">⬇️ Download CSV</a>
  </center>
  <center>
    <button class="btn-download" data-chart="chartPendidikan">📥 Download PNG</button>
  </center>

  <h3>Belum ada data?
    <a href="tambahdata.php?type=pendidikan&kelurahan=<?php echo urlencode($kelurahan); ?>">tambah data</a>
  </h3>

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

    const ctx = document.getElementById("chartPendidikan").getContext("2d");

    fetch("getdata.php?kategori=pendidikan&kelurahan=<?php echo urlencode($kelurahan); ?>")
      .then(res => res.json())
      .then(data => {
        console.log("Data dari getdata.php:", data);
        if (!data || !data.labels || !data.jumlah) {
          console.error("Data tidak lengkap atau kosong");
          return;
        }
        new Chart(ctx, {
          type: 'bar',
          data: {
            labels: data.labels,
            datasets: [{
              label: 'Jumlah',
              data: data.jumlah,
              backgroundColor: [
                'rgba(231, 76, 60, 0.7)',
                'rgba(241, 196, 15, 0.7)',
                'rgba(52, 152, 219, 0.7)',
                'rgba(46, 204, 113, 0.7)',
                'rgba(155, 89, 182, 0.7)',
                'rgba(230, 126, 34, 0.7)',
                'rgba(127, 140, 141, 0.7)',
                'rgba(52, 73, 94, 0.7)'
              ],
              borderColor: [
                'rgba(192, 57, 43, 1)',
                'rgba(243, 156, 18, 1)',
                'rgba(41, 128, 185, 1)',
                'rgba(39, 174, 96, 1)',
                'rgba(142, 68, 173, 1)',
                'rgba(211, 84, 0, 1)',
                'rgba(99, 110, 114, 1)',
                'rgba(44, 62, 80, 1)'
              ],
              borderWidth: 1
            }]
          },
          options: {
            indexAxis: 'y',
            responsive: true
          }
        });
      })
      .catch(err => {
        console.error("Error fetch data:", err);
      });

    document.querySelectorAll(".btn-download").forEach(function(button) {
      button.addEventListener("click", function() {
        var chartId = this.getAttribute("data-chart");
        var canvas = document.getElementById(chartId);
        if (!canvas) {
          console.error("Canvas dengan ID " + chartId + " tidak ditemukan!");
          return;
        }
        var link = document.createElement('a');
        link.href = canvas.toDataURL('image/png', 1.0);
        link.download = chartId + ".png"; 
        link.click();
      });
    });

    function toggleDropdown() {
      document.getElementById("userDropdown").classList.toggle("show");
    }

    window.onclick = function(e) {
      if (!e.target.matches('.user-btn')) {
        let dropdowns = document.getElementsByClassName("user-dropdown");
        for (let i = 0; i < dropdowns.length; i++) {
          let openDropdown = dropdowns[i];
          if (openDropdown.classList.contains('show')) {
            openDropdown.classList.remove('show');
          }
        }
      }
    }
  </script>

  <center>
    <a href="data.php?type=<?php echo urlencode($type); ?>&kelurahan=<?php echo urlencode($kelurahan); ?>" class="btn-kembali">
      ⬅ Kembali ke Kategori Data
    </a>
  </center>
</body>
</html>
