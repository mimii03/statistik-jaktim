ECHO is on.
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Grafik Ekonomi</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>Grafik Statistik Ekonomi - Pulo Gebang</h2>
  <canvas id="chartEkonomi"></canvas>
  <br>
  <a href="download.php?kategori=ekonomi&kelurahan=Pulo Gebang">⬇️ Download CSV</a>

  <script>
    const ctx = document.getElementById("chartEkonomi").getContext("2d");

    fetch("get_data.php?kategori=ekonomi&kelurahan=Pulo Gebang")
      .then(res => res.json())
      .then(data => {
        new Chart(ctx, {
          type: 'bar',
          data: {
            labels: data.labels,
            datasets: [{
              label: 'Jumlah Usaha',
              data: data.jumlah,
              backgroundColor: '#3498db'
            }]
          },
          options: {
            indexAxis: 'y',
            responsive: true
          }
        });
      });
  </script>
</body>
</html>
