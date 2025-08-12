<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pilih Kategori Data</title>
  <link rel="stylesheet" href="style.css">
  <style>
    * {
      box-sizing: border-box;
      font-family: Arial, sans-serif;
    }

    body {
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    h1 {
      margin-bottom: 20px;
    }

    .button {
      background-color: #2980b9;
      color: white;
      padding: 15px 30px;
      text-decoration: none;
      border-radius: 5px;
      margin: 10px;
      transition: background-color 0.3s;
    }

    .button:hover {
      background-color: #1c598a;
    }

    select {
      padding: 10px;
      margin: 10px;
    }
  </style>
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
    <a href="index.html" class="beranda-link">Beranda</a>
    
    <div class="dropdown">
      <input type="text" class="search-input" id="searchKel" onkeyup="filterKelurahan()" placeholder="Cari kelurahan...">
      <div class="dropdown-content">
        <div id="kelurahanList">
          <a href="data.php?kelurahan=Balimester">Balimester</a>
          <a href="data.php?kelurahan=Batu Ampar">Batu Ampar</a>
          <a href="data.php?kelurahan=Baru">Baru</a>
          <a href="data.php?kelurahan=Batuampar">Batuampar</a>
          <a href="data.php?kelurahan=Bidaracina">Bidaracina</a>
          <a href="data.php?kelurahan=Bambu Apus">Bambu Apus</a>
          <a href="data.php?kelurahan=Cawang">Cawang</a>
          <a href="data.php?kelurahan=Ceger">Ceger</a>
          <a href="data.php?kelurahan=Cibubur">Cibubur</a>
          <a href="data.php?kelurahan=Cipinang">Cipinang</a>
          <a href="data.php?kelurahan=Cipinang Besar Selatan">Cipinang Besar Selatan</a>
          <a href="data.php?kelurahan=Cipinang Besar Utara">Cipinang Besar Utara</a>
          <a href="data.php?kelurahan=Cipinang Cempedak">Cipinang Cempedak</a>
          <a href="data.php?kelurahan=Cipinang Melayu">Cipinang Melayu</a>
          <a href="data.php?kelurahan=Cipinang Muara">Cipinang Muara</a>
          <a href="data.php?kelurahan=Cilangkap">Cilangkap</a>
          <a href="data.php?kelurahan=Ciracas">Ciracas</a>
          <a href="data.php?kelurahan=Duren Sawit">Duren Sawit</a>
          <a href="data.php?kelurahan=Dukuh">Dukuh</a>
          <a href="data.php?kelurahan=Gedong">Gedong</a>
          <a href="data.php?kelurahan=Halim Perdana Kusumah">Halim Perdana Kusumah</a>
          <a href="data.php?kelurahan=Jatinegara">Jatinegara</a>
          <a href="data.php?kelurahan=Jatinegara Kaum">Jatinegara Kaum</a>
          <a href="data.php?kelurahan=Jati">Jati</a>
          <a href="data.php?kelurahan=Kampung Dukuh">Kampung Dukuh</a>
          <a href="data.php?kelurahan=Kampung Melayu">Kampung Melayu</a>
          <a href="data.php?kelurahan=Kayu Manis">Kayu Manis</a>
          <a href="data.php?kelurahan=Kayu Putih">Kayu Putih</a>
          <a href="data.php?kelurahan=Kebon Manggis">Kebon Manggis</a>
          <a href="data.php?kelurahan=Kramat Jati">Kramat Jati</a>
          <a href="data.php?kelurahan=Klender">Klender</a>
          <a href="data.php?kelurahan=Lubang Buaya">Lubang Buaya</a>
          <a href="data.php?kelurahan=Malaka Jaya">Malaka Jaya</a>
          <a href="data.php?kelurahan=Malaka Sari">Malaka Sari</a>
          <a href="data.php?kelurahan=Makasar">Makasar</a>
          <a href="data.php?kelurahan=Matraman">Matraman</a>
          <a href="data.php?kelurahan=Munjul">Munjul</a>
          <a href="data.php?kelurahan=Palmeriam">Palmeriam</a>
          <a href="data.php?kelurahan=Pasar Rebo">Pasar Rebo</a>
          <a href="data.php?kelurahan=Pekayon">Pekayon</a>
          <a href="data.php?kelurahan=Penggilingan">Penggilingan</a>
          <a href="data.php?kelurahan=Pinang Ranti">Pinang Ranti</a>
          <a href="data.php?kelurahan=Pisangan Baru">Pisangan Baru</a>
          <a href="data.php?kelurahan=Pondok Bambu">Pondok Bambu</a>
          <a href="data.php?kelurahan=Pondok Kelapa">Pondok Kelapa</a>
          <a href="data.php?kelurahan=Pondok Kopi">Pondok Kopi</a>
          <a href="data.php?kelurahan=Pulogadung">Pulogadung</a>
          <a href="data.php?kelurahan=Pulo Gebang">Pulo Gebang</a>
          <a href="data.php?kelurahan=Rambutan">Rambutan</a>
          <a href="data.php?kelurahan=Rawa Bunga">Rawa Bunga</a>
          <a href="data.php?kelurahan=Rawa Terate">Rawa Terate</a>
          <a href="data.php?kelurahan=Rawamangun">Rawamangun</a>
          <a href="data.php?kelurahan=Setu">Setu</a>
          <a href="data.php?kelurahan=Susukan">Susukan</a>
          <a href="data.php?kelurahan=Utan Kayu Selatan">Utan Kayu Selatan</a>
          <a href="data.php?kelurahan=Utan Kayu Utara">Utan Kayu Utara</a>
        </div>
      </div>
    </div>
  </div>

  <h1>Pilih Kategori Data</h1>

  <label for="kategori">Pilih Kategori Data:</label>
  <select id="kategori">
    <option value="">-- Pilih Kategori --</option>
    <option value="pendidikan">📚 Pendidikan</option>
    <option value="kependudukan">🧑‍🤝‍🧑 Kependudukan</option>
    <option value="ekonomi">💼 Ekonomi</option>
    <option value="kesehatan">🏥 Kesehatan</option>
  </select>

  <button onclick="redirectToData()" class="button">Tampilkan Data</button>

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
        window.location.href = `${kategori}.php?kelurahan=${kelurahan}`;
      } else {
        alert("Silakan pilih kategori data.");
      }
    }
  </script>

</body>
</html>
