<?php
$kategori  = $_GET['kategori'] ?? '';
$kelurahan = $_GET['kelurahan'] ?? '';

if ($kategori == '' || $kelurahan == '') {
    die("Parameter tidak lengkap!");
}

switch ($kategori) {
    case 'pendidikan':
        $file  = 'data_pendidikan.json';
        $label = 'jenjang';
        break;
    case 'kependudukan':
        $file  = 'data_kependudukan.json';
        $label = 'kelompok_umur';
        break;
    case 'kesehatan':
        $file  = 'data_kesehatan.json';
        $label = 'fasilitas';
        break;
    case 'ekonomi':
        $file  = 'data_ekonomi.json';
        $label = 'jenis_usaha';
        break;
    default:
        die("Kategori tidak valid!");
}

if (!file_exists($file)) {
    die("File data tidak ditemukan!");
}

$data = json_decode(file_get_contents($file), true);
if ($data === null) {
    die("Format JSON tidak valid!");
}

// Filter kelurahan
$filtered = array_filter($data, function ($row) use ($kelurahan) {
    return isset($row['kelurahan']) 
        && strtolower(trim($row['kelurahan'])) === strtolower(trim($kelurahan));
});

// Set header CSV
header("Content-Type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename={$kategori}_{$kelurahan}.csv");

// Buka output
$output = fopen("php://output", "w");

// Header kolom (pakai titik koma ;)
if ($kategori === 'kependudukan') {
    fputcsv($output, ['Kelurahan', ucfirst($label), 'Laki-laki', 'Perempuan', 'Jumlah'], ';');
} else {
    fputcsv($output, ['Kelurahan', ucfirst($label), 'Jumlah'], ';');
}

// Cek jika filtered kosong (debug: output pesan di CSV)
if (empty($filtered)) {
    fputcsv($output, ['TIDAK ADA DATA', 'untuk kelurahan: ' . $kelurahan, '', '', ''], ';');
    fclose($output);
    exit;
}

// Isi data baris per baris
if ($kategori === 'kependudukan') {
    // GROUP BY kelompok_umur untuk kependudukan (struktur: per gender terpisah)
    $grouped = [];
    foreach ($filtered as $row) {
        $umur = $row[$label] ?? '';  // kelompok_umur
        $jenis = strtolower(trim($row['jenis_kelamin'] ?? ''));  // "Laki-laki" atau "Perempuan"
        $jumlah_penduduk = isset($row['jumlah_penduduk']) ? (int) abs((float) $row['jumlah_penduduk']) : 0;

        if (!isset($grouped[$umur])) {
            $grouped[$umur] = [
                'kelurahan' => $row['kelurahan'] ?? '',
                'laki' => 0,
                'perempuan' => 0
            ];
        }

        if (strpos($jenis, 'laki') !== false || $jenis === 'male') {
            $grouped[$umur]['laki'] = $jumlah_penduduk;
        } elseif (strpos($jenis, 'perempuan') !== false || $jenis === 'female' || strpos($jenis, 'wanita') !== false) {
            $grouped[$umur]['perempuan'] = $jumlah_penduduk;
        }
    }

    // SORT grouped berdasarkan kelompok_umur (dari kecil ke besar)
    $sorted_grouped = [];
    foreach ($grouped as $umur => $info) {
        // Extract angka awal untuk sorting
        if (preg_match('/(\d+)/', $umur, $matches)) {
            $sort_key = (int) $matches[1];  // "00-04" → 0, "75++" → 75
        } else {
            $sort_key = 999;  // Fallback (umur aneh di akhir)
        }
        
        // Push ke array baru dengan index numeric untuk sort
        $sorted_grouped[$sort_key . '_' . $umur] = [
            'umur' => $umur,
            'info' => $info
        ];
    }

    // Sort array berdasarkan key (numeric + umur string)
    ksort($sorted_grouped);

    // Output sorted data ke CSV
    foreach ($sorted_grouped as $item) {
        $umur = $item['umur'];
        $info = $item['info'];
        $laki = $info['laki'];
        $perempuan = $info['perempuan'];
        $jumlah = $laki + $perempuan;

        fputcsv($output, [
            $info['kelurahan'],
            $umur,
            $laki,
            $perempuan,
            $jumlah
        ], ';');
    }

} else {
    // Kategori lain tetap sama (struktur flat: satu row = satu label + jumlah, urutan asli)
    foreach ($filtered as $row) {
        fputcsv($output, [
            $row['kelurahan'] ?? '',
            $row[$label] ?? '',
            $row['jumlah'] ?? ''
        ], ';');
    }
}

fclose($output);
?>
