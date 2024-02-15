<?php
session_start();
require_once '../classes/Database.php';
require_once '../classes/ContactManager.php';
require_once __DIR__ . '/../vendor/autoload.php';

use AzurWeb\Database;
use AzurWeb\ContactManager;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$db = new Database($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
$contactManager = new ContactManager($db);

if (isset($_POST['message_id'])) {
    $messageId = $_POST['message_id'];

    $contactManager->deleteMessage($messageId);
}

header('Location: messages.php');
exit;
?>
