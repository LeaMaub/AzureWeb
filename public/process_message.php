<?php
session_start();

require_once '../classes/Database.php';
require_once '../classes/ContactManager.php';
require_once __DIR__ . '/../vendor/autoload.php';

use AzurWeb\Database;
use AzurWeb\ContactManager;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

header('Content-Type: application/json');

$db = new Database($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
$contactManager = new ContactManager($db);

$response = ['success' => false, 'message' => 'Une erreur est survenue'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userCsrfToken = $_POST['csrf_token'] ?? '';
    $validCsrfToken = $_SESSION['csrf_token'] ?? '';
    if (!hash_equals($validCsrfToken, $userCsrfToken)) {
        $response['message'] = 'CSRF token validation failed.';
        echo json_encode($response);
        exit;
    }
    
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $email = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';

    $firstname = htmlspecialchars($firstname, ENT_QUOTES, 'UTF-8');
    $lastname = htmlspecialchars($lastname, ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Adresse e-mail invalide';
        echo json_encode($response);
        exit;
    }

    if (strlen($message) > 65535) {
        $response['message'] = 'Le message est trop long';
        echo json_encode($response);
        exit;
    }

    $telephone = preg_replace("/[^\d]/", "", $telephone);
    if (strlen($telephone) > 12 || strlen($telephone) < 10) {
        $response['message'] = 'Numéro de téléphone invalide';
        echo json_encode($response);
        exit;
    }

    if (strlen($firstname) > 50 || strlen($lastname) > 50) {
        $response['message'] = 'Nom ou prénom trop long';
        echo json_encode($response);
        exit;
    }

    if (empty($_POST['g-recaptcha-response'])) {
        $response = ['success' => false, 'message' => 'Le jeton CAPTCHA est manquant.'];
        echo json_encode($response);
        exit;
    }
    $token = $_POST['g-recaptcha-response'];

    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret = $_ENV['RECAPTCHA_SECRET_KEY'];
    $recaptcha_response = $token;

    $curl = curl_init($recaptcha_url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query([
        'secret' => $recaptcha_secret,
        'response' => $recaptcha_response
    ]));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $recaptcha = curl_exec($curl);
    if ($recaptcha === false) {
        $err = curl_error($curl);
        curl_close($curl);
        $response = ['success' => false, 'message' => 'Erreur de vérification CAPTCHA: ' . $err];
        echo json_encode($response);
        exit;
    }
    curl_close($curl);
    $recaptcha = json_decode($recaptcha);

    if (!isset($recaptcha->success, $recaptcha->score)) {
        $response = ['success' => false, 'message' => 'Réponse CAPTCHA malformée.'];
        echo json_encode($response);
        exit;
    }
    if (!$recaptcha->success || $recaptcha->score < 0.5) {
        $response = ['success' => false, 'message' => 'Échec de la vérification CAPTCHA.'];
        echo json_encode($response);
        exit;
    }

    try {
        $contactManager->saveMessage($firstname, $lastname, $telephone, $email, $message);
        $response = ['success' => true, 'message' => 'Message envoyé avec succès'];
    } catch (Exception $e) {
        $response = ['success' => false, 'message' => 'Erreur lors de l\'enregistrement du message : ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')];
    }
}

echo json_encode($response);