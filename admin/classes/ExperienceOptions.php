<?php
class ExperienceOptions {
    private $conn;
    private $table = 'experience_options';
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function add($name, $description, $highlight, $image, $priceList, $guide_name, $guide_email, $guide_phone) {
        try {
            $query = "INSERT INTO {$this->table} (name, description, highlight, image, priceList, guide_name, guide_email, guide_phone ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssssssss", $name, $description, $highlight, $image, $priceList, $guide_name, $guide_email, $guide_phone);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error adding experience option: " . $e->getMessage());
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
            error_log("Error getting experience options: " . $e->getMessage());
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
            error_log("Error getting experience option by ID: " . $e->getMessage());
            return null;
        }
    }
    
    public function update($id, $name, $description, $highlight, $image = null, $priceList = null, $guide_name = null, $guide_email = null, $guide_phone = null) {
        try {
            if ($image && $priceList) {
                $query = "UPDATE {$this->table} SET name = ?, description = ?, highlight = ?, image = ?, priceList = ?, guide_name= ?, guide_email =?, guide_phone= ? WHERE id = ?";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("ssssssssi", $name, $description, $highlight, $image, $priceList, $guide_name, $guide_email, $guide_phone , $id);
            } elseif ($image) {
                $query = "UPDATE {$this->table} SET name = ?, description = ?, highlight = ?, image = ?, guide_name= ?, guide_email =?, guide_phone= ? WHERE id = ?";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("sssssssi", $name, $description, $highlight, $image, $guide_name, $guide_email, $guide_phone, $id);
            } elseif ($priceList) {
                $query = "UPDATE {$this->table} SET name = ?, description = ?, highlight = ?, priceList = ?, guide_name= ?, guide_email =?, guide_phone= ? WHERE id = ?";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("sssssssi", $name, $description, $highlight, $priceList, $guide_name, $guide_email, $guide_phone, $id);
            } else {
                $query = "UPDATE {$this->table} SET name = ?, description = ?, highlight = ?, guide_name= ?, guide_email =?, guide_phone= ? WHERE id = ?";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("ssssssi", $name, $description, $highlight, $guide_name, $guide_email, $guide_phone, $id);
            }
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error updating experience option: " . $e->getMessage());
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
            error_log("Error deleting experience option: " . $e->getMessage());
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