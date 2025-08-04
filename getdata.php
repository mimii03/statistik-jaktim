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
    $sql = "SELECT kelompok_umur, laki_laki, perempuan, jumlah FROM kependudukan WHERE kelurahan=?";
    $mode = 'kependudukan';
    break;
  default:
    die("Kategori tidak valid");
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $kelurahan);
$stmt->execute();
$result = $stmt->get_result();

if ($mode === 'simple') {
  $labels = $jumlah = [];
  while ($row = $result->fetch_assoc()) {
    $labels[] = $row['label'];
    $jumlah[] = $row['jumlah'];
  }
  echo json_encode([
    'labels' => $labels,
    'jumlah' => $jumlah
  ]);
} else if ($mode === 'kependudukan') {
  $labels = $laki = $perempuan = $jumlah = [];
  while ($row = $result->fetch_assoc()) {
    $labels[] = $row['kelompok_umur'];
    $laki[] = $row['laki_laki'];
    $perempuan[] = $row['perempuan'];
    $jumlah[] = $row['jumlah'];
  }
  echo json_encode([
    'labels' => $labels,
    'laki' => $laki,
    'perempuan' => $perempuan,
    'jumlah' => $jumlah
  ]);
}
?>
