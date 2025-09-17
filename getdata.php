<?php
header('Content-Type: application/json');

$kategori  = $_GET['kategori'] ?? '';
$kelurahan = $_GET['kelurahan'] ?? '';

$map = [
    'pendidikan'   => 'data_pendidikan.json',
    'kesehatan'    => 'data_kesehatan.json',
    'ekonomi'      => 'data_ekonomi.json',
    'kependudukan' => 'data_kependudukan.json',
];

if (!isset($map[$kategori]) || !file_exists($map[$kategori])) {
    if ($kategori === 'kependudukan') {
        echo json_encode(['labels' => [], 'laki_laki' => [], 'perempuan' => []]);
    } else {
        echo json_encode(['labels' => [], 'jumlah' => []]);
    }
    exit;
}

$data = json_decode(file_get_contents($map[$kategori]), true) ?? [];

// ====================== KEPENDUDUKAN ======================
if ($kategori === 'kependudukan') {
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

    // Urutkan berdasarkan angka umur (opsional)
    $gabung = [];
    foreach ($labels as $i => $label) {
        $gabung[] = [
            'label'     => $label,
            'laki'      => $laki_laki[$i],
            'perempuan' => $perempuan[$i],
        ];
    }

    usort($gabung, function($a, $b) {
        $numA = intval($a['label']);
        $numB = intval($b['label']);
        return $numA <=> $numB;
    });

    echo json_encode([
        'labels'     => array_column($gabung, 'label'),
        'laki_laki'  => array_column($gabung, 'laki'),
        'perempuan'  => array_column($gabung, 'perempuan')
    ]);
    exit;
}

// ====================== PENDIDIKAN ======================
if ($kategori === 'pendidikan') {
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
}

// ====================== KESEHATAN ======================
if ($kategori === 'kesehatan') {
    $filtered = array_filter($data, function($row) use ($kelurahan) {
        return isset($row['kelurahan']) && strtolower($row['kelurahan']) === strtolower($kelurahan);
    });

    $labels = [];
    $jumlah = [];

    foreach ($filtered as $row) {
        $labels[] = $row['fasilitas_kesehatan'] ?? '';
        $jumlah[] = (int)($row['jumlah'] ?? 0);
    }

    echo json_encode([
        'labels' => $labels,
        'jumlah' => $jumlah
    ]);
    exit;
}

// ====================== EKONOMI ======================
if ($kategori === 'ekonomi') {
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
