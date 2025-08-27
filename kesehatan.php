<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Grafik Kesehatan</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

   <div class="auth-buttons">
      <?php if(isset($_SESSION['username'])): ?>
        <div class="dropdown">
          <button class="btn-login">
            <?php echo htmlspecialchars($_SESSION['username']); ?> ⬇
          </button>
          <div class="dropdown-content">
            <a href="logout.php">Logout</a>
          </div>
        </div>
      <?php else: ?>
        <a href="login.php" class="btn-login">Login</a>
        <a href="register.php" class="btn-register">Register</a>
      <?php endif; ?>
    </div>

    <div class="dropdown">
      <input type="text" class="search-input" id="searchKel" onkeyup="filterKelurahan()" placeholder="Cari kelurahan...">
      <div class="dropdown-content">
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
            echo "<a href='pendidikan.php?kelurahan=" . urlencode($nama) . "'>$nama</a>";
        }
        ?>
        </div>
      </div>
    </div>
  </div>
  
  <?php
  $kelurahan = $_GET['kelurahan'];
  ?>
  <h2>Grafik Statistik Kesehatan - <?php echo htmlspecialchars($kelurahan); ?></h2>
  <canvas id="chartKesehatan"></canvas>
  <br>
  <center><a href="download.php?kategori=kesehatan&kelurahan=<?php echo urlencode($kelurahan); ?>" class="btn-download">⬇️ Download CSV</a></center>
  <center><button class="btn-download" data-chart="chartKesehatan">📥 Download PNG</button></center>


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

    const ctx = document.getElementById("chartKesehatan").getContext("2d");

    fetch("getdata.php?kategori=kesehatan&kelurahan=<?php echo urlencode($kelurahan); ?>")
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
</body>
</html>