<?php
require_once('admin/inc/essentials.php');
require_once('admin/inc/db_config.php');

require_once 'admin/classes/Utility.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: dine_in.php");
    exit;
}

$id = intval($_GET['id']);
$query = "SELECT * FROM dine_in_options WHERE id = ? AND status = 1";
$result = select($query, [$id], 'i');

if (!$result || mysqli_num_rows($result) == 0) {
    $option = null;
} else {
    $option = mysqli_fetch_assoc($result);
}

$settings_q = "SELECT * FROM `settings` WHERE `sr_no`=?";
$settings_r = mysqli_fetch_assoc(select($settings_q, [1], 'i'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $settings_r['site_title']; ?> - Dine In Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <div class="container my-5">
        <?php if (!$option): ?>
            <div class="alert alert-danger text-center">
                <h4>Dine In Option Not Found</h4>
                <p>The item you’re looking for doesn’t exist or has been removed.</p>
                <a href="dine_in.php" class="btn btn-primary mt-3">Back to Dine In</a>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <img src="./images/dine_in/<?php echo $option['image']; ?>" 
                        alt="Dine In Image" class="img-fluid rounded shadow" style="width: 100%; height: auto;">
                </div>
                <div class="col-md-6">
                    <h2 class="fw-bold"><?php echo htmlspecialchars($option['name']); ?></h2>
                    <p class="text-muted" style="white-space: pre-line;">
                        <?php echo  str_replace(["\\r\\n", "\\n", "\\r"], ' ', $option['description']); ?>
                    </p>

                    <p>
                        <strong>Type of Dine-In: </strong> <?php echo htmlspecialchars($option['type_of_dine_in']); ?>
                    </p>
                    <p>
                        <strong>Location: </strong> <?php echo htmlspecialchars($option['location']); ?>
                    </p>
                    <p>
                        <strong><?php echo htmlspecialchars($option['open_hours']); ?> </strong> 
                    </p>
                    

                    <a href="dine_in.php" class="btn btn-outline-secondary mt-4">← Back to All Options</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php require('inc/footer.php'); ?>

</body>
</html>
