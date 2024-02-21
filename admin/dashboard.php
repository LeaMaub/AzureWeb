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
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
require_once '../classes/Database.php';
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

use AzurWeb\Database;

$db = new Database($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="/admin/css/style.css">
    <title>Tableau de bord Administrateur</title>
</head>
<body>
    <h1 class="text-center py-3">Tableau de bord</h1>
    <div class="form__dashboard d-flex justify-content-center">
        
        <div class="choices">
            <a class="choice" href="projects.php">Projets</a>
            <a class="choice" href="reviews.php">Avis</a>
        </div>
        <div class="logout">
            <form action="logout.php" method="post">
                <button class="btn py-2" type="submit" name="logout">Déconnexion</button>
            </form>
        </div>
    </div>
</body>
</html>
