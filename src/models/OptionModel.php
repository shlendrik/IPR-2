<?php
class OptionModel {
    private $conn;
    private $table = "options";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOne($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        if (!isset($data['name'], $data['value'])) return false;
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (name, value) VALUES (?, ?)");
        return $stmt->execute([$data['name'], $data['value']]);
    }

    public function update($id, $data) {
        if (!isset($data['name'], $data['value'])) return false;
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET name = ?, value = ? WHERE id = ?");
        return $stmt->execute([$data['name'], $data['value'], $id]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}