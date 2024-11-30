<?php
require_once 'db.php';

class Entry {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM entries");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM entries WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($title, $description) {
        $stmt = $this->pdo->prepare("INSERT INTO entries (title, description) VALUES (:title, :description)");
        $stmt->execute(['title' => $title, 'description' => $description]);
    }

    public function update($id, $title, $description) {
        $stmt = $this->pdo->prepare("UPDATE entries SET title = :title, description = :description WHERE id = :id");
        $stmt->execute(['id' => $id, 'title' => $title, 'description' => $description]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM entries WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
?>
