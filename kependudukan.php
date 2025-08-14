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

  <?php
  $kelurahan = $_GET['kelurahan'];
  ?>
  <h2>Grafik Statistik Kependudukan - <?php echo htmlspecialchars($kelurahan); ?></h2>
  <canvas id="chartKependudukan"></canvas>
  <br>
  <a href="download.php?kategori=ekonomi&kelurahan=<?php echo urlencode($kelurahan); ?>" class="btn-download">⬇️ Download CSV</a>
<button class="btn-download" data-chart="chartKependudukan">📥 Download PNG</button>

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
            datasets: [{
              label: 'Jumlah Penduduk',
              data: data.jumlah,
              backgroundColor: [
                'rgba(52, 152, 219, 0.7)',
                'rgba(46, 204, 113, 0.7)',
                'rgba(231, 76, 60, 0.7)',
                'rgba(241, 196, 15, 0.7)',
                'rgba(155, 89, 182, 0.7)',
                'rgba(230, 126, 34, 0.7)',
                'rgba(127, 140, 141, 0.7)',
                'rgba(52, 73, 94, 0.7)'
              ],
              borderColor: [
                'rgba(41, 128, 185, 1)',
                'rgba(39, 174, 96, 1)',
                'rgba(192, 57, 43, 1)',
                'rgba(243, 156, 18, 1)',
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
        link.download = chartId + ".png"; // nama file sesuai chart
        link.click();
    });
});

  </script>
</body>
</html>
