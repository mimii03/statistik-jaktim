
<?php

$koneksi = new mysqli("localhost", "root", "", "statistik");


if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}


$sql = "SELECT kelompok_umur, laki_laki, perempuan, jumlah FROM kependudukan WHERE kelurahan = 'Pulo Gebang'";
$result = $koneksi->query($sql);

$labels = [];
$dataLaki = [];
$dataPerempuan = [];
$dataJumlah = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row["kelompok_umur"];
    $dataLaki[] = $row["laki_laki"];
    $dataPerempuan[] = $row["perempuan"];
    $dataJumlah[] = $row["jumlah"];
}

$koneksi->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Grafik Kependudukan Pulo Gebang</title>
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
    <h2>Statistik Penduduk Pulo Gebang Berdasarkan Kelompok Umur</h2>
    <div class="chart-container">
        <canvas id="grafikPenduduk"></canvas>
        <br>
        <center><a href="download.php?kategori=kependudukan&kelurahan=Pulo Gebang">⬇ Download CSV</a></center>
    </div>

    <script>
        const ctx = document.getElementById("grafikPenduduk").getContext("2d");

        new Chart(ctx, {
            type: "bar",
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [
                    {
                        label: "Laki-laki",
                        data: <?php echo json_encode($dataLaki); ?>,
                        backgroundColor: "rgba(54, 162, 235, 0.7)"
                    },
                    {
                        label: "Perempuan",
                        data: <?php echo json_encode($dataPerempuan); ?>,
                        backgroundColor: "rgba(255, 99, 132, 0.7)"
                    },
                    {
                        label: "Jumlah",
                        data: <?php echo json_encode($dataJumlah); ?>,
                        backgroundColor: "rgba(100, 200, 100, 0.7)"
                    }
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: "Perbandingan Jumlah Penduduk per Kelompok Umur"
                    },
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Penduduk'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Kelompok Umur'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
