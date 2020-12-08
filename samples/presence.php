<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
date_default_timezone_set('Asia/Jakarta');

$post = $_POST;
$date_time = date('Y-m-d H:i:s');
if(isset($post['waktu'])) {
  $date_time = $post['waktu'];
}
$db = new mysqli('localhost', 'root', '!7kVbRjV', 'presensi_db');
$rule = [
  'jam_masuk' => '07:30:00',
  'jam_pulang' => '16:00:00',
  'latitude' => -0.5069577,
  'longitude' => 101.5410829,
  'radius_toleransi' => 100, //meter
  'toleransi_waktu' => 30 // untuk pulang - untuk masuk +
];

$post['tipe_presensi'] = intval($post['tipe_presensi']);
$post['latitude'] = doubleval($post['latitude']);
$post['longitude'] = doubleval($post['longitude']);

$image_types = [
  'image/apng' => '.apng',
  'image/bmp' => '.bmp',
  'image/gif' => '.gif',
  'image/x-icon' => '.ico',
  'image/jpeg' => '.jpg',
  'image/png' => '.png',
  'image/svg+xml' => '.svg',
  'image/tiff' => '.tif',
  'image/webp' => '.webp'
];

$file = base64_decode($post['foto']);
$f = finfo_open();
$mime_type = finfo_buffer($f, $file, FILEINFO_MIME_TYPE);
$filename = $post['nip_pegawai'] . '-' . date('Ymd') . '-' . $post['tipe_presensi'] . $image_types[$mime_type];
$path = 'images/presence/';
if(!is_dir($path)) {
  mkdir($path);
}

$sql = 'INSERT INTO presensi (`nip_pegawai`, `waktu`, `latitude`, `longitude`, `foto`, `tipe_presensi`, `status_konfirmasi`, `keterangan`) VALUES(\'' . $post['nip_pegawai'] . '\',\'' . $date_time . '\',\'' . $post['latitude'] . '\',\'' . $post['longitude'] . '\',\'https://ekomardiatno.site/epresensi/' . $path . $filename . '\',\'' . $post['tipe_presensi'] . '\',\'' . $post['status_konfirmasi'] . '\',\'' . $post['keterangan'] . '\')';

$query = $db->query($sql);

if($query) {
  file_put_contents($path . $filename, $file);
  http_response_code(201);
  $data = [
    'tipe_presensi' => $post['tipe_presensi'],
    'waktu' => $date_time,
    'foto' => 'https://ekomardiatno.site/epresensi/' . $path . $filename,
    'latitude' => $post['latitude'],
    'longitude' => $post['longitude'],
    'nip_pegawai' => $post['nip_pegawai'],
    'lokasi' => 'Dinas Komunikasi Informatika Statistik dan Persandian'
  ];
  if($post['tipe_presensi'] === 1) {
    $data = array_merge($data, ['keterangan_waktu' => round((strtotime($date_time) - strtotime(substr($date_time, 0, 10) . ' ' . $rule['jam_masuk'])) / 60,0)]);
  } else if($post['tipe_presensi'] === 2) {
    $data = array_merge($data, ['keterangan_waktu' => round((strtotime($date_time) - strtotime(substr($date_time, 0, 10) . ' ' . $rule['jam_pulang'])) / 60,0)]);
  }
  echo json_encode([
    'status' => 'OK',
    'data' => $data
  ]);
} else {
  http_response_code(400);
  echo json_encode([
    'status' => 'BAD_REQUEST'
  ]);
}