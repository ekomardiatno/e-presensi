<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


$db = new mysqli('localhost', 'root', '!7kVbRjV', 'presensi_db');

$sql = 'SELECT nip_pegawai, nama_pegawai, foto_pegawai FROM pegawai WHERE nip_pegawai!="' . $_GET['nip_pegawai'] . '" AND nama_pegawai LIKE "%' . $_GET['nama_pegawai'] . '%"';
$query = $db->query($sql);

if($query) {
  if($query->num_rows > 0) {
    $data = [];
    while($row = $query->fetch_assoc()) {
      $sql2 = 'SELECT tipe_presensi FROM presensi WHERE nip_pegawai="' . $row['nip_pegawai'] . '" AND waktu LIKE "' . date('Y-m-d') . '%"';
      $sql3 = 'SELECT COUNT(*) as count FROM absensi WHERE nip_pegawai="' . $row['nip_pegawai'] . '" AND date_added LIKE "' . date('Y-m-d') . '%"';
      $query2 = $db->query($sql2);
      $query3 = $db->query($sql3);
      $tipe_presensi = [];
      $arr = [
      'pegawai' => [
        'nip_pegawai' => $row['nip_pegawai'],
        'nama_pegawai' => $row['nama_pegawai'],
        'foto_pegawai' => $row['foto_pegawai'],
        ]
      ];
      while($row2 = $query2->fetch_assoc()) {
        $tipe_presensi[] = $row2['tipe_presensi'];
      }

      if(intval($query3->fetch_assoc()['count']) > 0) {
        $tipe_presensi_next = 0;
      } else {
        if(count($tipe_presensi) <= 0) {
          $tipe_presensi_next = 1;
        } else if(array_search('1', $tipe_presensi) >= 0 && !array_search('2', $tipe_presensi)) {
          $tipe_presensi_next = 2;
        } else {
          $tipe_presensi_next = 0;
        }
      }

      
      
      if($tipe_presensi_next > 0) {
        $arr = array_merge($arr, [
          'aturan_presensi' => [
            'tipe_presensi' => $tipe_presensi_next,
            'jam_masuk' => '07:30:00',
            'jam_pulang' => '16:00:00',
            'latitude' => -0.5069577,
            'longitude' => 101.5410829,
            'radius_toleransi' => 100, //meter
            'toleransi_waktu' => 30, // unit menit, - pulang + masuk,
            'inisial_lokasi' => 'DISKOMINFOSS'
          ]
        ]);
      }
      $data[] = $arr;
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