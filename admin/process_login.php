<?php 
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();
require_once '../classes/Database.php';
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

use AzurWeb\Database;

$db = new Database($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS']);

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$user = $db->getUserByUsername($username);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['logged_in'] = true;
    header('Location: dashboard.php');
    exit;
} else {
    header('Location: login.php');
    exit;
}
