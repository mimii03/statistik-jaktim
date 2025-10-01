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
<div class="content">
  <h2 class="guide-title">📖 Panduan Penggunaan Website Statistik</h2>

  <p class="guide-intro">
    Website ini dibuat dengan konsep sederhana agar mudah dipahami oleh siapa pun, baik pengguna yang sudah terbiasa dengan teknologi maupun yang baru pertama kali mencoba. Berikut adalah langkah-langkah yang bisa diikuti:
  </p>

  <div class="guide-steps">
    <div class="guide-step">
      <div class="step-icon">🔍</div>
      <div class="step-content">
        <h3>Pilih Kelurahan</h3>
        <p>Pada bagian kanan atas halaman, terdapat kolom pencarian kelurahan.</p>
        <p>Ketik nama kelurahan yang ingin dicari, lalu pilih dari daftar yang muncul.</p>
        <p>Setelah memilih kelurahan, sistem akan otomatis mengarahkan Anda ke halaman berikutnya.</p>
      </div>
    </div>

    <div class="guide-step">
      <div class="step-icon">📂</div>
      <div class="step-content">
        <h3>Pilih Kategori Data</h3>
        <p>Setelah memilih kelurahan, Anda akan berada di halaman Pilih Kategori Data.</p>
        <p>Terdapat beberapa pilihan kategori: Kependudukan, Pendidikan, Ekonomi, dan Kesehatan.</p>
        <p>Pilih salah satu kategori sesuai kebutuhan Anda.</p>
      </div>
    </div>

    <div class="guide-step">
      <div class="step-icon">📊</div>
      <div class="step-content">
        <h3>Tampilkan Data</h3>
        <p>Setelah memilih kategori, tekan tombol <b>“Tampilkan Data”</b>.</p>
        <p>Sistem akan menampilkan grafik serta tabel berisi informasi sesuai kategori yang dipilih.</p>
      </div>
    </div>

    <div class="guide-step">
      <div class="step-icon">🔄</div>
      <div class="step-content">
        <h3>Ganti Kelurahan</h3>
        <p>Jika ingin melihat data kelurahan lain, cukup kembali ke kolom pencarian kelurahan, ketik nama kelurahan baru yang diinginkan, lalu pilih dari daftar yang tersedia. Sistem akan otomatis menampilkan data sesuai kelurahan yang baru dipilih.</p>
        <p>coba tes doang iniiiiiiiiiiii</p>  
    </div>
    </div>

    <div class="guide-step">
      <div class="step-icon">📌</div>
      <div class="step-content">
        <h3>Navigasi Tambahan</h3>
        <p>Gunakan menu di sidebar untuk menuju halaman <b>Tentang</b> atau <b>Panduan</b>.</p>
        <p>Halaman ini membantu pengguna memahami aplikasi secara lebih mendalam.</p>
      </div> 
      </div>
      <center> <a href="index.php" class="btn-beranda">🏠 Kembali ke Beranda</a></center>

      </div>
    </div>
  </div>
</div>
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

<footer>
  <div class="footer">
    <p>&copy; Statistik Jakarta Timur.<br>
    Dikembangkan oleh Sudin Kominfotik Jakarta Timur.<br>
    Hak Cipta Dilindungi Undang-Undang.</p>
    </div>
</footer>

</body>
</html>
