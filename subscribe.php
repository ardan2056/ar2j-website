<?php
require __DIR__ . "/db.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") { http_response_code(405); exit("Method Not Allowed"); }

$email = post("email");
$name  = post("name");

if ($email === "") { header("Location: /ar2j/?sub=0&err=required#newsletter"); exit; }

$stmt = $mysqli->prepare("INSERT INTO subscribers(email,name) VALUES(?,?) ON DUPLICATE KEY UPDATE status='active', name=VALUES(name)");
$stmt->bind_param("ss", $email, $name);
$ok = $stmt->execute();

header("Location: /ar2j/?sub=" . ($ok ? "1" : "0") . "#newsletter");
