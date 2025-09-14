<?php
class BaseModel {
    protected $db;
    public function __construct($db) {
        $this->db = $db;
    }
    // Common method example
    public function findById($table, $id) {
        $stmt = $this->db->query("SELECT * FROM $table WHERE id = ?", [$id]);
        return $stmt->fetch();
    }
} 