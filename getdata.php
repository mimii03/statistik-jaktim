<?php
header('Content-Type: application/json');

$kategori = $_GET['kategori'] ?? '';
$kelurahan = $_GET['kelurahan'] ?? '';

$map = [
    'pendidikan'   => 'data_pendidikan.json',
    'kesehatan'    => 'data_kesehatan.json',
    'ekonomi'      => 'data_ekonomi.json',
    'kependudukan' => 'data_kependudukan.json',
];

if (!isset($map[$kategori])) {
    echo json_encode(['labels' => [], 'jumlah' => []]);
    exit;
}

$file = $map[$kategori];

if (!file_exists($file)) {
    echo json_encode(['labels' => [], 'jumlah' => []]);
    exit;
}

$json = file_get_contents($file);
$data = json_decode($json, true) ?? [];

$labels = [];
$jumlah = [];

if ($kategori === 'kependudukan') {
    foreach ($data as $row) {
        $labels[] = $row['kategori'] ?? '';
        $jumlah[] = (int)($row['jumlah_penduduk'] ?? 0);
    }
} elseif ($kategori === 'pendidikan') {
    foreach ($data as $row) {
        $labels[] = $row['jenjang'] ?? '';
        $jumlah[] = (int)($row['jumlah'] ?? 0);
    }
} elseif ($kategori === 'kesehatan' || $kategori === 'ekonomi') {
    foreach ($data as $row) {
        $labels[] = $row['fasilitas'] ?? '';
        $jumlah[] = (int)($row['jumlah'] ?? 0);
    }
}

echo json_encode([
    'labels' => $labels,
    'jumlah' => $jumlah
]);
