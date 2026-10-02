<?php
// Compatible with PHP 7.0+. Saves the contact form into the `users` table.
require __DIR__ . '/config.php';
ini_set('display_errors', '0');
$wantsJson = strpos(isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : '', 'application/json') !== false;

function respond($ok, $msg, $json, $code = 200) {
    http_response_code($code);
    if ($json) { header('Content-Type: application/json'); echo json_encode($ok ? array('ok' => true) : array('ok' => false, 'error' => $msg)); }
    else { header('Location: index.html?sent=' . ($ok ? '1' : '0') . '#contact'); }
    exit;
}
function post($k) { return isset($_POST[$k]) ? trim($_POST[$k]) : ''; }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') respond(false, 'POST only', $wantsJson, 405);

$name = post('full_name'); $email = post('email'); $goal = post('goal'); $msg = post('message');
if ($name === '') respond(false, 'Please enter your name.', $wantsJson, 422);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) respond(false, 'Please enter a valid email.', $wantsJson, 422);

try {
    $pdo = db();
    // Create the table if it does not exist yet
    $pdo->exec('CREATE TABLE IF NOT EXISTS `users` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `Fullname` VARCHAR(100), `Email` VARCHAR(150), `Yourgoal` VARCHAR(100), `Message` TEXT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

    // Match your real column names, ignoring upper/lower case
    $cols = array();
    foreach ($pdo->query('SHOW COLUMNS FROM `users`')->fetchAll() as $c) $cols[strtolower($c['Field'])] = $c['Field'];
    $want = array(
        array(array('fullname', 'full_name', 'name'), $name),
        array(array('email'), $email),
        array(array('yourgoal', 'your_goal', 'goal'), $goal),
        array(array('message', 'msg'), $msg),
    );
    $fields = array(); $vals = array();
    foreach ($want as $w) {
        foreach ($w[0] as $cand) if (isset($cols[$cand])) { $fields[] = '`' . $cols[$cand] . '`'; $vals[] = $w[1]; break; }
    }
    if (!$fields) throw new Exception('No matching columns in table users. Found: ' . implode(', ', $cols));
    $sql = 'INSERT INTO `users` (' . implode(',', $fields) . ') VALUES (' . implode(',', array_fill(0, count($vals), '?')) . ')';
    $pdo->prepare($sql)->execute($vals);
} catch (Exception $e) {
    error_log($e->getMessage());
    respond(false, DEBUG ? 'DB error: ' . $e->getMessage() : 'Could not save your message. Please try again.', $wantsJson, 500);
}
respond(true, '', $wantsJson);
