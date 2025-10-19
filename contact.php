<?php
require __DIR__ . "/db.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") { http_response_code(405); exit("Method Not Allowed"); }

$name    = post("name");
$email   = post("email");
$phone   = post("phone");
$message = post("message");
$source  = "contact_form";

if ($name === "" || $email === "") {
  header("Location: /ar2j/?sent=0&err=required#contact"); exit;
}

$stmt = $mysqli->prepare("INSERT INTO leads(name,email,phone,message,source) VALUES (?,?,?,?,?)");
$stmt->bind_param("sssss", $name, $email, $phone, $message, $source);
$ok = $stmt->execute();

header("Location: /ar2j/?sent=" . ($ok ? "1" : "0") . "#contact");
