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

    <div class="right-section">
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

      <!-- Search -->
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
  <!-- Content -->
  <div class="content">
    <h2>Selamat Datang di Statistik Jaktim</h2>

    <h3>📖 Panduan Penggunaan Website Statistik</h3>
    <p>
      web ini dibuat dengan konsep sederhana agar mudah dipahami oleh siapa pun, baik pengguna yang sudah terbiasa dengan teknologi maupun yang baru pertama kali mencoba. Berikut adalah langkah-langkah yang bisa diikuti:
    </p>

    <h4>🔍 Pilih Kelurahan</h4>
    <ul>
      <li>Pada bagian kanan atas halaman, terdapat kolom pencarian kelurahan.</li>
      <li>Ketik nama kelurahan yang ingin dicari, lalu pilih dari daftar yang muncul.</li>
      <li>Setelah memilih kelurahan, sistem akan otomatis mengarahkan Anda ke halaman berikutnya.</li>
    </ul>

    <h4>📂 Pilih Kategori Data</h4>
    <ul>
      <li>Setelah memilih kelurahan, Anda akan berada di halaman Pilih Kategori Data.</li>
      <li>Terdapat beberapa pilihan kategori: Kependudukan, Pendidikan, Ekonomi, dan Kesehatan.</li>
      <li>Pilih salah satu kategori sesuai kebutuhan Anda.</li>
    </ul>

    <h4>📊 Tampilkan Data</h4>
    <ul>
      <li>Setelah memilih kategori, tekan tombol <b>“Tampilkan Data”</b>.</li>
      <li>Sistem akan menampilkan grafik serta tabel berisi informasi sesuai kategori yang dipilih.</li>
      <li>Grafik memudahkan pembaca memahami data secara visual, sedangkan tabel menampilkan detail angka secara lengkap.</li>
    </ul>

    <h4>🔄 Ganti Kelurahan</h4>
    <ul>
      <li>Jika ingin melihat data kelurahan lain, cukup kembali ke kolom pencarian kelurahan dan pilih yang baru.</li>
      <li>Sistem akan otomatis memuat ulang kategori data untuk kelurahan tersebut.</li>
    </ul>

    <h4>📌 Navigasi Tambahan</h4>
    <ul>
      <li>Gunakan menu di sidebar untuk menuju halaman <b>Tentang</b> atau <b>Panduan</b>.</li>
      <li>Halaman ini membantu pengguna memahami aplikasi secara lebih mendalam

  <!-- Scripts -->
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
