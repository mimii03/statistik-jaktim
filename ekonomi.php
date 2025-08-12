<?php

$koneksi = new mysqli("localhost", "root", "", "statistik");

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$sql = "SELECT jenis_usaha, jumlah FROM ekonomi WHERE kelurahan = 'Pulo Gebang'";
$result = $koneksi->query($sql);

$labels = [];
$dataJumlah = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row["jenis_usaha"];
    $dataJumlah[] = $row["jumlah"];
}

$koneksi->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Grafik Ekonomi Pulo Gebang</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            padding: 30px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .chart-container {
            width: 95%;
            max-width: 1000px;
            margin: auto;
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
    <span>Beranda</span>
    
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
  
    <h2>Statistik Usaha Ekonomi di Pulo Gebang</h2>
    <div class="chart-container">
        <canvas id="grafikEkonomi"></canvas>
        <br>
        <center><a href="download.php?kategori=ekonomi&kelurahan=Pulo Gebang">⬇ Download CSV</a></center>
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

        const ctx = document.getElementById("grafikEkonomi").getContext("2d");

        new Chart(ctx, {
            type: "bar",
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [
                    {
                        label: "Jumlah Unit Usaha",
                        data: <?php echo json_encode($dataJumlah); ?>,
                        backgroundColor: "rgba(75, 192, 192, 0.7)"
                    }
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: "Jumlah Unit Usaha per Jenis Usaha"
                    },
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jenis Usaha'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Jumlah Unit Usaha'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
