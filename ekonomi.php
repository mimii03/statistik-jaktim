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
    <h2>Statistik Usaha Ekonomi di Pulo Gebang</h2>
    <div class="chart-container">
        <canvas id="grafikEkonomi"></canvas>
        <br>
        <center><a href="download.php?kategori=ekonomi&kelurahan=Pulo Gebang">⬇ Download CSV</a></center>
    </div>

    <script>
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
                indexAxis: 'y', // horizontal bar
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
