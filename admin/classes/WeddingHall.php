<?php

// class BaseModel {
//     protected $db;
//     public function __construct($db) {
//         $this->db = $db;
//     }
//     // Common method example
//     public function findById($table, $id) {
//         $stmt = $this->db->query("SELECT * FROM $table WHERE id = ?", [$id]);
//         return $stmt->fetch();
//     }
// } 


// interface CRUDInterface {
//     public function create($data);
//     public function read($id);
//     public function update($data);
//     public function delete($id);
// } 

// require_once __DIR__ . '/BaseModel.php';
// require_once __DIR__ . '/CRUDInterface.php';

// class WeddingHall extends BaseModel implements CRUDInterface {
    // Now $this->db is available from BaseModel

    // // Create a new wedding hall (from CRUDInterface)
    // public function create($data) {
    //     return $this->createWeddingHall($data);
    // }
    // // Read/get a wedding hall by ID (from CRUDInterface)
    // public function read($id) {
    //     return $this->getWeddingHallById($id);
    // }
    // // Update a wedding hall (from CRUDInterface)
    // public function update($data) {
    //     // updateWeddingHall expects id and data
    //     return $this->updateWeddingHall($data['id'], $data);
    // }
    // // Delete a wedding hall (from CRUDInterface)
    // public function delete($id) {
    //     return $this->deleteWeddingHall($id);
    // }

    // --- Other wedding hall-specific methods below ---
    // Get all wedding halls
    public function getAllWeddingHalls($limit = null, $offset = 0) {
        $sql = "SELECT * FROM wedding_halls ORDER BY created_at DESC";
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
            return $this->db->select($sql);
        }
        return $this->db->select($sql);
    }
    
    // Get wedding hall by ID
    public function getWeddingHallById($id) {
        $sql = "SELECT * FROM wedding_halls WHERE id = ?";
        return $this->db->selectOne($sql, [$id]);
    }
    
    // Create new wedding hall (used by create)
    public function createWeddingHall($data) {
        $sql = "INSERT INTO wedding_halls (name, description, package_pdf) VALUES (?, ?, ?)";
        $params = [
            $data['name'],
            $data['description'],
            $data['package_pdf'] ?? null
        ];
        
        return $this->db->insert($sql, $params);
    }
    
    // Update wedding hall
    public function updateWeddingHall($id, $data) {
        $sql = "UPDATE wedding_halls SET name = ?, description = ?, package_pdf = ? WHERE id = ?";
        $params = [
            $data['name'],
            $data['description'],
            $data['package_pdf'] ?? null,
            $id
        ];
        
        return $this->db->update($sql, $params);
    }
    
    // Delete wedding hall
    public function deleteWeddingHall($id) {
        // Get wedding hall data to clean up files
        $wedding_hall = $this->getWeddingHallById($id);
        if ($wedding_hall) {
            // Delete PDF file if exists
            if (!empty($wedding_hall['package_pdf'])) {
                Utility::deletePDF($wedding_hall['package_pdf'], WEDDING_PDF_FOLDER);
            }
            
            // Delete all images
            $images = $this->getWeddingHallImages($id);
            foreach ($images as $image) {
                Utility::deleteFile($image['image_path'], WEDDING_FOLDER);
            }
            
            // Delete all inquiries
            $this->deleteAllWeddingHallInquiries($id);
        }
        
        $sql = "DELETE FROM wedding_halls WHERE id = ?";
        return $this->db->delete($sql, [$id]);
    }
    
    /**
     * Get wedding hall images
     */
    public function getWeddingHallImages($wedding_id) {
        $sql = "SELECT * FROM wedding_images WHERE wedding_id = ? ORDER BY id ASC";
        return $this->db->select($sql, [$wedding_id]);
    }
    
    /**
     * Add wedding hall image
     */
    public function addWeddingHallImage($wedding_id, $image_path) {
        $sql = "INSERT INTO wedding_images (wedding_id, image_path) VALUES (?, ?)";
        return $this->db->insert($sql, [$wedding_id, $image_path]);
    }
    
    /**
     * Delete wedding hall image
     */
    public function deleteWeddingHallImage($image_id) {
        // Get image data to delete the file
        $sql = "SELECT image_path FROM wedding_images WHERE id = ?";
        $image = $this->db->selectOne($sql, [$image_id]);
        
        if ($image) {
            // Delete the actual file
            Utility::deleteFile($image['image_path'], WEDDING_FOLDER);
        }
        
        $sql = "DELETE FROM wedding_images WHERE id = ?";
        return $this->db->delete($sql, [$image_id]);
    }
    
    /**
     * Get wedding hall inquiries
     */
        public function getWeddingHallInquiries($wedding_id = null, $limit = null, $offset = 0) {
        if ($wedding_id) {
            $sql = "SELECT wi.*, wh.name as hall_name 
                    FROM wedding_inquiries wi 
                    JOIN wedding_halls wh ON wi.wedding_id = wh.id 
                    WHERE wi.wedding_id = ? 
                    ORDER BY wi.created_at DESC";
            if ($limit) {
                $sql .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
                return $this->db->select($sql, [(int)$wedding_id]);
            }
            return $this->db->select($sql, [(int)$wedding_id]);
        } else {
            $sql = "SELECT wi.*, wh.name as hall_name 
                    FROM wedding_inquiries wi 
                    JOIN wedding_halls wh ON wi.wedding_id = wh.id 
                    ORDER BY wi.created_at DESC";
            if ($limit) {
                $sql .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
                return $this->db->select($sql);
            }
            return $this->db->select($sql);
        }
    }
    
    /**
     * Create wedding hall inquiry
     */
    public function createWeddingHallInquiry($data) {
        $sql = "INSERT INTO wedding_inquiries (wedding_id, name, email, message) VALUES (?, ?, ?, ?)";
        $params = [
            $data['wedding_id'],
            $data['name'],
            $data['email'],
            $data['message']
        ];
        
        return $this->db->insert($sql, $params);
    }
    
    /**
     * Delete wedding hall inquiry
     */
    public function deleteWeddingHallInquiry($id) {
        $sql = "DELETE FROM wedding_inquiries WHERE id = ?";
        return $this->db->delete($sql, [$id]);
    }
    
    /**
     * Delete all inquiries for a wedding hall
     */
    public function deleteAllWeddingHallInquiries($wedding_id) {
        $sql = "DELETE FROM wedding_inquiries WHERE wedding_id = ?";
        return $this->db->delete($sql, [$wedding_id]);
    }
    
    /**
     * Get total count of wedding halls
     */
    public function getTotalWeddingHalls() {
        $sql = "SELECT COUNT(*) as total FROM wedding_halls";
        $result = $this->db->selectOne($sql);
        return $result['total'];
    }
    
    /**
     * Get total count of inquiries
     */
    public function getTotalInquiries($wedding_id = null) {
        if ($wedding_id) {
            $sql = "SELECT COUNT(*) as total FROM wedding_inquiries WHERE wedding_id = ?";
            $result = $this->db->selectOne($sql, [$wedding_id]);
        } else {
            $sql = "SELECT COUNT(*) as total FROM wedding_inquiries";
            $result = $this->db->selectOne($sql);
        }
        return $result['total'];
    }
    
    /**
     * Search wedding halls
     */
    public function searchWeddingHalls($search_term) {
        $sql = "SELECT * FROM wedding_halls WHERE name LIKE ? OR description LIKE ? ORDER BY created_at DESC";
        $search_param = "%$search_term%";
        return $this->db->select($sql, [$search_param, $search_param]);
    }
    
    /**
     * Get wedding hall with images
     */
    public function getWeddingHallWithImages($id) {
        $wedding_hall = $this->getWeddingHallById($id);
        if ($wedding_hall) {
            $wedding_hall['images'] = $this->getWeddingHallImages($id);
        }
        return $wedding_hall;
    }
    
    /**
     * Validate wedding hall data
     */
    public function validateWeddingHallData($data) {
        $errors = [];
        
        if (empty($data['name'])) {
            $errors[] = 'Wedding hall name is required';
        }
        
        if (empty($data['description'])) {
            $errors[] = 'Description is required';
        }
        
        if (strlen($data['name']) > 255) {
            $errors[] = 'Wedding hall name is too long (max 255 characters)';
        }
        
        return $errors;
    }
    
    /**
     * Validate inquiry data
     */
    public function validateInquiryData($data) {
        $errors = [];
        
        if (empty($data['name'])) {
            $errors[] = 'Name is required';
        }
        
        if (empty($data['email'])) {
            $errors[] = 'Email is required';
        } elseif (!Utility::validateEmail($data['email'])) {
            $errors[] = 'Invalid email format';
        }
        
        if (empty($data['message'])) {
            $errors[] = 'Message is required';
        }
        
        if (empty($data['wedding_id'])) {
            $errors[] = 'Wedding hall is required';
        }
        
        return $errors;
    }
// } 