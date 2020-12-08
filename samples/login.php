<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$db = new mysqli('localhost', 'root', '!7kVbRjV', 'presensi_db');
$post = $_POST;
$sql = 'SELECT nip_pegawai, nama_pegawai, foto_pegawai FROM pegawai WHERE nip_pegawai="' . $post['nip_pegawai'] . '" AND password="' . $post['password'] . '"';
$sql2 = 'SELECT id_presensi, foto, nip_pegawai, latitude, longitude, tipe_presensi, waktu FROM presensi WHERE nip_pegawai="' . $post['nip_pegawai'] . '" AND waktu LIKE "' . date('Y-m-d') . '%"';
$sql3 = 'SELECT id_absensi, nip_pegawai, tipe_absensi, date_added as tanggal_dibuat, lampiran, tanggal_mulai, tanggal_selesai, keterangan FROM absensi WHERE nip_pegawai="' . $post['nip_pegawai'] . '" AND date_added LIKE "' . date('Y-m-d') . '%"';
$query = $db->query($sql);
$query2 = $db->query($sql2);
$query3 = $db->query($sql3);

if(!isset($post['nip_pegawai']) || !isset($post['password'])) {
  http_response_code(400);
  echo json_encode([
    'status' => 'BAD_REQUEST'
  ]);
} else {
  if($query->num_rows > 0) {
    $history = [];
    if($query2->num_rows > 0) {
      while($row = $query2->fetch_assoc()) {
        $history[] = $row;
      }
    } else if ($query3->num_rows > 0) {
      while($row = $query3->fetch_assoc()) {
        $history[] = $row;
      }
    }
    http_response_code(200);
    echo json_encode([
      'status' => 'OK',
      'data' => [
        'user' => $query->fetch_assoc(),
        'history' => $history,
        'authorization_key' => ''
      ]
    ]);
  } else {
    http_response_code(200);
    echo json_encode([
      'status' => 'EMPTY'
    ]);
  }
}