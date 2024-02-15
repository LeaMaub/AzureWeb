<?php
session_start();
require_once '../classes/Database.php';
require_once '../classes/ContactManager.php';
require_once __DIR__ . '/../vendor/autoload.php';

use AzurWeb\Database;
use AzurWeb\ContactManager;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$db = new Database($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
$contactManager = new ContactManager($db);

header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['message_id'])) {
    $messageId = $input['message_id'];
    $success = $contactManager->archiveMessage($messageId);
    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Impossible d\'archiver le message.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID du message non fourni.']);
}