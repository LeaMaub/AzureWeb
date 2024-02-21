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
require_once '../classes/ReviewManager.php';
require_once '../classes/Reviews.php';
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

use AzurWeb\Database;
use AzurWeb\ReviewManager;
use AzurWeb\Review;

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
        $profilPicture = null;


        if (isset($_FILES['profilPicture']) && $_FILES['profilPicture']['error'] == 0) {
            $targetDirectory = "../public/images/";
            $targetFile = $targetDirectory . basename($_FILES['profilPicture']['name']);
            if (move_uploaded_file($_FILES['profilPicture']['tmp_name'], $targetFile)) {
                $profilPicture = basename($_FILES['profilPicture']['name']);
            }
        }

        
        if ($title && $note !== false && $reviewText && $customerName && $profilPicture !== null) {
            $review = new Review(null, $title, $note, $reviewText, $profilPicture, $customerName);
            $reviewManager->addReview($review);
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
        <div class="btn__container">
            <a class="btn return" href="/admin/dashboard.php">Retour</a>
        </div>
        <section class="current__reviews">
        <?php foreach ($reviews as $review): ?>
        <div class="review">
            <h3><?= htmlspecialchars($review['title']); ?></h3>
            <p><span class="note">Note: </span><?= htmlspecialchars($review['note']); ?></p>
            <p><?= nl2br(htmlspecialchars($review['reviewText'])); ?></p>
            <p class="customer__name"><img class="profil__picture" src="/public/images/<?= $review['profilPicture']?>" alt="Photo de profil"> <?= htmlspecialchars($review['customerName']); ?></p>
        </div>
    <?php endforeach; ?>
        </section>

        <h2>Ajouter un avis</h2>
        <form class="form__add__review" method="POST" action="reviews.php" enctype="multipart/form-data">
            <div class="add__review">
            <input type="text" name="title" placeholder="Titre" required>
            <input type="number" name="note" placeholder="Note" min="0" max="5" required>
            <textarea name="reviewText" placeholder="Avis client" required></textarea>
            <input class="add__profil__picture" type="file" name="profilPicture">
            <input class="add__customer__name" type="text" name="customerName" placeholder="Nom du client" required>
            </div>
            <div class="btn__container">
            <button class="btn" type="submit" name="add_review">Ajouter</button>
            </div>
        </form>

        <h2>Supprimer un avis</h2>
        <table class="table delete__review">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Contenu</th>
                    <th>Auteur</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reviews as $review): ?>
                <tr>
                    <td><?= htmlspecialchars($review['title']); ?></td>
                    <td><?= nl2br(htmlspecialchars($review['reviewText'])); ?></td>
                    <td><?= htmlspecialchars($review['customerName']); ?></td>
                    <td>
                        <form method="POST" action="reviews.php">
                            <input type="hidden" name="review_id" value="<?= $review['id']; ?>">
                            <button class="btn" type="submit" name="delete_review">Supprimer</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="btn__container">
            <a class="btn return" href="/admin/dashboard.php">Retour</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>