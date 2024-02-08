<?php
namespace AzurWeb;

class ReviewManager {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function addReview(Review $review) {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("INSERT INTO reviews (title, note, reviewText, profilPicture, customerName) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $review->getTitle(),
                $review->getNote(),
                $review->getReviewText(),
                $review->getProfilPicture(),
                $review->getCustomerName()
            ]);

            $this->db->commit();
        } catch (\PDOException $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function deleteReview($id) {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("DELETE FROM reviews WHERE id = ?");
            $stmt->execute([$id]);

            $this->db->commit();
        } catch (\PDOException $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getReviews() {
        $stmt = $this->db->prepare("SELECT * FROM reviews");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
}
