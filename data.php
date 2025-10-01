<?php
session_start();
include "koneksi.php";
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pilih Kategori Data</title>
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
  <h1>Pilih Kategori Data</h1>

  <div class="centered-container">
<label for="kategori">Pilih Kategori Data:</label>
<select id="kategori">
  <option value="">-- Pilih Kategori --</option>
  <option value="pendidikan.php">📚 Pendidikan</option>
  <option value="kependudukan.php">🧑‍🤝‍🧑 Kependudukan</option>
  <option value="ekonomi.php">💼 Ekonomi</option>
  <option value="kesehatan.php">🏥 Kesehatan</option>
</select>
</div>

<div style="text-align: center; margin-top: 20px;">
  <button onclick="redirectToData()" class="small-btn">Tampilkan Data</button>
</div>
      </div>
      
<footer>
  <div class="footer">
    <p>&copy; Statistik Jakarta Timur.<br>
    Dikembangkan oleh Sudin Kominfotik Jakarta Timur.<br>
    Hak Cipta Dilindungi Undang-Undang.</p>
    </div>
</footer>

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
    
function redirectToData() {
  const kelurahan = new URLSearchParams(window.location.search).get('kelurahan');
  const kategori = document.getElementById("kategori").value;

  if (kelurahan && kategori) {
    window.location.href = `${kategori}?kelurahan=${kelurahan}`;
  } else {
    alert("Silakan pilih kategori data.");
  }
}

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

</body>
</html>
