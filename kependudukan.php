<?php
$type = "kependudukan"; 
$kelurahan = $_GET['kelurahan'] ?? '';
if (is_array($kelurahan)) {
    $kelurahan = reset($kelurahan);
}
?>

<?php
session_start();
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
        echo "<a href='data.php?kelurahan=" . urlencode($nama) . "'>$nama</a>";
    }
    ?>
        </div>
      </div>
    </div>
  </div>

  <?php
  $kelurahan = $_GET['kelurahan'];
  ?>
  <h2>Grafik Statistik Kependudukan - <?php echo htmlspecialchars($kelurahan); ?></h2>
  <canvas id="chartKependudukan"></canvas>
  <br>
  <center><a href="download.php?kategori=kependudukan&kelurahan=<?php echo urlencode($kelurahan); ?>" class="btn-download">⬇️ Download CSV</a></center>
  <center><button class="btn-download" data-chart="chartKependudukan">📥 Download PNG</button></center>

<h3>Belum ada data?
  <a href="tambahdata.php?type=kependudukan&kelurahan=<?php echo urlencode($kelurahan); ?>">tambah data</a>
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

    
    const ctx = document.getElementById("chartKependudukan").getContext("2d");

fetch("getdata.php?kategori=kependudukan&kelurahan=<?php echo urlencode($kelurahan); ?>")
  .then(res => res.json())
  .then(data => {
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: data.labels,
        datasets: [
          {
            label: 'Laki-Laki',
            data: data.laki.map(v => -v),
            backgroundColor: 'rgba(52, 152, 219, 0.7)',
            borderColor: 'rgba(41, 128, 185, 1)',
            borderWidth: 1
          },
          {
            label: 'Perempuan',
            data: data.perempuan,
            backgroundColor: 'rgba(231, 76, 60, 0.7)',
            borderColor: 'rgba(192, 57, 43, 1)',
            borderWidth: 1
          },
          {
            label: 'Total',
            data: data.total,
            backgroundColor: 'rgba(155, 89, 182, 0.5)',
            borderColor: 'rgba(142, 68, 173, 1)',
            borderWidth: 1
          }
        ]
      },
      options: {
        indexAxis: 'y',
        responsive: true,
        scales: {
          x: {
            stacked: true,
            ticks: {
              callback: function(value) {
                return Math.abs(value); 
              }
            }
          },
          y: {
            stacked: true
          }
        }
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
  <center><a href="data.php?type=<?php echo urlencode($type); ?>&kelurahan=<?php echo urlencode($kelurahan); ?>" class="btn-kembali bg-blue-500 hover:bg-blue-600 text-white text-lg font-semibold px-9 py-4 rounded-lg inline-block">
   ⬅ Kembali ke  Kategori Data 
</a></center>

</body>
</html>
