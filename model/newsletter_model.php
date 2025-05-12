<?php
require_once '../../../config.php';

class NewsletterModel {
    private $pdo;

    public function __construct() {
        $this->pdo = config::getConnexion();
    }

    public function subscribe($email) {
        $query = "INSERT INTO newsletter_subscribers (email) VALUES (:email)";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([':email' => $email]);
    }

    public function getSubscribers() {
        $query = "SELECT * FROM newsletter_subscribers ORDER BY subscribed_at DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteSubscriber($id) {
        $query = "DELETE FROM newsletter_subscribers WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([':id' => $id]);
    }

    public function isSubscribed($email) {
        $query = "SELECT COUNT(*) FROM newsletter_subscribers WHERE email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':email' => $email]);
        return $stmt->fetchColumn() > 0;
    }
}