<?php
require_once('../admin/inc/db_config.php');
require_once('../admin/inc/essentials.php');
require_once('../inc/Mailer.php');

date_default_timezone_set("Asia/Colombo");
$mailer = new Mailer();

if (isset($_POST['action']) && $_POST['action'] === 'submit_inquiry') {
    $data = filteration($_POST);
    
    // Validate required fields
    if (empty($data['name']) || empty($data['email']) || empty($data['message']) || empty($data['wedding_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        exit;
    }
    
    // Validate email
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email format']);
        exit;
    }
    
    try {
        // Insert inquiry into database
        $query = "INSERT INTO wedding_inquiries (wedding_id, name, email, message, created_at) VALUES (?, ?, ?, ?, NOW())";
        $result = insert($query, [$data['wedding_id'], $data['name'], $data['email'], $data['message']], 'isss');
        
        if ($result) {
            // Send confirmation email
            if (!$mailer->sendInquiryMail($data['email'], $data['name'], $data['wedding_id'])) {
                echo json_encode(['status' => 'error', 'message' => 'Failed to send confirmation email']);
                exit;
            }
            echo json_encode(['status' => 'success', 'message' => 'Inquiry submitted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to submit inquiry']);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error occurred']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?> 