<?php
namespace AzurWeb;

class Review {
    private $id;
    private $title;
    private $note;
    private $reviewText;
    private $profilPicture;
    private $customerName;

    public function __construct($id, $title, $note, $reviewText, $profilPicture, $customerName) {
        $this->id = $id;
        $this->title = $title;
        $this->note = $note;
        $this->reviewText = $reviewText;
        $this->profilPicture = $profilPicture;
        $this->customerName = $customerName;
    }

    //Getters
    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getNote() {
        return $this->note;
    }

    public function getReviewText() {
        return $this->reviewText;
    }

    public function getProfilPicture() {
        return $this->profilPicture;
    }

    public function getCustomerName() {
        return $this->customerName;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setNote($note) {
        $this->note = $note;
    }

    public function setReviewText($reviewText) {
        $this->reviewText = $reviewText;
    }

    public function setProfilPicture($profilPicture) {
        $this->profilPicture = $profilPicture;
    }

    public function setCustomerName($customerName) {
        $this->customerName = $customerName;
    }
}
