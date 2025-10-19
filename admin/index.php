<?php
$USER = "admin";
$PASS = "ar2j123"; // ganti

if (!isset($_SERVER['PHP_AUTH_USER']) ||
    $_SERVER['PHP_AUTH_USER'] !== $USER ||
    $_SERVER['PHP_AUTH_PW']   !== $PASS) {
  header('WWW-Authenticate: Basic realm="AR2J Admin"');
  header('HTTP/1.0 401 Unauthorized');
  echo 'Auth required'; exit;
}

require __DIR__ . "/../db.php";
$leads = $mysqli->query("SELECT id,name,email,phone,LEFT(message,80) AS msg,created_at FROM leads ORDER BY id DESC LIMIT 200");
$subs  = $mysqli->query("SELECT id,email,name,status,created_at FROM subscribers ORDER BY id DESC LIMIT 200");
$ords  = $mysqli->query("SELECT id,order_code,customer_name,total_idr,status,created_at FROM orders ORDER BY id DESC LIMIT 200");
?>
<!doctype html>
<html lang="id">
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>AR2J Admin</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
<body>
<main class="container">
  <h1>AR2J Admin</h1>
  <p><a href="/ar2j/" target="_blank">← Lihat situs</a></p>

  <h3>Leads</h3>
  <table role="grid"><thead><tr><th>ID</th><th>Nama</th><th>Email</th><th>HP</th><th>Pesan</th><th>Waktu</th></tr></thead><tbody>
  <?php while($r=$leads->fetch_assoc()): ?>
    <tr>
      <td><?= $r['id'] ?></td>
      <td><?= htmlspecialchars($r['name']) ?></td>
      <td><?= htmlspecialchars($r['email']) ?></td>
      <td><?= htmlspecialchars($r['phone']) ?></td>
      <td><?= htmlspecialchars($r['msg']) ?></td>
      <td><?= $r['created_at'] ?></td>
    </tr>
  <?php endwhile; ?></tbody></table>

  <h3>Subscribers</h3>
  <table role="grid"><thead><tr><th>ID</th><th>Email</th><th>Nama</th><th>Status</th><th>Waktu</th></tr></thead><tbody>
  <?php while($r=$subs->fetch_assoc()): ?>
    <tr>
      <td><?= $r['id'] ?></td>
      <td><?= htmlspecialchars($r['email']) ?></td>
      <td><?= htmlspecialchars($r['name']) ?></td>
      <td><?= $r['status'] ?></td>
      <td><?= $r['created_at'] ?></td>
    </tr>
  <?php endwhile; ?></tbody></table>

  <h3>Orders</h3>
  <table role="grid"><thead><tr><th>ID</th><th>Kode</th><th>Nama</th><th>Total</th><th>Status</th><th>Waktu</th></tr></thead><tbody>
  <?php while($r=$ords->fetch_assoc()): ?>
    <tr>
      <td><?= $r['id'] ?></td>
      <td><?= $r['order_code'] ?></td>
      <td><?= htmlspecialchars($r['customer_name']) ?></td>
      <td>Rp<?= number_format($r['total_idr'],0,',','.') ?></td>
      <td><?= $r['status'] ?></td>
      <td><?= $r['created_at'] ?></td>
    </tr>
  <?php endwhile; ?></tbody></table>
</main>
</body>
</html>
