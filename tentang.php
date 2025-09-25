<<<<<<< HEAD
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tentang</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css" />
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .content-box {
     background: #fff;
     border-radius: 12px;
     padding: 40px;
     box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    margin: 30px auto;
    width: 100%;
     max-width: 1100px;  
    }
    .section-title {
      color: #343a40;
      border-bottom: 2px solid #007bff;
      padding-bottom: 10px;
      margin-bottom: 20px;
    }
    .highlight {
      background-color: #e7f3ff;
      padding: 15px;
      border-left: 4px solid #007bff;
      border-radius: 5px;
      margin: 20px 0;
    }
  </style>
</head>
<body>

  <div class="sidebar hidden" id="sidebar">
    <a href="tentang.php"> Tentang </a>
    <a href="kependudukan.php"> Panduan </a>
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

<div class="container-fluid d-flex justify-content-center">
  <div class="content-box">
      <h1 class="mb-4 section-title">Tentang Statistik Jakarta Timur</h1>
      
      <p class="lead">
        Statistik Jakarta Timur adalah sebuah platform berbasis web yang dirancang untuk memudahkan masyarakat dalam mengakses berbagai data penting yang berhubungan dengan kondisi sosial dan ekonomi di tingkat kelurahan.
      </p>

      <div class="highlight">
        <p>
          Di era digital saat ini, kebutuhan akan data yang cepat, akurat, dan transparan menjadi semakin penting, terutama untuk mendukung proses pengambilan keputusan, penelitian, hingga perencanaan pembangunan wilayah. Melalui aplikasi ini, pengguna dapat melihat informasi terkait jumlah penduduk, kondisi pendidikan, aspek kesehatan, serta perkembangan ekonomi yang ada di setiap kelurahan.
        </p>
      </div>

      <h4 class="mt-5 section-title">Tujuan Utama</h4>
      <ul class="list-group list-group-flush">
        <li class="list-group-item">Mempermudah akses data bagi masyarakat umum, mahasiswa, peneliti, maupun aparatur pemerintah.</li>
        <li class="list-group-item">Mendorong transparansi data publik, sehingga siapa pun dapat mengetahui kondisi real di lapangan.</li>
        <li class="list-group-item">Mendukung perencanaan berbasis data untuk pembangunan yang lebih tepat sasaran.</li>
      </ul>

      <h4 class="mt-5 section-title">Sumber Data</h4>
      <p>
        Data yang ditampilkan berasal dari input manual yang dilakukan oleh admin tiap kelurahan. Admin bertanggung jawab memperbarui data secara berkala agar informasi tetap relevan dan valid. Dengan cara ini, setiap kelurahan dapat memiliki statistik yang terorganisir dengan baik dan bisa diakses kapan saja.
      </p>

      <h4 class="mt-5 section-title">Pengembang</h4>
      <p>
        Web ini dikembangkan sebagai bagian dari Proyek PKL (Praktik Kerja Lapangan) siswa jurusan Pengembangan Perangkat Lunak dan Gim (PPLG) di Sudin Kominfotik Jakarta Timur dari SMKN 65 Jakarta. Selain menjadi media pembelajaran, aplikasi ini juga diharapkan dapat memberikan kontribusi nyata dalam meningkatkan kualitas pelayanan publik di bidang data dan informasi.
      </p>

      <div class="highlight mt-4">
        <p>
          Harapannya, web ini dapat menjadi jembatan antara data yang ada di lapangan dengan kebutuhan masyarakat luas, sekaligus melatih generasi muda untuk terbiasa membuat solusi digital yang bermanfaat bagi lingkungan sekitarnya.
        </p>
      </div>

      <div class="text-center mt-5">
        <a href="index.php" class="btn btn-primary btn-lg">Kembali ke Beranda</a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.toggle('hidden');
    }

    function toggleDropdown() {
      const dropdown = document.getElementById('userDropdown');
      dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    }

    function filterKelurahan() {
      const input = document.getElementById('searchKel');
      const filter = input.value.toLowerCase();
      const list = document.getElementById('kelurahanList');
      const items = list.getElementsByTagName('a');

      for (let i = 0; i < items.length; i++) {
        const txtValue = items[i].textContent || items[i].innerText;
        if (txtValue.toLowerCase().indexOf(filter) > -1) {
          items[i].style.display = '';
        } else {
          items[i].style.display = 'none';
        }
      }
    }

    document.addEventListener('click', function(event) {
      const dropdown = document.getElementById('userDropdown');
      const userMenu = document.querySelector('.user-menu');
      if (userMenu && !userMenu.contains(event.target)) {
        dropdown.style.display = 'none';
      }
    });
  </script>
</body>
</html>
=======
p
>>>>>>> 1e46a99eff0d25ceccabab3569f798873d5c6c13
