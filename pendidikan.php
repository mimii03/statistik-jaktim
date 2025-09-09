<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['login'])) {
    $redirectUrl = "pendidikan.php";
    if (isset($_GET['kelurahan'])) {
        $redirectUrl .= "?kelurahan=" . urlencode($_GET['kelurahan']);
    }
    header("Location: login.php?redirect=" . urlencode($redirectUrl));
    exit;
}

$kelurahan = isset($_GET['kelurahan']) ? $_GET['kelurahan'] : '';
$sql = "SELECT jenis_pendidikan, jumlah FROM pendidikan WHERE kelurahan='$kelurahan'";
$result = mysqli_query($conn, $sql);

$labels = [];
$data   = [];

while ($row = mysqli_fetch_assoc($result)) {
    $labels[] = $row['jenis_pendidikan'];
    $data[]   = $row['jumlah'];
}
?>

<?php
$type = "pendidikan"; 
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
  <title>Grafik Pendidikan</title>
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
        echo "<a href='data.php?kelurahan=" . urlencode($nama) . "'>$nama</a>";
    }
    ?>
        </div>
      </div>
    </div>
  </div>

  <h2>Grafik Statistik Pendidikan - <?php echo htmlspecialchars($kelurahan); ?></h2>
  <canvas id="chartPendidikan"></canvas>

  <center><a href="download.php?kategori=pendidikan&kelurahan=<?php echo urlencode($kelurahan); ?>" class="btn-download">⬇️ Download CSV</a></center>
  <center><button class="btn-download" data-chart="chartPendidikan">📥 Download PNG</button></center>

<h3>Belum ada data?
  <a href="tambahdata.php?type=pendidikan&kelurahan=<?php echo urlencode($kelurahan); ?>">tambah data</a>
</h3>

<script>
function toggleSidebar() {
  document.getElementById("sidebar").classList.toggle("hidden");
}

function filterKelurahan() {
  const input = document.getElementById("searchKel").value.toUpperCase();
  const links = document.getElementById("kelurahanList").getElementsByTagName("a");
  for (let i = 0; i < links.length; i++) {
    let txtValue = links[i].textContent || links[i].innerText;
    links[i].style.display = txtValue.toUpperCase().indexOf(input) > -1 ? "" : "none";
  }
}

fetch("getdata.php?kategori=pendidikan&kelurahan=<?php echo urlencode($kelurahan); ?>")
  .then(res => res.json())
  .then(data => {
    const ctx = document.getElementById("chartPendidikan").getContext("2d");
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: data.labels,
        datasets: [{
          label: 'Jumlah Pendidikan',
          data: data.jumlah,
          backgroundColor: [
            'rgba(231, 76, 60, 0.7)',
            'rgba(241, 196, 15, 0.7)',
            'rgba(52, 152, 219, 0.7)',
            'rgba(46, 204, 113, 0.7)'
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
    if (!canvas) return;
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

// Tutup dropdown kalau klik di luar
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

</body>
</html>
