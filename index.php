<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Statistik Jaktim</title>
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

  <div class="content">
    <h2>Selamat Datang di Statistik Jaktim</h2>
    <p>Silakan pilih kategori kelurahan dari navigasi bar di atas.</p>
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
  </script>
</body>
</html>
