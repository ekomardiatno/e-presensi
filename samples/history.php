<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$db = new mysqli('localhost', 'root', '!7kVbRjV', 'presensi_db');
$sql = 'SELECT * FROM presensi WHERE nip_pegawai="' . $_GET['nip_pegawai'] . '" AND waktu LIKE \''. $_GET['tanggal'] .'%\'';
$sql2 = 'SELECT * FROM absensi WHERE nip_pegawai="' . $_GET['nip_pegawai'] . '" AND date_added LIKE \''. $_GET['tanggal'] .'%\'';
$query = $db->query($sql);
$query2 = $db->query($sql2);
$rule = [
  'jam_masuk' => '07:30:00',
  'jam_pulang' => '16:00:00',
  'latitude' => -0.5069577,
  'longitude' => 101.5410829,
  'radius_toleransi' => 100, //meter
  'toleransi_waktu' => 30 // untuk pulang - untuk masuk +
];

if(isset($_GET['tanggal'])) {
  if($query->num_rows > 0) {
    $data = [];
    while ($row = $query->fetch_assoc()) {
      if($row['tipe_presensi'] === '1') {
        $row = array_merge($row, ['keterangan_waktu' => round((strtotime($row['waktu']) - strtotime(substr($row['waktu'], 0, 10) . ' ' . $rule['jam_masuk'])) / 60,0)]);
      } else if($row['tipe_presensi'] === '2') {
        $row = array_merge($row, ['keterangan_waktu' => round((strtotime($row['waktu']) - strtotime(substr($row['waktu'], 0, 10) . ' ' .$rule['jam_pulang'])) / 60,0)]);
      }
      $data[] = $row;
    }
    http_response_code(200);
    echo json_encode([
      'status' => 'OK',
      'data' => $data
    ]);
  } else if($query2->num_rows > 0) {
    $data = [];
    while($row = $query2->fetch_assoc()) {
      $data[] = $row;
    }
    http_response_code(200);
    echo json_encode([
      'status' => 'OK',
      'data' => $data
    ]);
  } else {
    http_response_code(200);
    echo json_encode([
      'status' => 'EMPTY'
    ]);
  }
} else {
  http_response_code(400);
  echo json_encode([
    'status' => 'BAD_REQUEST'
  ]);
}