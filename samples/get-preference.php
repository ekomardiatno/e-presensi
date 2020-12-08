<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$db = new mysqli('localhost', 'root', '!7kVbRjV', 'presensi_db');
$sql = 'SELECT nip_pegawai, nama_pegawai, foto_pegawai FROM pegawai WHERE nip_pegawai="' . $_GET['nip_pegawai'] . '"';

$query = $db->query($sql);
if(!$query) {
  http_response_code(400);
  echo json_encode([
    'status' => 'BAD_REQUEST'
  ]);
} else {
  if($query->num_rows <= 0) {
    http_response_code(200);
    echo json_encode([
      'status' => 'EMPTY'
    ]);
  } else {
    http_response_code(200);
    echo json_encode([
      'status' => 'OK',
      'data' => [
        'pegawai' => $query->fetch_assoc(),
        'aturan_presensi' => [
          'jam_masuk' => '07:30:00',
          'jam_pulang' => '16:00:00',
          'latitude' => -0.5069577,
          'longitude' => 101.5410829,
          'radius_toleransi' => 100, //meter
          'toleransi_waktu' => 30, // unit menit, - pulang + masuk,
          'inisial_lokasi' => 'DISKOMINFOSS'
        ]
      ]
    ]);
  }
}