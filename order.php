<?php
require __DIR__ . "/db.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") { http_response_code(405); exit("Method Not Allowed"); }

$customer_name  = post("customer_name");
$customer_email = post("customer_email");
$customer_phone = post("customer_phone");
$product_id     = (int) post("product_id");
$qty            = max(1, (int) post("qty"));

if ($customer_name==="" || $customer_email==="" || $product_id<=0) {
  header("Location: /ar2j/?ord=0&err=required#order"); exit;
}

$prod = $mysqli->prepare("SELECT id, name, price_idr FROM products WHERE id=? AND status='active'");
$prod->bind_param("i", $product_id);
$prod->execute();
$res = $prod->get_result();
if (!$res || $res->num_rows === 0) { header("Location: /ar2j/?ord=0&err=prod#order"); exit; }

$p = $res->fetch_assoc();
$price = (float)$p["price_idr"];
$total = $qty * $price;
$order_code = "AR2J-" . date("Ymd") . "-" . strtoupper(substr(md5(uniqid()), 0, 5));

$o = $mysqli->prepare("INSERT INTO orders(order_code, customer_name, customer_email, customer_phone, total_idr, status) VALUES(?,?,?,?,?, 'pending')");
$o->bind_param("ssssd", $order_code, $customer_name, $customer_email, $customer_phone, $total);
$okOrder = $o->execute();
if (!$okOrder) { header("Location: /ar2j/?ord=0&err=db#order"); exit; }
$order_id = $mysqli->insert_id;

$oi = $mysqli->prepare("INSERT INTO order_items(order_id, product_id, qty, price_idr) VALUES (?,?,?,?)");
$oi->bind_param("iiid", $order_id, $product_id, $qty, $price);
$okItem = $oi->execute();

header("Location: /ar2j/?ord=" . ($okItem ? "1" : "0") . "&code=$order_code#order");
