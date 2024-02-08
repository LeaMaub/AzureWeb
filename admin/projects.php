<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
require_once '../classes/Database.php';
require_once '../classes/ProjectManager.php';
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

use AzurWeb\Database;
use AzurWeb\ProjectManager;

$db = new Database($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
$pdo = $db->getConnection();

$projectManager = new ProjectManager($pdo);
$projects = $projectManager->getProjects();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_project'])) {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $image_tmp = $_FILES['image']['tmp_name'];
            $upload_directory = '../public/images/';
            $image_path = $upload_directory . basename($_FILES['image']['name']);
            
            if (move_uploaded_file($image_tmp, $image_path)) {
                $filename = basename($_FILES['image']['name']);

                $projectManager->addProject($_POST['url'], $filename, $_POST['altText'], $_POST['title']);
                header('Location: projects.php?status=success&operation=add');
                exit;
            } else {
                echo 'Erreur lors du téléchargement de l\'image';
            }
        } else {
            echo 'Aucun fichier téléchargé ou erreur de fichier';
        }
    } elseif (isset($_POST['delete_project'])) {
        $projectManager->deleteProject($_POST['project_id']);
        header('Location: projects.php?status=success&operation=delete');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des projets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="/admin/css/style.css">
</head>
<body>
    <div class="projects__container">
        <h1>Projets</h1>
        <section class="current__projects">
            <?php foreach ($projects as $project): ?>
                <div class="projects">
                    <img src="../public/images/<?= htmlspecialchars($project['image']) ?>" alt="<?= htmlspecialchars($project['altText']) ?>">
                    <p><?= htmlspecialchars($project['title']) ?></p>
                </div>
            <?php endforeach; ?>
        </section>

        <h2>Ajouter un projet</h2>
        <form method="POST" action="projects.php" enctype="multipart/form-data">
            <div class="add__project">
                <input type="text" name="url" placeholder="URL du projet" required>
                <input type="file" name="image" required>
                <input type="text" name="altText" placeholder="Texte alternatif de l'image" required>
                <input type="text" name="title" placeholder="Titre du projet" required>
            </div>
            <div class="btn__container">
                <button class="btn" type="submit" name="add_project">Ajouter</button>
            </div>
        </form>

        <h2>Supprimer un projet</h2>
        <form method="POST" action="projects.php">
            <input type="number" name="project_id" placeholder="ID du projet" required>
            <button type="submit" name="delete_project">Supprimer</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>