<?php
require_once('../inc/db_config.php');
require_once('../inc/essentials.php');

adminLogin();

// Get all wedding inquiries
if (isset($_POST['get_wedding_inquiries'])) {
    try {
        $query = "SELECT wi.*, wh.name as wedding_hall_name 
                  FROM wedding_inquiries wi 
                  LEFT JOIN wedding_halls wh ON wi.wedding_id = wh.id 
                  ORDER BY wi.created_at DESC";
        $result = select($query, [], '');
        $inquiries = [];
        
        while ($row = mysqli_fetch_assoc($result)) {
            $inquiries[] = $row;
        }
        
        echo json_encode($inquiries);
    } catch (Exception $e) {
        echo json_encode([]);
    }
}

// Get single inquiry details
if (isset($_POST['get_inquiry'])) {
    $data = filteration($_POST);
    
    try {
        $query = "SELECT wi.*, wh.name as wedding_hall_name 
                  FROM wedding_inquiries wi 
                  LEFT JOIN wedding_halls wh ON wi.wedding_id = wh.id 
                  WHERE wi.id = ?";
        $result = select($query, [$data['inquiry_id']], 'i');
        $inquiry = mysqli_fetch_assoc($result);
        
        if ($inquiry) {
            echo json_encode($inquiry);
        } else {
            echo 'not_found';
        }
    } catch (Exception $e) {
        echo 'error';
    }
}

// Delete inquiry
if (isset($_POST['delete_inquiry'])) {
    $data = filteration($_POST);
    
    try {
        $query = "DELETE FROM wedding_inquiries WHERE id = ?";
        $result = delete($query, [$data['inquiry_id']], 'i');
        
        if ($result) {
            echo 'success';
        } else {
            echo 'failed';
        }
    } catch (Exception $e) {
        echo 'error';
    }
}
?> 