<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$db = new mysqli('localhost', 'root', '!7kVbRjV', 'presensi_db');
$attachment_types = [
  'image/apng' => '.apng',
  'image/bmp' => '.bmp',
  'image/gif' => '.gif',
  'image/x-icon' => '.ico',
  'image/jpeg' => '.jpg',
  'image/png' => '.png',
  'image/svg+xml' => '.svg',
  'image/tiff' => '.tif',
  'image/webp' => '.webp',
  'application/pdf' => '.pdf'
];

$file = base64_decode($_POST['lampiran']);
$f = finfo_open();
$mime_type = finfo_buffer($f, $file, FILEINFO_MIME_TYPE);
$filename = $_POST['nip_pegawai'] . '-' . date('Ymd') . '-' . $_POST['tipe_absensi'] . $attachment_types[$mime_type];
$path = 'documents/permit/';
if(!is_dir('documents/')) {
  mkdir('documents/');
}
if(!is_dir($path)) {
  mkdir($path);
}
$_POST['lampiran'] = 'https://ekomardiatno.site/epresensi/' . $path . $filename;

$sql = 'INSERT INTO absensi (nip_pegawai, tipe_absensi, lampiran, tanggal_mulai, tanggal_selesai, keterangan, status_konfirmasi) VALUES (';
$sql .= '\'' . $_POST['nip_pegawai'] . '\',';
$sql .= '\'' . $_POST['tipe_absensi'] . '\',';
$sql .= '\'' . $_POST['lampiran'] . '\',';
$sql .= '\'' . $_POST['tanggal_mulai'] . '\',';
$sql .= '\'' . $_POST['tanggal_selesai'] . '\',';
$sql .= '\'' . $_POST['keterangan'] . '\',';
$sql .= '\'' . $_POST['status_konfirmasi'] . '\',';
$sql = substr($sql, 0, -1) . ')';

// echo json_encode($sql); die;

$query = $db->query($sql);

if($query) {
  file_put_contents($path . $filename, $file);
  http_response_code(201);
  echo json_encode([
    'status' => 'OK',
    'data' => $_POST
  ]);
} else {
  http_response_code(400);
  echo json_encode([
    'status' => 'BAD_REQUEST'
  ]);
}