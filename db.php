<?php
// db.php — koneksi MySQL ke DB ar2j_3
$DB_HOST = "127.0.0.1";
$DB_USER = "root";
$DB_PASS = "";        // jika MySQL kamu pakai password, isi di sini
$DB_NAME = "ar2j_3";

$mysqli = @new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
  http_response_code(500);
  die("DB error: " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8mb4");

// helper POST
function post($key, $default = "") {
  return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
}
