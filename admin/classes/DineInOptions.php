<?php
class DineInOptions {
    private $conn;
    private $table = 'dine_in_options';
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function add($name, $description, $open_hours, $image, $type_of_dine_in, $location) {
        try {
            $query = "INSERT INTO {$this->table} (name, description, open_hours, image, type_of_dine_in, location  ) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssssss", $name, $description, $open_hours, $image, $type_of_dine_in, $location);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error adding dine-in option: " . $e->getMessage());
            return false;
        }
    }
    
    public function getAll() {
        try {
            $query = "SELECT * FROM {$this->table}  ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting dine-in options: " . $e->getMessage());
            return [];
        }
    }
    
    public function getById($id) {
        try {
            $query = "SELECT * FROM {$this->table} WHERE id = ? AND status = 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error getting dine-in option by ID: " . $e->getMessage());
            return null;
        }
    }
    
    public function update($id, $name, $description, $open_hours, $image = null, $type_of_dine_in = null, $location = null) {
        try {
            if ($image) {
                $query = "UPDATE {$this->table} SET name = ?, description = ?, open_hours = ?, image = ?, type_of_dine_in = ?, location = ?  WHERE id = ?";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("ssssssi", $name, $description, $open_hours, $image, $type_of_dine_in, $location, $id);
            } else {
                $query = "UPDATE {$this->table} SET name = ?, description = ?, open_hours = ?, type_of_dine_in = ?, location = ? WHERE id = ?";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("sssssi", $name, $description, $open_hours, $type_of_dine_in, $location, $id);
            }
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error updating dine-in option: " . $e->getMessage());
            return false;
        }
    }
    
    public function delete($id) {
        try {
            $query = "DELETE FROM {$this->table} WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error deleting dine-in option: " . $e->getMessage());
            return false;
        }
    }
    
    public function toggleStatus($id, $status) {
        try {
            $query = "UPDATE {$this->table} SET status = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ii", $status, $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error toggling status: " . $e->getMessage());
            return false;
        }
    }
    
    public function nameExists($name, $excludeId = null) {
        try {
            if ($excludeId) {
                $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE name = ? AND id != ? AND status = 1";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("si", $name, $excludeId);
            } else {
                $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE name = ? AND status = 1";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("s", $name);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row['count'] > 0;
        } catch (Exception $e) {
            error_log("Error checking name existence: " . $e->getMessage());
            return false;
        }
    }
}
?>