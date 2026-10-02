<?php
require __DIR__ . '/config.php';
if (!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] !== ADMIN_USER
    || !password_verify($_SERVER['PHP_AUTH_PW'] ?? '', ADMIN_PASS_HASH)) {
    header('WWW-Authenticate: Basic realm="IRONFORGE admin"'); http_response_code(401); exit('Login required');
}
$rows = array_reverse(db()->query('SELECT * FROM `users`')->fetchAll());
function h($s){return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');}
?><!DOCTYPE html><html><head><meta charset="utf-8"><title>Messages — IRONFORGE</title>
<style>body{font:15px system-ui;max-width:900px;margin:30px auto;padding:0 16px}table{width:100%;border-collapse:collapse}td,th{border-bottom:1px solid #ddd;padding:8px;text-align:left;vertical-align:top}</style></head><body>
<h1>Contact messages (<?= count($rows) ?>)</h1>
<table><tr><th>Name</th><th>Email</th><th>Goal</th><th>Message</th></tr>
<?php foreach ($rows as $r): ?>
<tr><td><?= h((isset($r['Fullname'])?$r['Fullname']:'')) ?></td><td><a href="mailto:<?= h((isset($r['Email'])?$r['Email']:'')) ?>"><?= h((isset($r['Email'])?$r['Email']:'')) ?></a></td>
<td><?= h((isset($r['Yourgoal'])?$r['Yourgoal']:'')) ?></td><td><?= nl2br(h((isset($r['Message'])?$r['Message']:''))) ?></td></tr>
<?php endforeach; ?></table></body></html>
