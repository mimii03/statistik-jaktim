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
    $redirectUrl = "ekonomi.php";
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
  <title>Grafik Ekonomi</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="style.css">
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

  <div class="page-wrapper">
  <?php
  $kelurahan = $_GET['kelurahan'];
  ?>
  <h2>Grafik Statistik Ekonomi - <?php echo htmlspecialchars($kelurahan); ?></h2>
  <canvas id="chartEkonomi"></canvas>
  <br>
  <center><a href="download.php?kategori=ekonomi&kelurahan=<?php echo urlencode($kelurahan); ?>" class="btn-download">Download CSV</a></center>?
  <center><button class="btn-download" data-chart="chartEkonomi">📥 Download PNG</button></center>

  <h3>Belum ada data?
  <a href="tambahdata.php?type=ekonomi&kelurahan=<?php echo urlencode($kelurahan); ?>">tambah data</a>
</h3>
      </div>
  
  <script>
    function toggleSidebar() {
      document.getElementById("sidebar").classList.toggle("hidden");
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

    const ctx = document.getElementById("chartEkonomi").getContext("2d");

    fetch("getdata.php?kategori=ekonomi&kelurahan=<?php echo urlencode($kelurahan); ?>")
      .then(res => res.json())
      .then(data => {
        new Chart(ctx, {
          type: 'bar',
          data: {
            labels: data.labels,
            datasets: [{
              label: 'Jumlah',
              data: data.jumlah,
              backgroundColor: [
                'rgba(52, 152, 219, 0.7)',
                'rgba(46, 204, 113, 0.7)',
                'rgba(231, 76, 60, 0.7)',
                'rgba(241, 196, 15, 0.7)',
                'rgba(155, 89, 182, 0.7)',
                'rgba(230, 126, 34, 0.7)'
              ],
              borderColor: [
                'rgba(41, 128, 185, 1)',
                'rgba(39, 174, 96, 1)',
                'rgba(192, 57, 43, 1)',
                'rgba(243, 156, 18, 1)',
                'rgba(142, 68, 173, 1)',
                'rgba(211, 84, 0, 1)'
              ],
              borderWidth: 1
            }]
          },
          options: {
            indexAxis: 'y',
            responsive: true
          }
        });
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

  </script>

   <script>
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
  <center><a href="data.php?type=<?php echo urlencode($type); ?>&kelurahan=<?php echo urlencode($kelurahan); ?>" class="btn-kembali">
   ⬅ Kembali ke  Kategori Data 
</a></center>

  <footer>
  <div class="footer">
    <p>&copy; Statistik Jakarta Timur.<br>
    Dikembangkan oleh Sudin Kominfotik Jakarta Timur.<br>
    Hak Cipta Dilindungi Undang-Undang.</p>
    </div>
</footer>


</body>
</html>