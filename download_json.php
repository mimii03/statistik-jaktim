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
fputcsv($output, ['Kelurahan', ucfirst($label), 'Jumlah'], ';');

// Isi data baris per baris
foreach ($filtered as $row) {
    fputcsv($output, [
        $row['kelurahan'] ?? '',
        $row[$label] ?? '',
        $row['jumlah'] ?? ''
    ], ';');
}

fclose($output);
exit;
?>
