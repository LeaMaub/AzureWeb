<?php
namespace AzurWeb;

class ProjectManager {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getProjects() {
        $stmt = $this->db->query("SELECT * FROM projets");
        return $stmt->fetchAll();
    }

    public function addProject($url, $image, $altText, $title) {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("INSERT INTO projets (url, image, altText, title) VALUES (?, ?, ?, ?)");
            $stmt->execute([$url, $image, $altText, $title]);

            $this->db->commit();
        } catch (\PDOException $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function deleteProject($id) {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("DELETE FROM projets WHERE id = ?");
            $stmt->execute([$id]);

            $this->db->commit();
        } catch (\PDOException $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
