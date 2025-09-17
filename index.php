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

  <!-- Sidebar -->
  <div class="sidebar hidden" id="sidebar">
    <h4>Statistik</h4>
    <a href="pendidikan.php">📚 Pendidikan</a>
    <a href="kependudukan.php">🧑‍🤝‍🧑 Kependudukan</a>
    <a href="ekonomi.php">💼 Ekonomi</a>
    <a href="kesehatan.php">🏥 Kesehatan</a>
  </div>

  <!-- Navbar -->
  <div class="navbar">
    <span class="toggle-btn" onclick="toggleSidebar()">☰</span>
    <a href="index.php" class="beranda-link">Beranda</a>

    <!-- Auth -->
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
        <div class="guest-menu">
          <a href="login.php" class="btn-login">Login</a>
          <a href="register.php" class="btn-register">Register</a>
        </div>
      <?php endif; ?>
    </div>

    <!-- Dropdown Search Kelurahan -->
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

  <!-- Konten Utama -->
  <div class="content">
    <h2>Selamat Datang di Statistik Jaktim</h2>
    <p>Silakan pilih kategori atau kelurahan dari navigasi di atas.</p>
  </div>

  <!-- Script -->
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

</body>
</html>
