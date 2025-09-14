<?php
require('../inc/essentials.php');
require('../inc/db_config.php');

adminLogin();

require('../classes/DineInOptions.php');

try {
    $dineIn = new DineInOptions($con);
} catch (Exception $e) {
    error_log('Database connection failed: ' . $e->getMessage());
    echo 0;
    exit;
}

function uploadImageNew($file) {
    $uploadDir = '../../images/dine_in/';
    
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    if (!in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, and WebP allowed.'];
    }
    
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'message' => 'File size too large. Maximum 5MB allowed.'];
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'dine_in_' . uniqid() . '.' . $extension;
    $filepath = $uploadDir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'filename' => $filename];
    } else {
        return ['success' => false, 'message' => 'Failed to upload file.'];
    }
}

if (isset($_POST['add_dine_in_option'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $open_hours = mysqli_real_escape_string($con, $_POST['open_hours']);
    $type_of_dine_in = mysqli_real_escape_string($con, $_POST['type_of_dine_in']);
    $location = mysqli_real_escape_string($con, $_POST['location']);
    
    if (empty($name) || empty($description) || empty($open_hours)) {
        echo 0; 
        exit;
    }
    
    if ($dineIn->nameExists($name)) {
        echo 2;
        exit;
    }
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $uploadResult = uploadImageNew($_FILES['image']);
        
        if (!$uploadResult['success']) {
            echo 0; 
            exit;
        }
        
        $imageName = $uploadResult['filename'];
    } else {
        echo 0;
        exit;
    }
    
    if ($dineIn->add($name, $description, $open_hours, $imageName, $type_of_dine_in, $location)) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['get_all_dine_in_options'])) {
    $options = $dineIn->getAll();
    $output = '';
    
    if (empty($options)) {
        echo '<tr><td colspan="6" class="text-center">No dine-in options found</td></tr>';
        exit;
    }
    
    foreach ($options as $index => $option) {
        $imagePath = '../../images/dine_in/' . $option['image'];
        $imageDisplay = file_exists($imagePath) ? 
            '<img src="images/dine_in/' . $option['image'] . '" class="rounded me-2" width="50" height="50" style="object-fit: cover;">' :
            '<div class="bg-secondary rounded me-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; color: white; font-size: 12px;">No Image</div>';
        
        $statusButton = $option['status'] ? 
            '<button type="button" onclick="toggleStatus(' . $option['id'] . ', 0)" class="btn btn-sm btn-success">Active</button>' :
            '<button type="button" onclick="toggleStatus(' . $option['id'] . ', 1)" class="btn btn-sm btn-danger">Inactive</button>';
        
        $output .= '
        <tr>
            <td>' . ($index + 1) . '</td>
            <td>
                <div class="d-flex align-items-center">
                   
                    <span>' . htmlspecialchars($option['name']) . '</span>
                </div>
            </td>
            <td>' . htmlspecialchars($option['type_of_dine_in']) . '</td>
            <td>' . htmlspecialchars($option['location']) . '</td>
            <td>' . htmlspecialchars($option['open_hours']) . '</td>
            <td>
                <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="' . htmlspecialchars($option['description']) . '">
                    ' . htmlspecialchars($option['description']) . '
                </div>
            </td>
            <td>' . $statusButton . '</td>
            <td>
                <button type="button" onclick="editDineInOption(' . $option['id'] . ')" class="btn btn-sm btn-primary shadow-none me-2" title="Edit">
                    <i class="bi bi-pencil-square"></i>
                </button>
                <button type="button" onclick="deleteDineInOption(' . $option['id'] . ')" class="btn btn-sm btn-danger shadow-none" title="Delete">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
            
        </tr>';
    }
    
    echo $output;
}

if (isset($_POST['get_dine_in_option'])) {
    $id = (int)$_POST['id'];
    
    if ($id) {
        $option = $dineIn->getById($id);
        echo json_encode($option);
    } else {
        echo json_encode(null);
    }
}

if (isset($_POST['update_dine_in_option'])) {
    $id = (int)$_POST['id'];
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $open_hours = mysqli_real_escape_string($con, $_POST['open_hours']);
    $type_of_dine_in = mysqli_real_escape_string($con, $_POST['type_of_dine_in']);
    $location = mysqli_real_escape_string($con, $_POST['location']);
    
    if (!$id || empty($name) || empty($description) || empty($open_hours)) {
        echo 0; 
        exit;
    }
    
    if ($dineIn->nameExists($name, $id)) {
        echo 2; 
        exit;
    }
    
    $imageName = null;
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $uploadResult = uploadImageNew($_FILES['image']);
        
        if (!$uploadResult['success']) {
            echo 0; 
            exit;
        }
        
        $imageName = $uploadResult['filename'];
        
        $oldOption = $dineIn->getById($id);
        if ($oldOption && file_exists('../../images/dine_in/' . $oldOption['image'])) {
            unlink('../../images/dine_in/' . $oldOption['image']);
        }
    }
    
    if ($dineIn->update($id, $name, $description, $open_hours, $imageName, $type_of_dine_in, $location)) {
        echo 1; 
    } else {
        echo 0; 
    }
}

if (isset($_POST['delete_dine_in_option'])) {
    $id = (int)$_POST['id'];
    
    if ($id && $dineIn->delete($id)) {
        echo 1;
    } else {
        echo 0; 
    }
}

if (isset($_POST['toggle_status'])) {
    $id = (int)$_POST['id'];
    $status = (int)$_POST['status'];
    
    if ($id && in_array($status, [0, 1]) && $dineIn->toggleStatus($id, $status)) {
        echo 1;
    } else {
        echo 0; 
    }
}
?>