<?php
include 'koneksi.php';

$kategori = $_GET['kategori'];
$kelurahan = $_GET['kelurahan'];

switch($kategori) {
  case 'ekonomi':
    $sql = "SELECT jenis_usaha AS label, jumlah FROM ekonomi WHERE kelurahan=?";
    break;
  case 'pendidikan':
    $sql = "SELECT jenis_pendidikan AS label, jumlah FROM pendidikan WHERE kelurahan=?";
    break;
  case 'kesehatan':
    $sql = "SELECT fasilitas AS label, jumlah FROM kesehatan WHERE kelurahan=?";
    break;
  case 'kependudukan':
    $sql = "SELECT kelompok_umur AS label, jumlah FROM kependudukan WHERE kelurahan=?";
    break;
  default:
    die("Kategori tidak valid");
}

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename={$kategori}_{$kelurahan}.csv");

$output = fopen("php://output", "w");
fputcsv($output, ['Kategori', 'Jumlah']);

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $kelurahan);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
  fputcsv($output, [$row['label'], $row['jumlah']]);
}
fclose($output);
?>
