<?php
require('../inc/essentials.php');
require('../inc/db_config.php');

adminLogin();

require('../classes/ExperienceOptions.php');

try {
    $experience = new ExperienceOptions($con);
} catch (Exception $e) {
    error_log('Database connection failed: ' . $e->getMessage());
    echo 0;
    exit;
}

function uploadImageNew($file) {
    $uploadDir = '../../images/experience/';
    
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
    $filename = 'experience_' . uniqid() . '.' . $extension;
    $filepath = $uploadDir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'filename' => $filename];
    } else {
        return ['success' => false, 'message' => 'Failed to upload file.'];
    }
}

function uploadImagePDF($file) {
    $uploadDir = '../../images/experience/';
    
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $allowedTypes = ['application/pdf'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    if (!in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'message' => 'Invalid file type. Only PDF allowed.'];
    }
    
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'message' => 'File size too large. Maximum 5MB allowed.'];
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'experience_' . uniqid() . '.' . $extension;
    $filepath = $uploadDir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'filename' => $filename];
    } else {
        return ['success' => false, 'message' => 'Failed to upload file.'];
    }
}

if (isset($_POST['add_experience_option'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $highlight = mysqli_real_escape_string($con, $_POST['highlight']);
    $guide_name = mysqli_real_escape_string($con, $_POST['guide_name']);
    $guide_email = mysqli_real_escape_string($con, $_POST['guide_email']);  
    $guide_phone = mysqli_real_escape_string($con, $_POST['guide_phone']);
    
    if (empty($name) || empty($description) || empty($highlight)) {
        echo 0; 
        exit;
    }
    
    if ($experience->nameExists($name)) {
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

    if (isset($_FILES['pricelist']) && $_FILES['pricelist']['error'] == 0) {
        $uploadResult = uploadImagePDF($_FILES['pricelist']);
        
        if (!$uploadResult['success']) {
            echo 0; 
            exit;
        }
        
        $priceListName = $uploadResult['filename'];
    } else {
        echo 0;
        exit;
    }
    
    if ($experience->add($name, $description, $highlight, $imageName , $priceListName, $guide_name, $guide_email, $guide_phone)) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['get_all_experience_options'])) {
    $options = $experience->getAll();
    $output = '';
    
    if (empty($options)) {
        echo '<tr><td colspan="6" class="text-center">No dine-in options found</td></tr>';
        exit;
    }
    
    foreach ($options as $index => $option) {
        $imagePath = '../../images/experience/' . $option['image'];
        $imageDisplay = file_exists($imagePath) ? 
            '<img src="images/experience/' . $option['image'] . '" class="rounded me-2" width="50" height="50" style="object-fit: cover;">' :
            '<div class="bg-secondary rounded me-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; color: white; font-size: 12px;">No Image</div>';
        $viewPriceList = file_exists('../../images/experience/' . $option['priceList']) ? 
            '<a href="/hms/images/experience/' . $option['priceList'] . '" target="_blank" class="btn btn-sm btn-info">View Price List</a>' :
            '<span class="text-secondary">No Price List</span>';
        $statusButton = $option['status'] ? 
            '<button type="button" onclick="toggleStatus(' . $option['id'] . ', 0)" class="btn btn-sm btn-success">Active</button>' :
            '<button type="button" onclick="toggleStatus(' . $option['id'] . ', 1)" class="btn btn-sm btn-danger">Inactive</button>';
        
        $output .= '
        <tr>
            <td>' . ($index + 1) . '</td>
            <td>
                <div class="d-flex align-items-center">
                    <span style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" >' . htmlspecialchars($option['name']) . '</span>
                </div>
            </td>
            <td>' . htmlspecialchars($option['highlight']) . '</td>
            <td>
                <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="' . htmlspecialchars($option['description']) . '">
                    ' . htmlspecialchars($option['description']) . '
                </div>
            </td>
            <td>' . $viewPriceList . '</td>
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

if (isset($_POST['get_experience_option'])) {
    $id = (int)$_POST['id'];
    
    if ($id) {
        $option = $experience->getById($id);
        echo json_encode($option);
    } else {
        echo json_encode(null);
    }
}

if (isset($_POST['update_experience_option'])) {
    $id = (int)$_POST['id'];
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $highlight = mysqli_real_escape_string($con, $_POST['highlight']);
    $guide_name = mysqli_real_escape_string($con, $_POST['guide_name']);
    $guide_email = mysqli_real_escape_string($con, $_POST['guide_email']);
    $guide_phone = mysqli_real_escape_string($con, $_POST['guide_phone']);
    
    if (!$id || empty($name) || empty($description) || empty($highlight)) {
        echo 0; 
        exit;
    }
    
    if ($experience->nameExists($name, $id)) {
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
        
        $oldOption = $experience->getById($id);
        if ($oldOption && file_exists('../../images/experience/' . $oldOption['image'])) {
            unlink('../../images/experience/' . $oldOption['image']);
        }
    }

    $priceListName = null;

    if (isset($_FILES['pricelist']) && $_FILES['pricelist']['error'] == 0) {
        $uploadResult = uploadImagePDF($_FILES['pricelist']);
        
        if (!$uploadResult['success']) {
            echo 0; 
            exit;
        }
        
        $priceListName = $uploadResult['filename'];
        
        $oldOption = $experience->getById($id);
        if ($oldOption && file_exists('../../images/experience/' . $oldOption['priceList'])) {
            unlink('../../images/experience/' . $oldOption['priceList']);
        }
    }
    
    if ($experience->update($id, $name, $description, $highlight, $imageName, $priceListName, $guide_name, $guide_email, $guide_phone)) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['delete_experience_option'])) {
    $id = (int)$_POST['id'];
    
    if ($id && $experience->delete($id)) {
        echo 1;
    } else {
        echo 0; 
    }
}

if (isset($_POST['toggle_status'])) {
    $id = (int)$_POST['id'];
    $status = (int)$_POST['status'];
    
    if ($id && in_array($status, [0, 1]) && $experience->toggleStatus($id, $status)) {
        echo 1; 
    } else {
        echo 0;
    }
}
?>