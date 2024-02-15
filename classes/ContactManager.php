<?php

namespace AzurWeb;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class ContactManager {
    protected $db;
    private $logger;

    public function __construct(Database $db) {
        $this->db = $db;
        $this->logger = new Logger('contact_manager_logger');
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/contact_manager.log', Logger::ERROR));
    }

    public function saveMessage($firstname, $lastname, $telephone, $email, $message) {
        try {
            $this->db->beginTransaction();

            $firstname = htmlspecialchars($firstname);
            $lastname = htmlspecialchars($lastname);
            $telephone = htmlspecialchars($telephone);
            $email = htmlspecialchars($email);
            $message = htmlspecialchars($message);

            $stmt = $this->db->prepare("INSERT INTO contact (firstname, lastname, telephone, email, message) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$firstname, $lastname, $telephone, $email, $message]);

            $this->db->commit();
        } catch (\PDOException $e) {
            $this->db->rollBack();
            $this->logger->error('Erreur lors de la sauvegarde du message : ' . $e->getMessage());
            throw $e;
        }
    }

    public function getMessages() {
        try {
            $stmt = $this->db->query("SELECT * FROM contact WHERE status != 'archived'");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            $this->logger->error('Erreur lors de la récupération des messages : ' . $e->getMessage());
            return [];
        }
    }

    public function archiveMessage($id) {
        try {
            $stmt = $this->db->prepare("UPDATE contact SET status = 'archived' WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->rowCount() > 0;
        } catch (\PDOException $e) {
            $this->logger->error('Erreur lors de l’archivage du message : ' . $e->getMessage());
            return false; 
        }
    }

    public function deleteMessage($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM contact WHERE id = ?");
            $stmt->execute([$id]);
        } catch (\PDOException $e) {
            $this->logger->error('Erreur lors de la suppression du message : ' . $e->getMessage());
            throw $e;
        }
    }

    public function getArchivedMessages() {
        try {
            $stmt = $this->db->query("SELECT * FROM contact WHERE status = 'archived'");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            $this->logger->error('Erreur lors de la récupération des messages archivés : ' . $e->getMessage());
            return [];
        }
    }

    public function getUnreadMessages() {
        try {
            $stmt = $this->db->query("SELECT * FROM contact WHERE status = 'unread'");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            $this->logger->error('Erreur lors de la récupération des messages non lus : ' . $e->getMessage());
            return [];
        }
    }
}
