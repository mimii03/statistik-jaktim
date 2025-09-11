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
    if ($kategori === 'kependudukan') {
        echo json_encode(['labels' => [], 'Laki-laki' => [], 'Perempuan' => []]);
    } else {
        echo json_encode(['labels' => [], 'jumlah' => []]);
    }
    exit;
}

$file = $map[$kategori];

if (!file_exists($file)) {
    if ($kategori === 'kependudukan') {
        echo json_encode(['labels' => [], 'Laki-laki' => [], 'Perempuan' => []]);
    } else {
        echo json_encode(['labels' => [], 'jumlah' => []]);
    }
    exit;
}

$json = file_get_contents($file);
$data = json_decode($json, true) ?? [];

if ($kategori === 'kependudukan') {
    $file = $map[$kategori];

    if (!file_exists($file)) {
        echo json_encode(['labels' => [], 'Laki-laki' => [], 'Perempuan' => []]);
        exit;
    }

    $data = json_decode(file_get_contents($file), true) ?? [];

    $filtered = array_filter($data, function($row) use ($kelurahan) {
        return isset($row['kelurahan']) && strtolower($row['kelurahan']) === strtolower($kelurahan);
    });

    $labels = [];
    $laki_laki = [];
    $perempuan = [];

    foreach ($filtered as $row) {
        $umur   = $row['kelompok_umur'] ?? '';
        $jk     = strtolower($row['jenis_kelamin'] ?? '');
        $jumlah = (int)($row['jumlah_penduduk'] ?? 0);

        if (!in_array($umur, $labels)) {
            $labels[] = $umur;
            $laki_laki[] = 0;
            $perempuan[] = 0;
        }

        $i = array_search($umur, $labels);

        if ($jk === 'laki-laki') {
            $laki_laki[$i] += $jumlah;
        } elseif ($jk === 'perempuan') {
            $perempuan[$i] += $jumlah;
        }
    }

    echo json_encode([
        'labels'     => $labels,
        'laki_laki'  => $laki_laki,
        'perempuan'  => $perempuan
    ]);
    exit;

} elseif ($kategori === 'pendidikan') {
    $filtered = array_filter($data, function($row) use ($kelurahan) {
        return isset($row['kelurahan']) && strtolower($row['kelurahan']) === strtolower($kelurahan);
    });

    $labels = [];
    $jumlah = [];

    foreach ($filtered as $row) {
        $labels[] = $row['jenjang'] ?? '';
        $jumlah[] = (int)($row['jumlah'] ?? 0);
    }

    echo json_encode([
        'labels' => $labels,
        'jumlah' => $jumlah
    ]);
    exit;
} elseif ($kategori === 'kesehatan' || $kategori === 'ekonomi') {
    $filtered = array_filter($data, function($row) use ($kelurahan) {
        return isset($row['kelurahan']) && strtolower($row['kelurahan']) === strtolower($kelurahan);
    });

    $labels = [];
    $jumlah = [];

    foreach ($filtered as $row) {
        $labels[] = $row['fasilitas'] ?? '';
        $jumlah[] = (int)($row['jumlah'] ?? 0);
    }

    echo json_encode([
        'labels' => $labels,
        'jumlah' => $jumlah
    ]);
    exit;
}

echo json_encode(['labels' => [], 'jumlah' => []]);
