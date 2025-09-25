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

  <div class="content">
    <h2>Selamat Datang di Statistik Jaktim</h2>
    <p>📖 Panduan Penggunaan Website Statistik
1. Cara Masuk ke Website

Buka browser (misalnya Chrome, Edge, atau Firefox).

Ketik alamat website statistik di kolom URL, contoh: http://localhost/statistik.

Tekan Enter, maka halaman utama website akan terbuka.

2. Navigasi Menu

Beranda → Menampilkan ringkasan informasi utama.

Data → Menampilkan tabel data statistik.

Grafik → Menampilkan data dalam bentuk diagram batang, garis, atau pie chart.

Panduan → Menampilkan petunjuk penggunaan website.

3. Melihat Data Statistik

Pilih menu Data.

Gunakan Dropdown Tahun untuk memilih periode data yang ingin ditampilkan.

Gunakan Kolom Pencarian untuk mencari data tertentu dengan cepat.

4. Melihat Grafik Statistik

Pilih menu Grafik.

Pilih jenis grafik yang tersedia (misalnya: diagram batang, garis, atau pie).

Arahkan kursor (hover) ke bagian grafik untuk melihat detail angka.

5. Fitur Tambahan

Ekspor Data → Unduh data ke dalam file Excel/CSV.

Filter Data → Saring data berdasarkan kategori tertentu.

Cetak Laporan → Cetak tabel atau grafik langsung dari browser.

6. Bantuan

Jika mengalami kendala, silakan hubungi admin melalui menu Kontak atau kirim email ke admin@statistik.com.</p>
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
