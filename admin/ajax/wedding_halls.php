<?php
require_once('../inc/db_config.php');
require_once('../inc/essentials.php');
require('../classes/Utility.php');

adminLogin();

// Get all wedding halls
if (isset($_POST['get_wedding_halls'])) {
    try {
        $query = "SELECT * FROM wedding_halls ORDER BY created_at DESC";
        $result = select($query, [], '');
        $wedding_halls = [];
        
        while ($row = mysqli_fetch_assoc($result)) {
            // Get image count
            $img_query = "SELECT COUNT(*) as count FROM wedding_images WHERE wedding_id = ?";
            $img_result = select($img_query, [$row['id']], 'i');
            $img_count = mysqli_fetch_assoc($img_result)['count'];
            
            // Get inquiry count
            $inq_query = "SELECT COUNT(*) as count FROM wedding_inquiries WHERE wedding_id = ?";
            $inq_result = select($inq_query, [$row['id']], 'i');
            $inq_count = mysqli_fetch_assoc($inq_result)['count'];
            
            $row['image_count'] = $img_count;
            $row['inquiry_count'] = $inq_count;
            $wedding_halls[] = $row;
        }
        
        echo json_encode($wedding_halls);
    } catch (Exception $e) {
        echo json_encode([]);
    }
}

// Add wedding hall
if (isset($_POST['add_wedding_hall'])) {
    $data = filteration($_POST);
    
    // Validate required fields
    if (empty($data['name']) || empty($data['description'])) {
        echo 'required_fields';
        exit;
    }
    
    try {
        // Handle PDF upload
        $pdf_filename = null;
        if (isset($_FILES['package_pdf']) && $_FILES['package_pdf']['error'] === 0) {
            $upload_result = Utility::uploadPDF($_FILES['package_pdf'], WEDDING_PDF_FOLDER);
            if ($upload_result['status'] === 'success') {
                $pdf_filename = $upload_result['filename'];
            } else {
                echo 'pdf_upload_failed';
                exit;
            }
        }
        
        // Insert wedding hall
        $query = "INSERT INTO wedding_halls (name, description, package_pdf) VALUES (?, ?, ?)";
        $result = insert($query, [$data['name'], $data['description'], $pdf_filename], 'sss');
        
        if ($result) {
            $wedding_hall_id = mysqli_insert_id($GLOBALS['con']);
            
            // Handle image uploads
            if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                    if ($_FILES['images']['error'][$key] === 0) {
                        $image_file = [
                            'name' => $_FILES['images']['name'][$key],
                            'type' => $_FILES['images']['type'][$key],
                            'tmp_name' => $tmp_name,
                            'error' => $_FILES['images']['error'][$key],
                            'size' => $_FILES['images']['size'][$key]
                        ];
                        
                        $upload_result = Utility::uploadImage($image_file, WEDDING_FOLDER);
                        if ($upload_result['status'] === 'success') {
                            $img_query = "INSERT INTO wedding_images (wedding_id, image_path) VALUES (?, ?)";
                            insert($img_query, [$wedding_hall_id, $upload_result['filename']], 'is');
                        }
                    }
                }
            }
            
            echo 'success';
        } else {
            echo 'failed';
        }
    } catch (Exception $e) {
        echo 'error';
    }
}

// Get wedding hall details for editing
if (isset($_POST['get_wedding_hall'])) {
    $data = filteration($_POST);
    
    try {
        $query = "SELECT * FROM wedding_halls WHERE id = ?";
        $result = select($query, [$data['wedding_hall_id']], 'i');
        $wedding_hall = mysqli_fetch_assoc($result);
        
        if ($wedding_hall) {
            // Get images
            $img_query = "SELECT * FROM wedding_images WHERE wedding_id = ? ORDER BY id ASC";
            $img_result = select($img_query, [$data['wedding_hall_id']], 'i');
            $images = [];
            while ($img = mysqli_fetch_assoc($img_result)) {
                $images[] = $img;
            }
            
            $wedding_hall['images'] = $images;
            echo json_encode($wedding_hall);
        } else {
            echo 'not_found';
        }
    } catch (Exception $e) {
        echo 'error';
    }
}

