<?php
// Ambil parameter dari URL
$kategori  = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$kelurahan = isset($_GET['kelurahan']) ? $_GET['kelurahan'] : '';

if ($kategori == '' || $kelurahan == '') {
    die("Parameter tidak lengkap!");
}

// Tentukan nama file JSON dan key label
switch ($kategori) {
    case 'pendidikan':
        $json_file = 'data_pendidikan.json';
        $label_key = 'jenis_pendidikan';
        break;
    case 'ekonomi':
        $json_file = 'data_ekonomi.json';
        $label_key = 'jenis_usaha';
        break;
    case 'kesehatan':
        $json_file = 'data_kesehatan.json';
        $label_key = 'fasilitas';
        break;
    case 'kependudukan':
        $json_file = 'data_kependudukan.json';
        $label_key = 'kelompok_umur';
        break;
    default:
        die("Kategori tidak valid!");
}

// Baca file JSON
if (!file_exists($json_file)) {
    die("File JSON tidak ditemukan: " . $json_file);
}
$json_data = file_get_contents($json_file);
$data = json_decode($json_data, true);

if ($data === null) {
    die("Format JSON tidak valid di file: " . $json_file);
}

// Filter data sesuai kelurahan
$filtered = array_filter($data, function ($row) use ($kelurahan) {
    return isset($row['kelurahan']) && $row['kelurahan'] === $kelurahan;
});

// Header untuk download CSV
header("Content-Type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename={$kategori}_{$kelurahan}.csv");

// Tulis CSV ke output
$output = fopen("php://output", "w");
fputcsv($output, ['Label', 'Jumlah']);

foreach ($filtered as $row) {
    if (isset($row[$label_key], $row['jumlah'])) {
        fputcsv($output, [$row[$label_key], $row['jumlah']]);
    }
}

fclose($output);
exit;
?>
