
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Grafik Kesehatan</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>Grafik Statistik Kesehatan - Pulo Gebang</h2>
  <canvas id="chartKesehatan"></canvas>
  <br>
  <a href="download.php?kategori=kesehatan&kelurahan=Pulo Gebang">⬇️ Download CSV</a>

  <script>
    const ctx = document.getElementById("chartKesehatan").getContext("2d");

    fetch("getdata.php?kategori=kesehatan&kelurahan=Pulo Gebang")
      .then(res => res.json())
      .then(data => {
        new Chart(ctx, {
          type: 'bar',
          data: {
            labels: data.labels,
            datasets: [{
              label: 'Jumlah',
              data: data.jumlah,
              backgroundColor: [
          'rgba(231, 76, 60, 0.7)',
          'rgba(241, 196, 15, 0.7)',
          'rgba(52, 152, 219, 0.7)',
          'rgba(46, 204, 113, 0.7)',
          'rgba(155, 89, 182, 0.7)',
          'rgba(230, 126, 34, 0.7)',
          'rgba(127, 140, 141, 0.7)',
          'rgba(52, 73, 94, 0.7)'
        ],
        borderColor: [
          'rgba(192, 57, 43, 1)',
          'rgba(243, 156, 18, 1)',
          'rgba(41, 128, 185, 1)',
          'rgba(39, 174, 96, 1)',
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
  </script>
</body>
</html>

