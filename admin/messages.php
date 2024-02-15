<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
require_once '../classes/Database.php';
require_once '../classes/ContactManager.php';
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

use AzurWeb\Database;
use AzurWeb\ContactManager;

$db = new Database($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS']);

$contactManager = new ContactManager($db);
$messages = $contactManager->getMessages();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="/admin/css/style.css">
    <title>Messages reçus</title>
</head>
<body>
<div class="messages">
    <h1>Mes messages</h1>
    <div class="btn__container">
            <a class="btn return" href="/admin/dashboard.php">Retour</a>
    </div>
    <h2>Non lu(s)</h2>
    <?php foreach ($messages as $message):
        $email = htmlspecialchars($message['email'], ENT_QUOTES, 'UTF-8');
        $subject = "Réponse à votre message sur AzureWeb";
        $body = "Bonjour,\n\n"; ?>
        <section class="unread__messages">
            <div class="message">
                <p class="user__infos"><strong>Nom:</strong> <?= htmlspecialchars($message['lastname']); ?></p>
                <p class="user__infos"><strong>Prénom:</strong> <?= htmlspecialchars($message['firstname']); ?></p>
                <p class="user__infos"><strong>Téléphone:</strong> <?= htmlspecialchars($message['telephone']); ?></p>
                <p class="user__infos"><strong>E-mail:</strong> <?= htmlspecialchars($message['email']); ?></p>
                <p class="user__infos"><strong>Message:</strong> <?= nl2br(htmlspecialchars($message['message'])); ?></p>
                <div class="btn__container">
                    <a href="#" class="btn reply-button" data-message-id="<?= $message['id']; ?>" data-email="<?= $email; ?>" data-subject="<?= $subject; ?>" data-body="<?= $body; ?>">Répondre</a>
                </div>
            </div>
        </section>
    <?php endforeach; ?>
    <h2>Archivé(s)</h2>
    <?php foreach ($contactManager->getArchivedMessages() as $archivedMessage): ?>
        <div class="message">
        <p class="user__infos"><strong>Nom:</strong> <?= htmlspecialchars($archivedMessage['lastname']); ?></p>
            <p class="user__infos"><strong>Prénom:</strong> <?= htmlspecialchars($archivedMessage['firstname']); ?></p>
            <p class="user__infos"><strong>Téléphone:</strong> <?= htmlspecialchars($archivedMessage['telephone']); ?></p>
            <p class="user__infos"><strong>E-mail:</strong> <?= htmlspecialchars($archivedMessage['email']); ?></p>
            <p class="user__infos"><strong>Message:</strong> <?= nl2br(htmlspecialchars($archivedMessage['message'])); ?></p>
            <form action="deleteMessage.php" method="post">
                <input type="hidden" name="message_id" value="<?= $archivedMessage['id']; ?>">
                <div class="btn__container">
                    <button class="btn" type="submit" name="delete">Supprimer</button>
                </div>
            </form>
        </div>
    <?php endforeach; ?>
</div>
<div class="btn__container">
    <a class="btn return" href="/admin/dashboard.php">Retour</a>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const replyButtons = document.querySelectorAll('.reply-button');

    replyButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const messageId = this.dataset.messageId;
            const email = this.dataset.email;
            const subject = encodeURIComponent(this.dataset.subject);
            const body = encodeURIComponent(this.dataset.body);

            fetch('/admin/archiveMessage.php', {
                method: 'POST',
                body: JSON.stringify({ message_id: messageId }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    window.location.href = `mailto:${email}?subject=${subject}&body=${body}`;
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    alert('Erreur lors de l\'archivage du message.');
                }
            });
        });
    });
});
</script>
</body>
</html>