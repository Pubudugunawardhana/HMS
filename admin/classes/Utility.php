<?php

// Inheritance

// Classes like RoomManager, UserManager, BookingManager can extend
// to inherit shared logic
//(e.g., validation, Filter and sanitize input data , Upload image file , Upload PDF file).

class Utility {
    
    /**
     * Filter and sanitize input data
     */
    public static function filterInput($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::filterInput($value);
            }
        } else {
            $data = trim($data);
            $data = stripslashes($data);
            $data = strip_tags($data);
            $data = htmlspecialchars($data);
        }
        return $data;
    }
    
    /**
     * Ensure directory exists, create if it doesn't
     */
    private static function ensureDirectoryExists($directory) {
        if (!is_dir($directory)) {
            if (!mkdir($directory, 0755, true)) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * Upload image file
     */
    public static function uploadImage($image, $folder) {
        if (!isset($image['tmp_name']) || empty($image['tmp_name'])) {
            return ['status' => 'error', 'message' => 'No file uploaded'];
        }
        
        $img_mime = $image['type'];
        // Define allowed image types if not already defined
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!in_array($img_mime, $allowed_types)) {
            return ['status' => 'error', 'message' => 'Invalid image format'];
        }
        
        $max_size = 2 * 1024 * 1024; // 2MB
        if ($image['size'] > $max_size) {
            return ['status' => 'error', 'message' => 'Image size too large (max 2MB)'];
        }
        
        // Ensure the upload directory exists
        $upload_dir = UPLOAD_IMAGE_PATH . $folder;
        if (!self::ensureDirectoryExists($upload_dir)) {
            return ['status' => 'error', 'message' => 'Failed to create upload directory'];
        }
        
        $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
        $filename = 'IMG_' . random_int(11111, 99999) . '.' . $ext;
        $upload_path = $upload_dir . $filename;
        
        if (move_uploaded_file($image['tmp_name'], $upload_path)) {
            return ['status' => 'success', 'filename' => $filename];
        } else {
            return ['status' => 'error', 'message' => 'Upload failed'];
        }
    }
    
    /**
     * Upload PDF file
     */
    public static function uploadPDF($file, $folder) {
        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            return ['status' => 'error', 'message' => 'No file uploaded'];
        }
        
        $file_mime = $file['type'];
        $allowed_pdf_types = ['application/pdf'];
        if (!in_array($file_mime, $allowed_pdf_types)) {
            return ['status' => 'error', 'message' => 'Invalid PDF format'];
        }
        
        $max_pdf_size = 5 * 1024 * 1024; // 5MB
        if ($file['size'] > $max_pdf_size) {
            return ['status' => 'error', 'message' => 'PDF size too large (max 5MB)'];
        }
        
        // Ensure the upload directory exists
        $upload_dir = UPLOAD_PDF_PATH . $folder;
        if (!self::ensureDirectoryExists($upload_dir)) {
            return ['status' => 'error', 'message' => 'Failed to create upload directory'];
        }
        
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'PDF_' . random_int(11111, 99999) . '.' . $ext;
        $upload_path = $upload_dir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            return ['status' => 'success', 'filename' => $filename];
        } else {
            return ['status' => 'error', 'message' => 'Upload failed'];
        }
    }
    
    /**
     * Delete file
     */
    public static function deleteFile($filename, $folder) {
        $file_path = UPLOAD_IMAGE_PATH . $folder . $filename;
        if (file_exists($file_path) && unlink($file_path)) {
            return true;
        }
        return false;
    }
    
    /**
     * Delete PDF file
     */
    public static function deletePDF($filename, $folder) {
        $file_path = UPLOAD_PDF_PATH . $folder . $filename;
        if (file_exists($file_path) && unlink($file_path)) {
            return true;
        }
        return false;
    }
    
    /**
     * Generate alert message
     */
    public static function showAlert($type, $message) {
        $bs_class = ($type == "success") ? "alert-success" : "alert-danger";
        return "<div class='alert $bs_class alert-dismissible fade show custom-alert' role='alert'>
                    <strong class='me-3'>$message</strong>
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
    }
    
    /**
     * Validate email
     */
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    /**
     * Generate random string
     */
    public static function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
    
    /**
     * Format date
     */
    public static function formatDate($date, $format = 'Y-m-d H:i:s') {
        return date($format, strtotime($date));
    }
    
    /**
     * Redirect to URL
     */
    public static function redirect($url) {
        echo "<script>window.location.href='$url';</script>";
        exit;
    }
    
    /**
     * Check if user is logged in
     */
    public static function isLoggedIn() {
        session_start();
        return isset($_SESSION['login']) && $_SESSION['login'] === true;
    }
    
    /**
     * Check if admin is logged in
     */
    public static function isAdminLoggedIn() {
        session_start();
        return isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] === true;
    }
    
    /**
     * Require admin login
     */
    public static function requireAdminLogin() {
        if (!self::isAdminLoggedIn()) {
            self::redirect('index.php');
        }
    }
    
    /**
     * Get current user ID
     */
    public static function getCurrentUserId() {
        session_start();
        return $_SESSION['uId'] ?? null;
    }
    
    /**
     * Pagination helper
     */
    public static function getPagination($total_records, $current_page, $per_page = 10) {
        $total_pages = ceil($total_records / $per_page);
        $offset = ($current_page - 1) * $per_page;
        
        return [
            'total_pages' => $total_pages,
            'current_page' => $current_page,
            'offset' => $offset,
            'per_page' => $per_page
        ];
    }

    /**
     * Set alert message in session
     */
    public static function setAlert($type, $message) {
        // Only start session if not already started and headers not sent
        if (session_status() == PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }
        $_SESSION['alert'] = [
            'type' => $type,
            'message' => $message
        ];
    }

    /**
     * Display alert messages and clear them
     */
    public static function displayAlerts() {
        // Only start session if not already started and headers not sent
        if (session_status() == PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }
        if (isset($_SESSION['alert'])) {
            $alert = $_SESSION['alert'];
            $type = $alert['type'];
            $message = $alert['message'];
            
            // Clear the alert
            unset($_SESSION['alert']);
            
            // Return the alert HTML
            return self::showAlert($type, $message);
        }
        return '';
    }
}