// Update wedding hall
if (isset($_POST['update_wedding_hall'])) {
    $data = filteration($_POST);
    
    try {
        $wedding_hall_id = $data['wedding_hall_id'];
        
        // Handle PDF upload
        $pdf_filename = null;
        if (isset($_FILES['package_pdf']) && $_FILES['package_pdf']['error'] === 0) {
            $upload_result = Utility::uploadPDF($_FILES['package_pdf'], WEDDING_PDF_FOLDER);
            if ($upload_result['status'] === 'success') {
                $pdf_filename = $upload_result['filename'];
            } else {
                echo 'pdf_upload_failed';
                exit;
            }
        }
        
        // Update wedding hall
        if ($pdf_filename) {
            $query = "UPDATE wedding_halls SET name = ?, description = ?, package_pdf = ? WHERE id = ?";
            $result = update($query, [$data['name'], $data['description'], $pdf_filename, $wedding_hall_id], 'sssi');
        } else {
            $query = "UPDATE wedding_halls SET name = ?, description = ? WHERE id = ?";
            $result = update($query, [$data['name'], $data['description'], $wedding_hall_id], 'ssi');
        }
        if ($result !== false) {
            // Handle additional image uploads
            if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                    if ($_FILES['images']['error'][$key] === 0) {
                        $image_file = [
                            'name' => $_FILES['images']['name'][$key],
                            'type' => $_FILES['images']['type'][$key],
                            'tmp_name' => $tmp_name,
                            'error' => $_FILES['images']['error'][$key],
                            'size' => $_FILES['images']['size'][$key]
                        ];
                        
                        $upload_result = Utility::uploadImage($image_file, WEDDING_FOLDER);
                        if ($upload_result['status'] === 'success') {
                            $img_query = "INSERT INTO wedding_images (wedding_id, image_path) VALUES (?, ?)";
                            insert($img_query, [$wedding_hall_id, $upload_result['filename']], 'is');
                        }
                    }
                }
            }
            
            echo 'success';
        } else {
            echo 'failed';
        }
    } catch (Exception $e) {
        echo 'error';
    }
}

// Delete wedding hall
if (isset($_POST['delete_wedding_hall'])) {
    $data = filteration($_POST);
    
    try {
        $wedding_hall_id = $data['wedding_hall_id'];
        
        // Get wedding hall data to clean up files
        $query = "SELECT * FROM wedding_halls WHERE id = ?";
        $result = select($query, [$wedding_hall_id], 'i');
        $wedding_hall = mysqli_fetch_assoc($result);
        
        if ($wedding_hall) {
            // Delete PDF file if exists
            if (!empty($wedding_hall['package_pdf'])) {
                Utility::deletePDF($wedding_hall['package_pdf'], WEDDING_PDF_FOLDER);
            }
            
            // Delete all images
            $img_query = "SELECT * FROM wedding_images WHERE wedding_id = ?";
            $img_result = select($img_query, [$wedding_hall_id], 'i');
            while ($image = mysqli_fetch_assoc($img_result)) {
                Utility::deleteFile($image['image_path'], WEDDING_FOLDER);
            }
            
            // Delete all inquiries
            delete("DELETE FROM wedding_inquiries WHERE wedding_id = ?", [$wedding_hall_id], 'i');
            
            // Delete all images from database
            delete("DELETE FROM wedding_images WHERE wedding_id = ?", [$wedding_hall_id], 'i');
            
            // Delete wedding hall
            $del_query = "DELETE FROM wedding_halls WHERE id = ?";
            $result = delete($del_query, [$wedding_hall_id], 'i');
            
            if ($result) {
                echo 'success';
            } else {
                echo 'failed';
            }
        } else {
            echo 'not_found';
        }
    } catch (Exception $e) {
        echo 'error';
    }
}

// Delete wedding hall image
if (isset($_POST['delete_wedding_image'])) {
    $data = filteration($_POST);
    
    try {
        $image_id = $data['image_id'];
        
        // Get image data to delete the file
        $query = "SELECT * FROM wedding_images WHERE id = ?";
        $result = select($query, [$image_id], 'i');
        $image = mysqli_fetch_assoc($result);
        
        if ($image) {
            // Delete the actual file
            Utility::deleteFile($image['image_path'], WEDDING_FOLDER);
            
            // Delete from database
            $del_query = "DELETE FROM wedding_images WHERE id = ?";
            $result = delete($del_query, [$image_id], 'i');
            
            if ($result) {
                echo 'success';
            } else {
                echo 'failed';
            }
        } else {
            echo 'not_found';
        }
    } catch (Exception $e) {
        echo 'error';
    }
}
?> 