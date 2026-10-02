<?php
// XAMPP defaults: user "root", empty password. Change these if you set a MySQL password.
const DB_HOST = '127.0.0.1';
const DB_NAME = 'contact';
const DB_USER = 'root';
const DB_PASS = '';
const DEBUG = true; // shows the real error on the form; set to false when live

// Admin login for admin.php. Generate a hash with:
//   C:\xampp\php\php.exe -r "echo password_hash('your-password', PASSWORD_DEFAULT);"
const ADMIN_USER = 'admin';
const ADMIN_PASS_HASH = '$2y$10$REPLACE_ME_WITH_A_REAL_HASH';

function db() {
    return new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
         PDO::ATTR_EMULATE_PREPARES => false]
    );
}
