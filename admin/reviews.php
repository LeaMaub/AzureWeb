<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
require_once '../classes/Database.php';
require_once '../classes/ReviewManager.php';
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

use AzurWeb\Database;
use AzurWeb\ReviewManager;

$db = new Database($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
$pdo = $db->getConnection();

$reviewManager = new ReviewManager($pdo);
$reviews = $reviewManager->getReviews();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_review'])) {
        $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $note = filter_var($_POST['note'], FILTER_VALIDATE_INT);
        $reviewText = filter_var($_POST['reviewText'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $customerName = filter_var($_POST['customerName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        if ($title && $note !== false && $reviewText && $customerName) {
            $reviewManager->addReview($title, $note, $reviewText, $profilePicture, $customerName);
            header('Location: reviews.php?status=success&operation=add');
            exit;
        } else {
            header('Location: reviews.php?status=error');
            exit;
        }
    } elseif (isset($_POST['delete_review'])) {
        $project_id = filter_var($_POST['review_id'], FILTER_VALIDATE_INT);
        if ($project_id) {
            $reviewManager->deleteReview($project_id);
            header('Location: reviews.php?status=success&operation=delete');
            exit;
        } else {
            header('Location: reviews.php?status=error');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des avis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="/admin/css/style.css">
</head>
<body>
    <div class="reviews__container">
        <h1>Avis</h1>
        <section class="current__reviews">
        <?php foreach ($reviews as $review): ?>
        <div class="review">
            <h3><?= htmlspecialchars($review['title']); ?></h3>
            <p>Note: <?= htmlspecialchars($review['note']); ?></p>
            <p><?= nl2br(htmlspecialchars($review['reviewText'])); ?></p>
            <p>Nom du client: <?= htmlspecialchars($review['customerName']); ?></p>
        </div>
    <?php endforeach; ?>
        </section>

        <h2>Ajouter un avis</h2>
        <form method="POST" action="reviews.php" enctype="multipart/form-data">
            <div class="add__review">
            <input type="text" name="title" placeholder="Titre" required>
            <input type="number" name="note" placeholder="Note" min="0" max="5" required>
            <textarea name="reviewText" placeholder="Avis client" required></textarea>
            <input type="file" name="profilePicture">
            <input type="text" name="customerName" placeholder="Nom du client" required>
            </div>
            <div class="btn__container">
            <button type="submit" name="add_review">Ajouter</button>
            </div>
        </form>

        <h2>Supprimer un avis</h2>
        <form method="POST" action="reviews.php">
            <input type="number" name="review_id" placeholder="ID de l'avis" required>
            <button type="submit" name="delete_review">Supprimer</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>